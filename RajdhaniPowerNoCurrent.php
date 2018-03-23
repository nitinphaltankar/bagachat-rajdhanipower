
<?php

function callNoCurrentWS($inCA_Number, $inContactNumber) {

    error_log ("Input- CA_Number:" . $inCA_Number . ", ContactNumber:" . $inContactNumber);

    $pieces = array("","","","","","");
    try {
        //Removing non alphanumeric chars
        $CA_Number = preg_replace("/(\W)+/", "", $inCA_Number);
        $ContactNumber = preg_replace("/(\W)+/", "", $inContactNumber);
        error_log ("Removing non alphanumeric chars Input- CA_Number:" . $CA_Number . ", ContactNumber:" . $ContactNumber);

        //Removing non numeric chars
        $CA_Number = preg_replace("/[^0-9]/", "", $inCA_Number);
        $ContactNumber = preg_replace("/[^0-9]/", "", $inContactNumber);
        error_log ("Removing non numeric chars Input- CA_Number:" . $CA_Number . ", ContactNumber:" . $ContactNumber);

        $key = "@CPanMPc";
        $code = "NC"; //WhatsApp

        //http://115.249.54.69:7670/Service1.asmx/RegisterWhatsappComplaint?CA=100215763&Phone=8802670645&Code=NC
        //$webServiceToConnect = "http://115.249.67.73:7860/whatsapp/IVRSService.asmx?op=RegisterWhatsappComplaint?CA=".$CA_Number."&Phone=".$ContactNumber."&Code=".$code;
        $webServiceToConnect = "http://115.249.67.73:7860/whatsapp/IVRSService.asmx/RegisterWhatsappComplaint?CA=".$CA_Number."&CODE=".$code."&Mobile=".$ContactNumber;
        //CA=string&CODE=string&Mobile=string
        error_log("Web Service to Call for NoCurrent is : " . $webServiceToConnect);
        //$xmlRaw = file_get_contents($webServiceToConnect);
        $xmlRaw = file_get_contents($webServiceToConnect);
        
        /*
		$disconnectedCustomerXmlRaw = '<DataSet xmlns="http://tempuri.org/"> <xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet"> <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"> <xs:complexType> <xs:choice minOccurs="0" maxOccurs="unbounded"> <xs:element name="Table1"> <xs:complexType> <xs:sequence> <xs:element name="STATUS" type="xs:string" minOccurs="0"/> <xs:element name="COMP_NO" type="xs:string" minOccurs="0"/> </xs:sequence> </xs:complexType> </xs:element> </xs:choice> </xs:complexType> </xs:element> </xs:schema> <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"> <NewDataSet xmlns=""> <Table1 diffgr:id="Table11" msdata:rowOrder="0" diffgr:hasChanges="inserted"> <STATUS>DISCONNECTED CONSUMER</STATUS> <COMP_NO>DISCONNECTED CONSUMER</COMP_NO> </Table1> </NewDataSet> </diffgr:diffgram> </DataSet>';
        $invalidCaNumXmlRaw = '<DataSet xmlns="http://tempuri.org/"> <xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet"> <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"> <xs:complexType> <xs:choice minOccurs="0" maxOccurs="unbounded"> <xs:element name="Table1"> <xs:complexType> <xs:sequence> <xs:element name="STATUS" type="xs:string" minOccurs="0"/> <xs:element name="COMP_NO" type="xs:string" minOccurs="0"/> </xs:sequence> </xs:complexType> </xs:element> </xs:choice> </xs:complexType> </xs:element> </xs:schema> <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"> <NewDataSet xmlns=""> <Table1 diffgr:id="Table11" msdata:rowOrder="0" diffgr:hasChanges="inserted"> <STATUS>INVALID CA NUMBER</STATUS> <COMP_NO>INVALID CA NUMBER</COMP_NO> </Table1> </NewDataSet> </diffgr:diffgram> </DataSet>';
        $alreadyRegisteredRaw = '<DataSet xmlns="http://tempuri.org/"> <xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet"> <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"> <xs:complexType> <xs:choice minOccurs="0" maxOccurs="unbounded"> <xs:element name="Table1"> <xs:complexType> <xs:sequence> <xs:element name="STATUS" type="xs:string" minOccurs="0"/> <xs:element name="COMP_NO" type="xs:string" minOccurs="0"/> </xs:sequence> </xs:complexType> </xs:element> </xs:choice> </xs:complexType> </xs:element> </xs:schema> <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"> <NewDataSet xmlns=""> <Table1 diffgr:id="Table11" msdata:rowOrder="0" diffgr:hasChanges="inserted"> <STATUS>ALREADY REGISTERED</STATUS> <COMP_NO>17092100581</COMP_NO> </Table1> </NewDataSet> </diffgr:diffgram> </DataSet>';
        $registeredNumRaw = '<DataSet xmlns="http://tempuri.org/"> <xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet"> <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"> <xs:complexType> <xs:choice minOccurs="0" maxOccurs="unbounded"> <xs:element name="Table1"> <xs:complexType> <xs:sequence> <xs:element name="STATUS" type="xs:string" minOccurs="0"/> <xs:element name="COMP_NO" type="xs:string" minOccurs="0"/> </xs:sequence> </xs:complexType> </xs:element> </xs:choice> </xs:complexType> </xs:element> </xs:schema> <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"> <NewDataSet xmlns=""> <Table1 diffgr:id="Table11" msdata:rowOrder="0" diffgr:hasChanges="inserted"> <STATUS>SUCCESSFUL</STATUS> <COMP_NO>17092100581</COMP_NO> </Table1> </NewDataSet> </diffgr:diffgram> </DataSet>';
        
        $xmlRaw = $disconnectedCustomerXmlRaw;
		*/
        error_log("Web Service for NoCurrent Response is : " . $xmlRaw);
        
        $xmlRaw = str_replace(PHP_EOL, '', $xmlRaw);
        error_log("Web Service for NoCurrent Response is : " . $xmlRaw);

        $xmlData = simplexml_load_string($xmlRaw);
		
        $statusArray = $xmlData->xpath('//CMS_x0020_Compliants//Status');
        $status = null;
        foreach( $statusArray as $singleStatus ){
            $status = (string)$singleStatus;
            break;
        }

        $complaintNumArray = $xmlData->xpath('//CMS_x0020_Compliants//Comment');
        $complaintNum = null;
        foreach( $complaintNumArray as $singleComplaintNum ){
            $complaintNum = (string)$singleComplaintNum;
            break;
        }
        error_log ("Getting Data from for NoCurrent Response, Status:" . $status . ", ComplaintNum:" . $complaintNum);

		return array ($status, $complaintNum);
    } catch (Exception $e) {
        error_log("Exception while parsing The response from NoCurrent ws");
        error_log("Caught exception: " . $e->getMessage());
    }

    return $array();
}

// Transform hours like "1:45" into the total number of minutes, "105".
function hoursToMinutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 

function get24HrTimeInAMPM($tatIn24Hrs) {
	error_log ("time in UTC : " . time());
	error_log ("date in UTC : " . date('Y-m-d h-i-s'));
    if(function_exists('date_default_timezone_set')) {
        date_default_timezone_set("Asia/Kolkata");
    }
    error_log ("time in Asia : " . time());
    error_log ("date in Asia : " . date('Y-m-d h-i-s'));
    $mins = hoursToMinutes($tatIn24Hrs);
    error_log ("mins in tat " . $mins);
    $now = time();
    $time_after_tat_minutes = $now + ($mins * 60);
    error_log ("get24HrTimeInAMPM: " . date('h:i a', $time_after_tat_minutes));
    return date('h:i a', $time_after_tat_minutes);
    //return date("h:i a", strtotime($timeIn24Hrs));
}

function getDefaultNoCurrentUserMsg() {
    $defaultNoCurrentUserMsg = "Dear customer, There is some trouble in retrieving the information. Request you to try again. Send #NC <Your 9-digit X Power Account No>.";
    return $defaultNoCurrentUserMsg;
}
function convertNoCurrentWSResponseToUserMessage($wsComplaintStatus, $wsComplaintNum) {
    error_log("Creating User Message for, wsComplaintStatus : " . $wsComplaintStatus . ", $wsComplaintNum : " . $wsComplaintNum);
    $userNoCurrentMsg = getDefaultNoCurrentUserMsg();
    switch($wsComplaintStatus) {
        case "21":	
            $userNoCurrentMsg = "Dear Customer, The Account Number provided was Invalid. Request you to try again with valid account number. Send #NC <Your 9-digit X Power Account No>.";
            break;
		case "22":
			$userNoCurrentMsg = "Dear Customer you Entered Invalid Code. Request you to Enter Valid Code.";
			break;
		 case "23":
            $userNoCurrentMsg = "Dear Customer, Your ". $wsComplaintNum .". Rajdhani Power.";
            break;
		case "24":
            $userNoCurrentMsg = "Dear Customer, This account is  ". $wsComplaintNum .". Rajdhani Power.";
            break;
		case "25":
		case "28":
			$userNoCurrentMsg = "Dear Customer, The Account Number provided can not be found in our system. Please try after few hours.";
        case "26":
            $userNoCurrentMsg = "Dear Customer, The Account Number provided is current disabled in our system. Please try after few hours OR contact Customer Service.";
            break;
		case "27":
			$userNoCurrentMsg = "Dear Customer, The Account Number provided doesn't seem to be Rajdhani's customer. Please try with registered account number.";
			break;
        default:
            $userNoCurrentMsg = getDefaultNoCurrentUserMsg();
            break;
    }
    //$responseToUser = "Response-- wsRetStatus:" . $wsRetStatus . ", wsRMessage:" . $wsRMessage . ", wsTAT:" .  $wsTAT . ", wsComplaintNum:" .  $wsComplaintNum . ", wsComplaintStatus:" .  $wsComplaintStatus . ", wsHTLT:" .  $wsHTLT;
    error_log ("userNoCurrentMsg Created: " . $userNoCurrentMsg);
    return $userNoCurrentMsg;
}

function callRajdhaniPowerNoCurrentWSNGetUserResponse($CA_Number, $ContactNumber) {
    list($wsComplaintStatus, $wsComplaintNum) = callNoCurrentWS($CA_Number, $ContactNumber);
    return convertNoCurrentWSResponseToUserMessage($wsComplaintStatus, $wsComplaintNum);
}  


?>