
<?php

set_error_handler (
    function($errno, $errstr, $errfile, $errline) {
        error_log ("Some Error Occured");
        error_log ("errno:" . $errno);
        error_log ("errstr:" . $errstr);
        error_log ("errfile:" . $errfile);
        error_log ("errline:" . $errline);
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
    }
);

function callGetOutageWS($inCA_Number) {

    error_log ("Input- CA_Number:" . $inCA_Number);

    $pieces = array("","","","","","");
    try {
        //Removing non alphanumeric chars
        $CA_Number = preg_replace("/(\W)+/", "", $inCA_Number);
        error_log ("Removing non alphanumeric chars Input- CA_Number:" . $CA_Number);

        //Removing non numeric chars
        $CA_Number = preg_replace("/[^0-9]/", "", $inCA_Number);
        error_log ("Removing non numeric chars Input- CA_Number:" . $CA_Number);

        $key = "SomeKey";

        $webServiceToConnect = "http://1.2.3.4/Service1.asmx/GetComplaintDetailsCA?key=".$key."&CANo=".$CA_Number;
        error_log("Web Service to Call for GetOutage is : " . $webServiceToConnect);
        $xmlRaw = file_get_contents($webServiceToConnect);
        /* *
		$outageXmlRaw = '<DataSet xmlns="http://tempuri.org/"> <xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet"> <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"> <xs:complexType> <xs:choice minOccurs="0" maxOccurs="unbounded"> <xs:element name="Table1"> <xs:complexType> <xs:sequence> <xs:element name="COMP_NO" type="xs:string" minOccurs="0"/> <xs:element name="CA_NO" type="xs:decimal" minOccurs="0"/> <xs:element name="OPENING_TIME" type="xs:string" minOccurs="0"/> <xs:element name="DATE_CLOSED" type="xs:string" minOccurs="0"/> <xs:element name="STATUS" type="xs:string" minOccurs="0"/> <xs:element name="FAULT_TYPE" type="xs:string" minOccurs="0"/> </xs:sequence> </xs:complexType> </xs:element> <xs:element name="Table2"> <xs:complexType> <xs:sequence> <xs:element name="OUTAGE_INFO" type="xs:string" minOccurs="0"/> </xs:sequence> </xs:complexType> </xs:element> </xs:choice> </xs:complexType> </xs:element> </xs:schema> <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"> <NewDataSet xmlns=""> <Table1 diffgr:id="Table11" msdata:rowOrder="0"> <COMP_NO>17060601264</COMP_NO> <CA_NO>150673412</CA_NO> <OPENING_TIME>06-06-2017 02:36:38</OPENING_TIME> <DATE_CLOSED>PENDING</DATE_CLOSED> <STATUS>ASSIGNED</STATUS> <FAULT_TYPE>NO POWER IN AREA</FAULT_TYPE> </Table1> <Table1 diffgr:id="Table12" msdata:rowOrder="1"> <COMP_NO>17060600465</COMP_NO> <CA_NO>150673412</CA_NO> <OPENING_TIME>06-06-2017 00:46:11</OPENING_TIME> <DATE_CLOSED>06-06-2017 02:34:00</DATE_CLOSED> <STATUS>CLOSED</STATUS> <FAULT_TYPE>NO CURRENT/POWER</FAULT_TYPE> </Table1> <Table1 diffgr:id="Table13" msdata:rowOrder="2"> <COMP_NO>17043001189</COMP_NO> <CA_NO>150673412</CA_NO> <OPENING_TIME>30-04-2017 21:07:43</OPENING_TIME> <DATE_CLOSED>30-04-2017 21:14:00</DATE_CLOSED> <STATUS>CLOSED</STATUS> <FAULT_TYPE>NO POWER IN AREA</FAULT_TYPE> </Table1> <Table1 diffgr:id="Table14" msdata:rowOrder="3"> <COMP_NO>17042900385</COMP_NO> <CA_NO>150673412</CA_NO> <OPENING_TIME>29-04-2017 10:32:24</OPENING_TIME> <DATE_CLOSED>29-04-2017 11:21:00</DATE_CLOSED> <STATUS>CLOSED</STATUS> <FAULT_TYPE>NO POWER IN AREA</FAULT_TYPE> </Table1> <Table1 diffgr:id="Table15" msdata:rowOrder="4"> <COMP_NO>17042100072</COMP_NO> <CA_NO>150673412</CA_NO> <OPENING_TIME>21-04-2017 00:15:30</OPENING_TIME> <DATE_CLOSED>21-04-2017 01:59:00</DATE_CLOSED> <STATUS>CLOSED</STATUS> <FAULT_TYPE>NO POWER IN AREA</FAULT_TYPE> </Table1> <Table2 diffgr:id="Table21" msdata:rowOrder="0" diffgr:hasChanges="inserted"> <OUTAGE_INFO> EMERGENCY SHUTDOWN in your area. Expected duration of restoration: 0000 hrs 30 mins </OUTAGE_INFO> </Table2> </NewDataSet> </diffgr:diffgram> </DataSet>';
        $xmlRaw = $outageXmlRaw;
		* */
        error_log("Web Service for GetOutage Response is : " . $xmlRaw);
        $xmlData = simplexml_load_string($xmlRaw);
		
        $outageInfoArray = $xmlData->xpath('//Table2//OUTAGE_INFO');
        $outageInfo = null;
        foreach( $outageInfoArray as $singleOutageInfo ){
            $outageInfo = (string)$singleOutageInfo;
            break;
        }
        
        error_log ("Getting Data from GetOutage Response, outageInfo:" . $outageInfo);

		return $outageInfo;
    } catch (Exception $e) {
        error_log("Exception while parsing The response from GetOutage ws");
        error_log("Caught exception: " . $e->getMessage());
    }

    return null;
}

function getDefaultOutageUserMsg() {
    $defaultOutageUserMsg = "";
    return $defaultOutageUserMsg;
}
function convertOutageWSResponseToUserMessage($wsOutageInfo) {
    error_log("Creating User Message for, wsOutageInfo : " . $wsOutageInfo);
	$userOtuageMsg = getDefaultOutageUserMsg();
    if(!empty($wsOutageInfo)) {
        $userOtuageMsg = $wsOutageInfo;
    }
    error_log ("userOtuageMsg Created: " . $userOtuageMsg);
    return $userOtuageMsg;
}

function callRajdhaniPowerGetOutageWSNGetUserResponse($CA_Number) {
    $wsOutageInfo = callGetOutageWS($CA_Number);
    return convertOutageWSResponseToUserMessage($wsOutageInfo);
}  

?>