<?php 
	//require('openpay/openpay.php'); //librerias openpay
	require_once "db_params.php";
	//https://sandbox-api.openpay.mx/v1/
	//$openpay = Openpay::getInstance('mo1fcupzq8mudsl3rucp', 'sk_2d0142a16c4d41f39a47c1fa7ff15122'); //bruja SANDBOX
	//$pmode = Openpay::setProductionMode(true);
	//Openpay::setSandboxMode(false);		

error_reporting(E_ALL ^ E_DEPRECATED);
//include_once 'timezone.php';

function Is_Server(){	
	global $APP_Type;
	return $APP_Type == 'SERVER';
}

function Is_Registered(){	
	global $APP_Type;
	if (Is_Server()) return true;
	$id_service =GetValueSQL("select id_service from conf_site","id_service");
	return $id_service!=0;
}	

function DatasetSQL_con($sSQL,&$dbConx){
	global $DB_Host, $DB_User, $DB_Password, $DB_Database, $DB_Init_Params, $DB_Host;
	$rsCursor=null;
  	
	if(! $DB_Init_Params) 
		Init_DBParams();
 	if (!$dbConx)
	    $dbConx = mysqli_connect($DB_Host,$DB_User,$DB_Password,$DB_Database);
 	if(!$dbConx){
 		return null;
 	}
  	//mysqli_select_db( $DB_Database,);
  	$dbConx->set_charset('utf8');
  	$rsCursor=mysqli_query($dbConx,$sSQL);
  	return $rsCursor;
}
function DatasetSQL($sSQL){
   $dbConx = null;
   $ret = DatasetSQL_con($sSQL,$dbConx);
   mysqli_close($dbConx);
   return $ret;
}
function GetValueSQL_con(&$dbConx,$sSQL,$sFieldname){
	$rsTemp=null;
	
	$rsTemp=DatasetSQL_con($sSQL,$dbConx);
	if ($rsTemp!=null){
		$row = mysqli_fetch_array($rsTemp);
		$Value = $row[$sFieldname];
		mysqli_free_result($rsTemp);		
		
		return $Value;
	}
	else 
		return null;
}
function GetValueSQL($sSQL,$sFieldname){
	$rsTemp=null;
	$dbConx = null;
	$rsTemp=DatasetSQL_con($sSQL,$dbConx);
	if ($rsTemp!=null){
		$row = mysqli_fetch_array($rsTemp);
		$Value = $row[$sFieldname];
		mysqli_free_result($rsTemp);		
		mysqli_close($dbConx);
		return $Value;
	}
	else {
		//echo "erroe:";
		return null;
	}
}

function ExecuteSQL($sSQL){
        $dbConx = null;
 	try {
 		$rsTemp=DatasetSQL_con($sSQL,$dbConx);
 		mysqli_close($dbConx);
 		return true;	
 	} catch (Exception $e) {
 		 echo $e->getMessage();
 		return false;
 	}
}

function ExecuteSQL_returnID($sSQL){
        $dbConx = null;
 	try {
 		$rsTemp=DatasetSQL_con($sSQL,$dbConx);
 		$aidi=mysqli_insert_id($dbConx);
 		mysqli_close($dbConx);
		return $aidi;
 	} catch (Exception $e) {
 		 echo $e->getMessage();
 		return false;
 	}
}


function ComboSQL($sSQL,$keyfield,$listfield,$value){
  $rsRows = DatasetSQL($sSQL);
  $sRet ="";
  while ( $row = mysqli_fetch_array($rsRows) ){
    $sel =  ($row[$keyfield]==$value)?" selected": "";    
    $sRet = $sRet . "<option value='". $row[$keyfield] ."' $sel >" . $row[$listfield] ."</option>";    
  }
  return $sRet;
}

function Requesting($sParamName){
	$Invalid_words = array("'","%","^","`","~","´","¨","$","¬","delete ","create ","execute ","select ","insert ");
	$valid_words = array("\"","%","^","`","~","´","¨","$"," "," "," "," "," "," ");
	//$_REQUEST["fname"];
	if (!isset($_REQUEST[$sParamName]) )
			return '';
	$sRequest = $_REQUEST[$sParamName];
	$iCount = 0;
	$i =0;
	foreach ($Invalid_words as $word) {
		 $sRequest = str_replace($word, $valid_words[$i], $sRequest,$iCount);
	}
	return $sRequest;
}

function Request($sParamName){
	$Invalid_words = array("'","delete ","create ","execute ","select ","insert ");
	$valid_words = array("\'"," "," "," "," "," ");
	//$_REQUEST["fname"];
	if (!isset($_REQUEST[$sParamName]) )
			return '';
	$sRequest = $_REQUEST[$sParamName];
	$iCount = 0;
	$i =0;
	foreach ($Invalid_words as $word) {
		 $sRequest = str_replace($word, $valid_words[$i], $sRequest,$iCount);
	}
	return $sRequest;
}
function CDATA($texto){
	return "<![CDATA[" . $texto . "]]>";
}

function BoolText($Condicion, $True,$False ){
	return ($Condicion)? $True : $False;
}

function dump_chunk($chunk){
  printf("%x\r\n%s\r\n", strlen($chunk), $chunk);
  flush();
}
function Decode_Zip($sBuffer){		
       $s = "";
 	for($i=0;$i<(strlen($sBuffer) / 2);$i++){
		$s = $s . chr(hexdec(substr($sBuffer, $i*2,2)));
	}
	return gzuncompress($s);
}
function Save_EncodeXML_Zip($sBuffer,$sFilename){	
	$sPath = /*Is_Server() ? '../temp/' :*/ '../temp/';
 	if ($sFilename=="") 
		$sFilename = Datetime_ID().".zip";
	$sFilename = $sPath.$sFilename;
    $s = "";
 	for($i=0;$i<(strlen($sBuffer) / 2);$i++){
		$s = $s . chr(hexdec(substr($sBuffer, $i*2,2)));
	}
	$sxml_content = gzuncompress($s);
 	$fh = fopen($sFilename, 'w') or die("can't open file");
 	fwrite($fh, $sxml_content);
	fclose($fh);
 	return $sFilename; 	
}
function Save_EncodeXML($sBuffer,$sFilename){	
 	$sPath = Is_Server() ? '../temp/' : '../temp/';
 	return Save_EncodeXML_Dest($sBuffer,$sFilename,$sPath);
}

function Save_EncodeXML_Dest($sBuffer,$sFilename,$sDest){
	$sResult = "";
	for($i=0;$i<(strlen($sBuffer) / 2);$i++){
		$sResult = $sResult . chr(hexdec(substr($sBuffer, $i*2,2)));
	}

	if ($sFilename=="") 
		$sFilename = Datetime_ID().".xml";

	
	$sFilename = $sDest.$sFilename;
	//$sFilename =  '../temp/' .$sFilename;
	$fh = fopen($sFilename, 'w') or die("can't open file");
	fwrite($fh, $sResult);
	fclose($fh);
 	return $sFilename;
}
function Encode_Buffer($sBuffer){
	$sResult = "";
	for($i=0;$i<(strlen($sBuffer));$i++){		
		$sResult = $sResult . 
				((ord($sBuffer[$i]))<16?'0':'').
				dechex(ord($sBuffer[$i]));
	}
 	return $sResult;
}

function Datetime_ID(){
	$fecha = new DateTime();
	return $fecha->format('Ymd_His_u');
}


function Is_Logged(){
	global $APP_Name;
	return isset($_SESSION[$APP_Name.'id_user']);
}

function Session_ID_User(){
	global $APP_Name;
	if (isset($_SESSION[$APP_Name.'id_user']))
		return $_SESSION[$APP_Name.'id_user'];
	else
		return 0;
}

function User_Info($campo){
	return GetValueSQL("select ".$campo." from administradores where id=".Session_ID_User(),$campo);
}


function Close_Session(){
	session_destroy();	
	//header( 'Location: login.php' ) ;
}

function Init_Session($ID_User){
	global $APP_Name;
	session_destroy();	
	session_start();
	
	$email = GetValueSQL("select email from users where id=$ID_User","email");
	$_SESSION[$APP_Name.'id_user'] = $ID_User;				
}
function Go_Home(){
	header('Location: admin.php');
}
function Go_login(){
	header( 'Location: login.php' ) ;	
}
function Verify_Logged(){
	if (!Is_Logged())		
		Go_login();
	else
		if (Conf_Status()==0)
			header( 'Location: configuracion.php' ) ;	
}


function SinComillas($sTexto){
	$sRet = str_replace('"','&quot;',$sTexto);
	$sRet = str_replace('<','&lt;',$sRet);
	$sRet = str_replace('>','&gt;',$sRet);
	$sRet = str_replace('&','&amp;',$sRet);
	$sRet = str_replace("'",'&com;',$sRet);
	//$sRet = str_replace("'",'&apos;',$sRet);
	return $sRet;
}
function ConComillas($sTexto,$ID_Service){
	$sRet = str_replace('%%ID_SERVICE%%',$ID_Service,$sTexto); 
	$sRet = str_replace('&quot;','"',$sRet);
	$sRet = str_replace('&lt;','<',$sRet);
	$sRet = str_replace('&gt;','>',$sRet);
	$sRet = str_replace('&amp;','&',$sRet);
	$sRet = str_replace('&com;',"\'",$sRet);
	return $sRet;
}

function Addlog($sLog){
  $date = new DateTime();
  $sCommand = "echo " . $date->format('Y-m-d H:i:s.u') ." ".$sLog." >>server.log";
  shell_exec ($sCommand);  
}
//*********************** ENVIO DE MAIL USANDO PHP MAILER ***********************************
function Send_Mail($to,$name, $subject, $message){
    // ---- ESTE SE USABA CUANDO NO SE NECESITA SMTPAuth  return mail($to,$subject,$message,'Content-type: text/html; charset=UTF-8');
	
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = "";
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Username = "";
    $mail->Password = "";
    $mail->setFrom('EMAIL@MAIL', 'NOMBRE');
    $mail->addAddress($to, $name);
    $mail->Subject = $subject;
    
    $sMsg = $message; //file_get_contents('mail1.html') .$message .file_get_contents('mail2.html');
    
    $mail->msgHTML($sMsg, dirname(__FILE__));
    $mail->AltBody = $message;
    //echo $sMsg;
    //exit;
    //$mail->addAttachment('images/logo_mail.jpg');

    //send the message, check for errors
    if (!$mail->send()) {
	//return false;
		echo "Mailer Error: " . $mail->ErrorInfo;
		return; false;
    } else {
        return  true;
    }
}
//********************************************************************************************
?>