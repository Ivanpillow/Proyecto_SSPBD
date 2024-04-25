<?php
	require_once "include/functions.php";
	require_once "include/db_tools.php";


    use PHPMailer\PHPMailer\PHPMailer;
	
	require 'include/PHPMailer2022/src/Exception.php';
	require 'include/PHPMailer2022/src/PHPMailer.php';
	require 'include/PHPMailer2022/src/SMTP.php';		
	require_once "extensiones/vendor/autoload.php";



if (Requesting("action")=="llenar_tabla"){
	$name_table = Requesting("name_table");
	$opcion = Requesting("opcion");
	
	$resultStatus 	= "ok";
	$resultText 		= "Correcto.";	
	$xmlRow = "";	

	$query2 = "SHOW COLUMNS FROM $name_table";
	$columnas = DatasetSQL($query2);
	while($row2 = mysqli_fetch_array($columnas)){
		$columna_id = $row2[0];
		break;
	}

	$num_columnas = $columnas->num_rows;

	$query1 = "SELECT * FROM $name_table";

	
	switch ($opcion) {
        case 1:
            $query1 .= " ORDER BY ".$columna_id." ASC";
            break;
        case 2:
            $query1 .= " ORDER BY ".$columna_id." DESC";
            break;
        case 3:
            $query1 .= " ORDER BY status ASC";
            break;
        case 4:
            $query1 .= " ORDER BY status DESC";
            break;
        case 5:
            $query1 .= " ORDER BY precio ASC";
            break;
        case 6:
            $query1 .= " ORDER BY precio DESC";
            break;
        case 7:
            $query1 .= " ORDER BY subtotal ASC";
            break;
        case 8:
            $query1 .= " ORDER BY subtotal DESC";
            break;
        case 9:
            $query1 .= " ORDER BY cantidad ASC";
            break;
        case 10:
            $query1 .= " ORDER BY cantidad DESC";
            break;
        case 11:
            $query1 .= " ORDER BY stock ASC";
            break;
        case 12:
            $query1 .= " ORDER BY stock DESC";
            break;
        case 11:
            $query1 .= " ORDER BY fecha ASC";
            break;
        case 12:
            $query1 .= " ORDER BY fecha DESC";
            break;
        default:
            
        break;
    }

	
	$datos_tabla = DatasetSQL($query1);

	while($row1 = mysqli_fetch_array($datos_tabla)){
		$cont = 0;
		$xmlRow .= "<tr>";
		while($cont < $num_columnas){
			$xmlRow .=  "<td>".$row1[$cont]."</td>";
			$cont++;
		}
		$xmlRow .=  "<td>
				<a href='#'><i class='fas fa-pen fa-lg'></i></a> &nbsp;
				<a href='#'><i class='fas fa-trash text-danger fa-lg'></i></a>
			</td>
		</tr>";
	}

	$result = array( 
		'tabla_a_rellenar' 		=> $xmlRow, 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}


if (Requesting("action")=="agregar_tabla"){ 	
	$name_table		= Requesting("name_table");
	$campo_id 		= Requesting("campo_id");	

	$resultStatus 	= "ok"; 
	$resultText 		= "Correcto.";
	 
	

	$query_check = "SHOW TABLES LIKE '".$name_table."'";
	$result = DatasetSQL($query_check);

	if($result->num_rows == 0){
		$query1 = "CREATE TABLE ".$name_table." (".$campo_id." INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (".$campo_id."))";
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