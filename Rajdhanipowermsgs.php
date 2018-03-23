<?php
require('RajdhaniPowerHashTagsEnum.php');
require('RajdhaniPowerNoCurrent.php');
require('RajdhaniPowerGetOutage.php');

function remove_emoji($text){
  return preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '?', $text);
}

//For Hash Tag Auto Msgs Start
function checkIfHashTagPresent($messageText) {
	error_log("checkIfHashTagPresent - messageText : " . $messageText);
	$hashTag = RajdhaniPowerHashTagsEnum::None();
	if (stripos($messageText, '#NC') !== false) {
    	$hashTag = RajdhaniPowerHashTagsEnum::NC();
	} 
	error_log("checkIfHashTagPresent - hashTag : " . $hashTag->getName());
	return $hashTag;
}

function getCustomerContractNumber($messageText, $hashTag) {
	error_log("getCustomerContractNumber - messageText : " . $messageText . ", hashTag : " . $hashTag->getName());
	$CA_Number = null;
	$str2Match = null;
	if($hashTag == RajdhaniPowerHashTagsEnum::NC()) {
		$str2Match = '#NC';
	} 
	if(!is_null($str2Match)) {
		//Removing 	New lines & multiple spaces & tabs
		$messageText = trim(preg_replace('/\s+/', ' ', $messageText));

		//old one preg_match('/(?<='.$str2Match.' )\S+/i', $messageText, $match);
		//Removing The space after st2Match
		preg_match('/(?<='.$str2Match.' ).*/i', $messageText, $match);
		// if a value is numeric, positive and integral
		if(!empty($match)) {
			error_log("getCustomerContractNumber - After preg_match : " . $match[0]);
			//Old Removing non numeric chars
			//old $CA_Number = preg_replace("/(\W)+/", "", $match[0]);
			//Removing non-numeric charachters
			$CA_Number = preg_replace("/[^0-9]/", "", $match[0]);
		}
		error_log("getCustomerContractNumber - After Removal of non numeric : " . $CA_Number);
		if(is_numeric($CA_Number) && $CA_Number > 0 && $CA_Number == round($CA_Number, 0)){
			$CA_Number = $CA_Number;
		}else {
			$CA_Number = "";
		}

	}
	error_log("getCustomerContractNumber - CA_Number From Message Text: " . $CA_Number);

	return $CA_Number;
}

function getOutageInfo($CA_Number) {
	error_log("getOutageInfo - CA_Number : " . $CA_Number);
	//TODO Strip + & 91 from the phone number
	return callRajdhaniPowerGetOutageWSNGetUserResponse($CA_Number);
}

function getReply2NoCurrentMsg($CA_Number, $ContactNumber) {
	error_log("getReply2NoCurrentMsg - CA_Number : " . $CA_Number . ", ContactNumber : " . $ContactNumber);
	//TODO Strip + & 91 from the phone number
	//Remove Spaces
	$ContactNumber = preg_replace('/\s+/', '', $ContactNumber);
	//Remove + Sign if any
	$ContactNumber = str_replace("+", "", $ContactNumber);
	//Remove Country Code
	if(substr($ContactNumber, 0, strlen("91")) === "91" && strlen($ContactNumber) > 10) {
		$ContactNumber = substr($ContactNumber, strlen("91"));
	}
	error_log("getReply2NoCurrentMsg - Curated ContactNumber : " . $ContactNumber);
	return callRajdhaniPowerNoCurrentWSNGetUserResponse($CA_Number, $ContactNumber);
}

function handleHashTag($PhoneNumber, $MessageText, $Conversation, $SubDomain, $ApiKey, $AgentNumber, $Source) {

	$hashTag = checkIfHashTagPresent($MessageText);
	if($hashTag == RajdhaniPowerHashTagsEnum::None()) {
		error_log("No Hash Tag Present");
		return null;
	}
	$message2User = null;
	$CA_Number = getCustomerContractNumber($MessageText, $hashTag);

	if($hashTag == RajdhaniPowerHashTagsEnum::NC()) {
		/*$outageInfoFrmWs = getOutageInfo($CA_Number);
		if(empty($outageInfoFrmWs)) {
			error_log("Outage Info is null. So Registering NoCurrent Complaint");
			//$message2User = "Test Msg Frm No Current";
			$message2User = getReply2NoCurrentMsg($CA_Number, $PhoneNumber);
		} else {
			$message2User = $outageInfoFrmWs;
		}*/
		$message2User = getReply2NoCurrentMsg($CA_Number, $PhoneNumber);
	} 
	error_log("handleHashTag - message2User : " . $message2User);
	return $message2User;
}
//For Hash Tag Auto Msgs End

// Check if file is present.. Set the Flag
// Get CustomerId from PhoneNumber
// Get OpenTickets for this customerId
// If there is open Ticket
//    If there is not file, addNoteWithoutAttachement
//    If there is file, addNoteWithAttachement
// If there is no open Tickets
//    If there is not file, createTicketWithoutAttachement
//    If there is file, createTicketWithAttachement

$isSuccess = false;
$isMediaPresent = false;
$shouldCreateNewTicket = false;
$openTicketId = 0;

$output2Echo = "Content Type for Request is: " . $_SERVER['CONTENT_TYPE'];
$output2Echo = $output2Echo . "\r\n";
//comment Echos echo $output2Echo;

//echo (file_get_contents('php://input'));
//echo ("\r\n");

//print_r($_REQUEST);
//echo ("\r\n");


if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(isset($_FILES['file']['name'])) {
		$isMediaPresent = true;
		//comment Echos echo "FileName: " . $_FILES['file']['name'];
		//comment Echos echo "File: " . $_FILES['file']['tmp_name'];
	}

    $PhoneNumber = -1;
	$Message = "";
	$SubDomain = "";
	$ApiKey = "";
	$Conversation = "";
	$AgentNumber = "";
	$Source = "";
	//if( ($isMediaPresent == true && isset($_POST['json'])) || $_SERVER["CONTENT_TYPE"] == "application/json") {
	if( ($isMediaPresent == true && isset($_POST['json'])) || strpos($_SERVER["CONTENT_TYPE"], 'application/json') !== FALSE) {
		$JsonBdy = json_decode("{}");
		//comment Echos echo file_get_contents('php://input') . "\n";
		if($isMediaPresent == true && isset($_POST['json'])) {
			//$JsonBdy = json_decode($_POST['json']);
			$replacedNewLineCharStr = str_replace("\r\n", "\\n", $_POST['json']);
			$replacedNewLineCharStr = str_replace(chr(10), "\\n", $replacedNewLineCharStr);
			$replacedNewLineCharStr = str_replace(chr(13), "\\n", $replacedNewLineCharStr);
			$replacedTabCharStr = str_replace(chr(9), "    ", $replacedNewLineCharStr);
		    $replacedTabCharStr = str_replace("\t", "    ", $replacedTabCharStr);
			$JsonBdy = json_decode($replacedTabCharStr);
		} else {
			//comment Echos echo file_get_contents('php://input') . "\n";
			$replacedNewLineCharStr = str_replace("\r\n", "\\n", file_get_contents('php://input'));
			$replacedNewLineCharStr = str_replace(chr(10), "\\n", $replacedNewLineCharStr);
			$replacedNewLineCharStr = str_replace(chr(13), "\\n", $replacedNewLineCharStr);
			//echo $replacedNewLineCharStr . "\n";
			//echo json_encode($replacedNewLineCharStr) . "\n";
			//$JsonBdy = json_decode(file_get_contents('php://input'));
			$replacedTabCharStr = str_replace(chr(9), "    ", $replacedNewLineCharStr);
		    $replacedTabCharStr = str_replace("\t", "    ", $replacedTabCharStr);
			$JsonBdy = json_decode($replacedTabCharStr);
		}
		//comment Echos echo json_encode($JsonBdy, JSON_PRETTY_PRINT);
		$PhoneNumber = $JsonBdy->phone;
		$Message = $JsonBdy->message;

		//Convert $Message for HTML
		//Now Doing in function only for description & not for Subject
		//$Mesage = htmlspecialchars($Message);
		//$Message = nl2br($Message);

		/*
		$SubDomain = $JsonBdy->subdomain;
		$ApiKey = $JsonBdy->apikey;
		if(isset($JsonBdy->agentnumber))
			$AgentNumber = $JsonBdy->agentnumber;
		if(isset($JsonBdy->source))
			$Source = $JsonBdy->source;
		*/
		$Conversation = empty($JsonBdy->conversation) ? "" : $JsonBdy->conversation;

	} else {
		$PhoneNumber = isset($_POST['phone']) ? $_POST['phone'] : "";
		$Message = isset($_POST['message']) ? $_POST['message'] : "";
		$Conversation = isset($_POST['conversation']) ? $_POST['conversation'] : "";
		

		/*
		$SubDomain = isset($_POST['subdomain']) ? $_POST['subdomain'] : "";
		$ApiKey = isset($_POST['apikey']) ? $_POST['apikey'] : "";
		$AgentNumber = isset($_POST['agentnumber']) ? $_POST['agentnumber'] : "";
		$Source = isset($_POST['source']) ? $_POST['source'] : "";
		*/
		//comment Echos echo "SubDomain:" . $SubDomain . ", ApiKey:" . $ApiKey . ", AgentNumber:" . $AgentNumber . ", Source:" . $Source . "\n";
	}

	error_log("isMediaPresent:" . $isMediaPresent);
	error_log("SubDomain:" . $SubDomain . ", ApiKey:" . $ApiKey . ", AgentNumber:" . $AgentNumber . ", Source:" . $Source);
	error_log("Message:" . $Message . ", Conversation:" . $Conversation . ", PhoneNumber:" . $PhoneNumber);

	if($PhoneNumber == "") {
		error_log("No PhoneNumber Present");
		return;
	}

	
	//comment Echos echo "PhoneNumber:" . $PhoneNumber . ", Message:" . $Message . "\n";
	//comment Echos echo "SubDomain:" . $SubDomain . ", ApiKey:" . $ApiKey . "\n";
	//comment Echos echo "AgentNumber:" . $AgentNumber . ", Source:" . $Source . "\n";

	//Replace Emoji
	$Message = remove_emoji($Message);
	//comment Echos echo "Message After Removing Emoji:" . $Message . "\n";

	$responseMsg2User = handleHashTag($PhoneNumber, $Message, $Conversation, $SubDomain, $ApiKey, $AgentNumber, $Source);

    if(is_null($responseMsg2User)) {
		$responseMsg2User = getDefaultNoCurrentUserMsg();
	}
	error_log("Sending Message after Handling Hashtag : " . $responseMsg2User);
	header("Content-Type: application/json");
	echo '{"messageResponse" : "' . $responseMsg2User . '"}';
} else {
	error_log("Some Unknown Error");
}
  
?>