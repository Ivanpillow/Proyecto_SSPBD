<?php
	require_once "include/functions.php";
	require_once "include/db_tools.php";


    use PHPMailer\PHPMailer\PHPMailer;
	
	require 'include/PHPMailer2022/src/Exception.php';
	require 'include/PHPMailer2022/src/PHPMailer.php';
	require 'include/PHPMailer2022/src/SMTP.php';		
	require_once "extensiones/vendor/autoload.php";

	
function XML_Header() {
    return '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
}




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
		$id_columna = $row1[0];
		$cont = 0;
		$xmlRow .= "<tr>";
		while($cont < $num_columnas){
			$xmlRow .=  "<td>".$row1[$cont]."</td>";
			$cont++;
		}
		$xmlRow .=  "<td>
				<a type='button' data-bs-toggle='modal' data-bs-target='#modalEditar' data-id='$id_columna' onclick='llenar_form_tabla(\"$name_table\", $id_columna)'><i class='fas fa-pen fa-lg'></i></a> &nbsp;
				<a type='button' data-bs-toggle='modal' data-bs-target='#modalEliminar' data-id='$id_columna'><i class='fas fa-trash text-danger fa-lg'></i></a>
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


//ESTO FALTAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA

if(Requesting("action")=="modificar_tabla"){
	$resultStatus 	= "ok"; 
	$resultText 		= "Correcto.";

	//Aqui tengo que reestructurar la tabla que se muestra, para poner iconos de eliminar y editar
	


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


//ESTO FALTAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
if(Requesting("action") == "modificar_campo"){
	
	$resultStatus = "ok"; 
    $resultText = "Correcto.";



	$result = array( 
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}


if(Requesting("action")=="agregar_registro"){
	$resultStatus = "ok"; 
    $resultText = "Correcto.";
	 //Obtener la tabla
	$tabla = Requesting("tabla");

	//Inicializar un array para almacenar los datos del formulario
	$datos_formulario = array();

	//Iterar sobre $_POST para obtener los datos del formulario
	foreach ($_POST as $nombre_campo => $valor_campo) {
		//Si el nombre del campo no es "tabla" (ya lo tenemos), lo añadimos al array de datos del formulario
		if ($nombre_campo !== "tabla" AND $nombre_campo !== "action") {
			// Guardar el nombre del campo y su valor en el array
			$datos_formulario[$nombre_campo] = $valor_campo;
		}
	}

	//Preparar la consulta para insertar el nuevo registro
    $columnas = implode(", ", array_keys($datos_formulario));
    $valores = "'" . implode("', '", array_values($datos_formulario)) . "'";

    $query1 = "INSERT INTO $tabla ($columnas) VALUES ($valores)";

    //Ejecutar la consulta para insertar el nuevo registro
    if(ExecuteSQL($query1)){
        
        $resultStatus = "ok"; 
        $resultText = "Registro insertado correctamente.";
    } else{
        
        $resultStatus = "error"; 
        $resultText = "Error al insertar el registro.";
    }



	$result = array( 
		'tabla'				=> $tabla,
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}


if(Requesting("action")=="modificar_registro"){
	$tabla = Requesting("tabla");
	$nombre_id = Requesting("editar_nombre_id");
	$id_registro = Requesting("editar_id_registro");

	// echo "Tabla: ".$tabla;
	// echo "Nombre id: ".$nombre_id;
	// echo "Id registro: ".$id_registro;

	$resultStatus = "ok"; 
    $resultText = "Correcto.";


	// Inicializar un array para almacenar las partes de la sentencia UPDATE
    $update_values = array();

    // Iterar sobre $_POST para obtener los datos del formulario
    foreach ($_POST as $nombre_campo => $valor_campo) {
        // Si el nombre del campo no es "tabla", "action", "nombre_id" o "id_registro", lo añadimos a la sentencia UPDATE
        if ($nombre_campo !== "tabla" && $nombre_campo !== "action" && $nombre_campo !== "editar_nombre_id" && $nombre_campo !== "editar_id_registro"){
            // Construir parte de la sentencia UPDATE
            $update_values[] = "`$nombre_campo` = '$valor_campo'";
        }
    }

	// Construir la sentencia UPDATE
	$query1 = "UPDATE `$tabla` SET " . implode(", ", $update_values) . " WHERE `$nombre_id` = '$id_registro'";
	// echo $query1;

	if(ExecuteSQL($query1)){
		$resultStatus = "ok"; 
        $resultText = "Registro modificado correctamente.";
	} else {
		$resultStatus = "error"; 
        $resultText = "Error al modificar el registro.";
    }


	$result = array( 
		'tabla'				=> $tabla,
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}



if(Requesting("action")=="eliminar_registro"){
	$tabla = Requesting("tabla");
	$nombre_id_registro = Requesting("nombre_id_registro"); 
	$id_registro = Requesting("id_registro");

	$resultStatus = "ok"; 
    $resultText = "Correcto.";


	$query1 = "DELETE FROM $tabla WHERE $nombre_id_registro = $id_registro";
	// echo $query1;

	if(ExecuteSQL($query1)){
		$resultStatus = "ok"; 
        $resultText = "Registro eliminado correctamente.";
	} else{
		$resultStatus = "error"; 
        $resultText = "Error al eliminar el registro.";
	}



	$result = array( 
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}

if(Requesting("action")=="llenar_form_tabla"){
	$nombre_tabla = Requesting("nombre_tabla");
	$id_columna = Requesting("id_columna"); 
	$nombre_campo_id = '';

	$resultStatus = "ok"; 
    $resultText = "Correcto.";


	//GUARDAR CAMPOS EN UN ARRAY

	$query1 = "SHOW COLUMNS FROM $nombre_tabla";
	$columnas = DatasetSQL($query1);
	$nombres_campos = array();
	while($row1 = mysqli_fetch_array($columnas)){
		$nombre_campo = $row1['Field'];
    	$nombres_campos[] = $nombre_campo;

		if(empty($nombre_campo_id)){
			$nombre_campo_id = $nombre_campo; //Guarda el nombre del campo del id debido a que es el primer campo en cada tabla
		}
	}


	//GUARDAR REGISTROS EN UN ARRAY
    $registros_campos = array();
	$query2 = "SELECT * FROM $nombre_tabla WHERE $nombre_campo_id = $id_columna";
	$registros = DatasetSQL($query2);

	while($row2 = mysqli_fetch_array($registros)){
		$registro = array();
		foreach($nombres_campos as $campo) {
			$registros_campos[] = $row2[$campo]; // Guardar los datos de cada campo en el registro actual
		}
	}

	$query3 = "SELECT COUNT(*) AS cuantos_campos FROM information_schema.columns WHERE table_schema = 'punto_venta_tenis' AND table_name = '$nombre_tabla'";
	// echo $query3;
	$cuantos_campos = GetValueSQL($query3, 'cuantos_campos');

	$result = array( 
		'cuantos_campos' => $cuantos_campos,
		'nombres_campos' => json_encode($nombres_campos),
		'registros_campos' => json_encode($registros_campos),
		'result' 			=> $resultStatus, 
		'result_text' 		=> $resultText, 
	);	 

	XML_Envelope($result);     
	exit;
}


?>