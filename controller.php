<?php
	require_once "include/functions.php";
	require_once "include/db_tools.php";


    use PHPMailer\PHPMailer\PHPMailer;
	
	require 'include/PHPMailer2022/src/Exception.php';
	require 'include/PHPMailer2022/src/PHPMailer.php';
	require 'include/PHPMailer2022/src/SMTP.php';		
	require_once "extensiones/vendor/autoload.php";



if (Requesting("action")=="agregar_tabla"){ 	
	$nameBD		= Requesting("name_table");
	$campo_id 		= Requesting("campo_id");	

	$resultStatus 	= "ok"; 
	$resultText 		= "Correcto.";
	 
	

	$query_check = "SHOW TABLES LIKE '".$nameBD."'";
	$result = DatasetSQL($query_check);

	if($result->num_rows == 0){
		$query1 = "CREATE TABLE ".$nameBD." (".$campo_id." INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (".$campo_id."))";
		//echo $query1;
		$result = ExecuteSQL($query1);
		
		$resultText = "Tabla creada con éxito";
	} else{
		$resultText = "La tabla ya existe";
		$resultStatus = "error";
	}
	
	 	
	$result = array( 
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
 
}

if(Requesting("action")=="eliminar_tabla"){
	$nombre_tabla = Requesting("nombre_tabla");

	$resultStatus 	= "ok"; 
	$resultText 		= "Correcto.";


	$query_check = "SHOW TABLES LIKE '".$nombre_tabla."'";
	$result = DatasetSQL($query_check);

	if($result->num_rows > 0){
		$query1 = "DROP TABLE ".$nombre_tabla;
		//echo $query1;
		$result = ExecuteSQL($query1);
		$resultText = "Tabla eliminada correctamente.";
	} else{
		$resultStatus 	= "error"; 
		$resultText 		= "Ocurrió un error. Intenta de nuevo.";
	}

	$result = array( 
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}

if(Requesting("action")=="agregar_campo"){
	$name_table = Requesting("name_table");
	$columna = Requesting("agregar_campo_columna");
	$tipo_dato = Requesting("agregar_campo_tipo_dato");
	$longitud	= Requesting("agregar_campo_longitud");
	
	$resultStatus 	= "ok"; 
	$resultText 		= "Correcto.";

	if($longitud == null){
		$longitud = 255;
	} 


	$query_check = "SHOW TABLES LIKE '".$name_table."'";
	$result = DatasetSQL($query_check);

	if($result->num_rows > 0){
		$query1 = "SHOW COLUMNS FROM ".$name_table." WHERE Field = '".$columna."'";
		//echo $query1;
		$result1 = DatasetSQL($query1);
		if($result1->num_rows == 0){
			if($tipo_dato == "VARCHAR"){
				$query2 = "ALTER TABLE ".$name_table." ADD ".$columna." ".$tipo_dato."(".$longitud.")";
			} else{
				$query2 = "ALTER TABLE ".$name_table." ADD ".$columna." ".$tipo_dato;
			}
			

			$result2 = ExecuteSQL($query2);
			$resultStatus 	= "ok"; 
			$resultText 		= "Campo añadido correctamente.";
		} else{
			
			$resultStatus 	= "error"; 
			$resultText 		= "El campo ya existe.";
		}
	} else{
		$resultStatus 	= "error"; 
		$resultText 		= "La tabla no existe.";
	}

	$result = array( 
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}

if(Requesting("action") == "eliminar_campo"){
	$name_table = Requesting("name_table");
	$columna = Requesting("eliminar_campo_columna");
	
	$resultStatus 	= "ok"; 
	$resultText 		= "Correcto.";

	$query_check = "SHOW TABLES LIKE '".$name_table."'";
	$result = DatasetSQL($query_check);

	if($result->num_rows > 0){
		$query1 = "SHOW COLUMNS FROM ".$name_table." WHERE Field = '".$columna."'";
		// echo $query1;
		$result = DatasetSQL($query1);
		if($result->num_rows > 0){
			
			$query2 = "ALTER TABLE ".$name_table." DROP COLUMN ".$columna;
			
			//echo $query2;
			$result = ExecuteSQL($query2);
			
			$resultStatus 	= "ok"; 
			$resultText 		= "Se ha eliminado el campo.";
		} else{
			$resultStatus 	= "error"; 
			$resultText 		= "El campo no existe.";
		}
	} else{
		$resultStatus 	= "error"; 
		$resultText 		= "La tabla no existe.";
	}

	$result = array( 
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}


?>