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
        case 13:
            $query1 .= " ORDER BY fecha ASC";
            break;
        case 14:
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


if(Requesting("action")=="llenar_tabla_ventas"){
	$select_ventas = Requesting("select_ventas");
	// echo $select_ventas;

	$resultStatus = "ok"; 
    $resultText = "Correcto.";

	$xmlRow = "";

	if($select_ventas != 1){
		$primer_dia_mes = date('Y-m-01', strtotime($select_ventas));
   	 	$ultimo_dia_mes = date('Y-m-t', strtotime($select_ventas));
	}

	if($select_ventas == 1){
		$query1 = "SELECT COUNT(*) AS existe FROM ventas 
		INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente
		INNER JOIN empleados ON ventas.id_empleado = empleados.id_empleado";
	} else{
		$query1 = "SELECT COUNT(*) AS existe FROM ventas 
		INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente
		INNER JOIN empleados ON ventas.id_empleado = empleados.id_empleado
		WHERE ventas.fecha BETWEEN '$primer_dia_mes' AND '$ultimo_dia_mes'";
		
		
		// echo $query1;
	}
	
	// echo $query1;
	$existe = GetValueSQL($query1, 'existe');

	$total_vendido = 0;
	
	if($existe > 0){

		if($select_ventas == 1){
			$query4 = "SELECT SUM(ventas.total_venta) AS total_vendido FROM ventas";
			$total_vendido = GetValueSQL($query4, 'total_vendido');

			$query2 = "SELECT * FROM ventas 
			INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente
			INNER JOIN empleados ON ventas.id_empleado = empleados.id_empleado
			ORDER BY fecha DESC";
			
		} else{
			$query4 = "SELECT SUM(ventas.total_venta) AS total_vendido FROM ventas
			WHERE ventas.fecha BETWEEN '$primer_dia_mes' AND '$ultimo_dia_mes'";
			$total_vendido = GetValueSQL($query4, 'total_vendido');
			// echo $query4;

			
			$query2 = "SELECT * FROM ventas 
			INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente
			INNER JOIN empleados ON ventas.id_empleado = empleados.id_empleado
			WHERE ventas.fecha BETWEEN '$primer_dia_mes' AND '$ultimo_dia_mes'
			ORDER BY fecha DESC";
			// echo $query2;
		}
		
		// echo $query2;
		$result2 = DatasetSQL($query2);

		while($row2 = mysqli_fetch_array($result2)){
			$id_venta = $row2['id_venta'];
			$nombre_cliente = $row2['nombre_cliente'];
			$nombre_empleado = $row2['nombre_empleado'];
			$fecha = $row2['fecha'];
			$total_venta = $row2['total_venta'];

			$xmlRow .=  "<tr>";
			$xmlRow .=  "<td>$id_venta</td>";
			$xmlRow .=  "<td>$nombre_cliente</td>";
			$xmlRow .=  "<td>$nombre_empleado</td>";
			$xmlRow .=  "<td>$fecha</td>";
			$xmlRow .=  "<td>$".number_format($total_venta, 2)."</td>";
			$xmlRow .=  "<td><a type='button' href='' onclick='ver_detalles_venta(".$id_venta.", event)'>Ver detalles</a></td>";
			$xmlRow .=  "</tr>";
			
			
			$xmlRow .= "<tr class='detalles_venta' id='detalles_venta_tabla_$id_venta;'>";
			$xmlRow .= "<td colspan='6'>";
			$xmlRow .= "<ul>";
						
						
						$query3 ="SELECT * FROM ventas
						INNER JOIN detalles_ventas ON ventas.id_venta = detalles_ventas.id_venta
						INNER JOIN productos ON detalles_ventas.id_producto = productos.id_producto
						INNER JOIN tallas ON productos.id_talla = tallas.id_talla
						WHERE ventas.id_venta = $id_venta";
						$detalles_ventas = DatasetSQL($query3);
		
						while($row3 = mysqli_fetch_array($detalles_ventas)){
							$nombre_producto = $row3['nombre_producto'];
							$talla = $row3['talla'];
							$cantidad = $row3['cantidad'];
							$precio_unitario = $row3['precio_unitario'];
							$subtotal = $row3['subtotal'];
		
							$xmlRow .= "<li><strong>Producto: </strong>$nombre_producto</li>";
							$xmlRow .=  "<strong>Talla: </strong>$talla<br>";
							$xmlRow .=  "<strong>Cantidad: </strong>$cantidad<br>";
							$xmlRow .=  "<strong>Precio Unitario: </strong>$".number_format($precio_unitario, 2)."<br>";
							$xmlRow .=  "<strong>Subtotal: </strong>$".number_format($subtotal, 2)."<br>";
							$xmlRow .=  "<br>";
						}
						
					$xmlRow .=  "</ul>";
				$xmlRow .=  "</td>";
			$xmlRow .=  "</tr>";

		
		}
	}

	$total_vendido = number_format($total_vendido, 2);


	$result = array( 
		'total_vendido'			=> $total_vendido,
		'tabla_ventas' 			=> $xmlRow, 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;		
}

if(Requesting("action") == "llenar_select_clientes"){
	$resultStatus = "ok";
	$resultText = "Correcto.";

	$xmlRow = '<option value="0">Selecciona un cliente...</option>';

	$query1 = "SELECT COUNT(*) AS cuantos FROM clientes WHERE status = 1";
	$cuantos_clientes = GetValueSQL($query1, 'cuantos');

	if($cuantos_clientes > 0){
		$query2 = "SELECT * FROM clientes WHERE status = 1";
        $clientes = DatasetSQL($query2);

		while($row2 = mysqli_fetch_array($clientes)){
			$id_cliente = $row2['id_cliente'];
            $nombre_cliente = $row2['nombre_cliente'];
			$xmlRow .=  "<option value='$id_cliente'>$nombre_cliente</option>";
		}
	}



	$result = array( 
		'select_clientes'		=> $xmlRow, 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}

if(Requesting("action") == "llenar_select_empleados"){
	$resultStatus = "ok";
	$resultText = "Correcto.";

	$xmlRow = '<option value="0">Selecciona un empleado...</option>';

	$query1 = "SELECT COUNT(*) AS cuantos FROM empleados WHERE status = 1";
	$cuantos = GetValueSQL($query1, 'cuantos');

	if($cuantos > 0){
		$query2 = "SELECT * FROM empleados WHERE status = 1";
        $clientes = DatasetSQL($query2);

		while($row2 = mysqli_fetch_array($clientes)){
			$id_empleado = $row2['id_empleado'];
            $nombre_empleado = $row2['nombre_empleado'];
			$xmlRow .=  "<option value='$id_empleado'>$nombre_empleado</option>";
		}
	}



	$result = array( 
		'select_empleados'		=> $xmlRow, 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}



if(Requesting("action") == "llenar_select_productos"){
	$resultStatus = "ok";
	$resultText = "Correcto.";

	$xmlRow = '<option value="0">Selecciona un producto...</option>';

	$query1 = "SELECT COUNT(*) AS cuantos FROM productos WHERE status = 1";
	$cuantos = GetValueSQL($query1, 'cuantos');

	if($cuantos > 0){
		$query2 = "SELECT * FROM productos WHERE status = 1 ORDER BY id_producto";
        $productos = DatasetSQL($query2);

		while($row2 = mysqli_fetch_array($productos)){
			$id_producto = $row2['id_producto'];
            $nombre_producto = $row2['nombre_producto'];
			$xmlRow .=  "<option value='$id_producto'>$nombre_producto</option>";
		}
	}



	$result = array( 
		'select_productos'		=> $xmlRow, 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}



if(Requesting("action") == "llenar_select_tallas"){
    $id_producto = Requesting("id_producto");

    $resultStatus = "ok";
    $resultText = "Correcto.";

    $xmlRow = '<option value="0">Selecciona una talla...</option>';

    if($id_producto != 0){
        $query1 = "SELECT COUNT(*) AS cuantos FROM producto_talla WHERE id_producto = $id_producto";
        $cuantos = GetValueSQL($query1, 'cuantos');

        if($cuantos > 0){
            $query2 = "SELECT producto_talla.*, tallas.talla, tallas.status FROM producto_talla
            INNER JOIN tallas ON producto_talla.id_talla = tallas.id_talla
            WHERE id_producto = $id_producto AND status = 1 ORDER BY id_talla";
            $productos = DatasetSQL($query2);

            while($row2 = mysqli_fetch_array($productos)){
                $id_producto_talla = $row2['id_producto_talla'];
                $talla = $row2['talla'];
                $xmlRow .=  "<option value='$id_producto_talla'>$talla</option>";
            }
        }

		$query3 = "SELECT precio FROM productos WHERE id_producto = $id_producto";
		$precio_unitario = GetValueSQL($query3, 'precio');

		$precio_unitario = "$".number_format($precio_unitario, 2);
    } else{
		$precio_unitario = "$".number_format(0, 2);
	}

	

    $result = array( 
		'id_producto'			=> $id_producto,
		'precio_unitario'		=> $precio_unitario,
        'select_tallas'     	=> $xmlRow, 
        'result'                => $resultStatus, 
        'result_text'           => $resultText
    );      
    XML_Envelope($result);     
    exit;   
}




if(Requesting("action") == "crear_venta"){
	$resultStatus = "ok";
	$resultText = "Correcto.";

	$id_cliente = Requesting("id_cliente");
	$id_empleado = Requesting("id_empleado");
	$fecha_hoy = date("Y-m-d");

	// echo "ID CLiente: ".$id_cliente;
	// echo "ID Empleado: ".$id_empleado;
	// echo "Fecha Hoy: ".$fecha_hoy;

	$query2 = "SELECT COUNT(*) AS existe FROM ventas WHERE id_cliente = $id_cliente AND status_venta = 0";
	$cuantos = GetValueSQL($query2, 'existe');

	if($cuantos == 0){
		$query1 = "INSERT INTO ventas (id_cliente, id_empleado, fecha, total_venta, status_venta) VALUES ($id_cliente, $id_empleado, '$fecha_hoy', 0, 0)";
		$id_venta = ExecuteSQL_returnID($query1);

		if ($id_venta !== false) {
			$resultStatus = "ok";
			$resultText = "Se creó la venta. Agrega productos.";
		} else {
			$resultStatus = "error";
			$resultText = "Error al crear la venta.";
		}
	} else{
		$resultStatus = "warning";
        $resultText = "Ya existe una venta abierta para este cliente.";
		$query3 = "SELECT id_venta FROM ventas WHERE id_cliente = $id_cliente AND status_venta = 0";
		$id_venta = GetValueSQL($query3, 'id_venta');
	}

	
	$result = array( 
		'id_cliente'			=> $id_cliente,
		'id_venta'				=> $id_venta,
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}


if(Requesting("action") == "agregar_dv"){
	$id_venta = Requesting("id_venta");
	$id_producto = Requesting("id_producto");
    $id_producto_talla = Requesting("id_producto_talla");
	$cantidad = Requesting("cantidad");

	$query1 = "SELECT precio FROM productos WHERE id_producto = $id_producto";
	$precio_unitario = GetValueSQL($query1, 'precio');

	$subtotal = floatval($precio_unitario) * floatval($cantidad);

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$query3 = "SELECT COUNT(*) AS existe FROM detalles_ventas WHERE id_venta = $id_venta AND id_producto = $id_producto AND id_producto_talla = $id_producto_talla ";
	$existe = GetValueSQL($query3, 'existe');

	if($existe > 0){

		$query4 = "UPDATE detalles_ventas SET cantidad = cantidad + $cantidad, subtotal = subtotal + $subtotal WHERE id_venta = $id_venta AND id_producto_talla = $id_producto_talla ";
		ExecuteSQL($query4);

	} else{

		$query2 = "INSERT INTO detalles_ventas (id_venta, id_producto, id_producto_talla, cantidad, precio_unitario, subtotal)
					VALUES ($id_venta, $id_producto, $id_producto_talla, $cantidad, $precio_unitario, $subtotal)";
		if(ExecuteSQL($query2)){
			$resultStatus = "ok";
			$resultText = "Se agregó el producto a la venta.";
		} else{
			$resultStatus = "error";
			$resultText = "Ocurrió un error. Inténtalo de nuevo. ";
		}

	}

	

	
	$result = array( 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}


if(Requesting("action") == "editar_dv"){
	$id_detalle_venta = Requesting("id_detalle_venta");
    $id_producto_talla = Requesting("id_producto_talla");
	$cantidad = Requesting("cantidad");

    $resultStatus = "ok";
    $resultText = "Correcto.";



	$query1 = "SELECT * FROM detalles_ventas 
	INNER JOIN productos ON detalles_ventas.id_producto = productos.id_producto
	WHERE id_detalle_venta = $id_detalle_venta";

	$precio = GetValueSQL($query1, 'precio');

	$subtotal = floatval($precio) * floatval($cantidad);

	$query2 = "UPDATE detalles_ventas SET id_producto_talla = $id_producto_talla, cantidad = $cantidad, precio_unitario = $precio, subtotal = $subtotal WHERE id_detalle_venta = $id_detalle_venta";
	if(ExecuteSQL($query2)){
		$resultStatus = "ok";
        $resultText = "Se editó correctamente. ";
    } else{
		$resultStatus = "error";
		$resultText = "Ocurrió un error. Inténtalo de nuevo. ";
	}

	
	$result = array( 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}



if(Requesting("action") == "eliminar_dv"){
	$id_detalle_venta = Requesting("id_detalle_venta");

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$query1 = "DELETE FROM detalles_ventas WHERE id_detalle_venta = $id_detalle_venta";
	if(ExecuteSQL($query1)){
		$resultStatus = "ok";
        $resultText = "Se eliminó correctamente. ";
    } else{
        $resultStatus = "error";
        $resultText = "Ocurrió un error. Inténtalo de nuevo. ";
	}


	
	$result = array( 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}


if(Requesting("action") == "llenar_tabla_dv"){
	$id_venta = Requesting("id_venta");

	// echo "ID Venta: ".$id_venta;

    $resultStatus = "ok";
    $resultText = "Correcto.";
	$xmlRow = "";	

	$query1 = "SELECT COUNT(*) AS cuantos FROM detalles_ventas WHERE id_venta = $id_venta";
	$cuantos = GetValueSQL($query1, 'cuantos');
	if($cuantos > 0){

		$query2 = "SELECT * FROM detalles_ventas
		INNER JOIN productos ON detalles_ventas.id_producto = productos.id_producto
		INNER JOIN producto_talla ON detalles_ventas.id_producto_talla = producto_talla.id_producto_talla
		INNER JOIN tallas ON producto_talla.id_talla = tallas.id_talla
		WHERE id_venta = $id_venta";
		// echo $query2;
		$detalle_venta = DatasetSQL($query2);

		while($row2 = mysqli_fetch_array($detalle_venta)){
			$id_detalle_venta = $row2['id_detalle_venta'];
			$nombre_producto = $row2['nombre_producto'];
			$talla = $row2['talla'];
            $cantidad = $row2['cantidad'];
            $precio_unitario = $row2['precio_unitario'];
            $subtotal = $row2['subtotal'];

			$xmlRow .=  "<tr>
                            <td>$nombre_producto</td>
                            <td>$talla</td>
                            <td>$cantidad</td>
                            <td>$".number_format($precio_unitario, 2)."</td>
                            <td>$".number_format($subtotal, 2)."</td>
                            <td>
								<a type='button' data-bs-toggle='modal' data-bs-target='#modalEditarDV' data-id='$id_detalle_venta' onclick='llenar_form_dv($id_detalle_venta)'><i class='fas fa-pen fa-lg'></i></a> &nbsp;
								<a type='button' onclick='eliminar_dv($id_detalle_venta)'><i class='fas fa-trash text-danger fa-lg'></i></a> 
                            </td>
                        </tr>";
			
		}
		$query3 = "SELECT total_venta FROM ventas WHERE id_venta = $id_venta";
		$total_venta = GetValueSQL($query3, 'total_venta');
		$total_venta = "$".number_format($total_venta, 2);
		$xmlRow .= "<tr>
						<td colspan='6'><strong>Subtotal: </strong>".$total_venta."</td>
					</tr>";
	}





	$result = array( 
		'tabla_detalle_venta' 	=> $xmlRow, 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}



if(Requesting("action") == "llenar_form_dv"){
	$id_detalle_venta = Requesting("id_detalle_venta");

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$xmlRow = '';


	$query1 = "SELECT * FROM detalles_ventas 
	INNER JOIN productos on productos.id_producto = detalles_ventas.id_producto
	WHERE id_detalle_venta = $id_detalle_venta";


	$cantidad = GetValueSQL($query1, 'cantidad');
	$nombre_producto = GetValueSQL($query1, 'nombre_producto');
	$precio = GetValueSQL($query1, 'precio');
	$id_producto = GetValueSQL($query1, 'id_producto');
	$id_producto_talla = GetValueSQL($query1, 'id_producto_talla');

	$precio = "$".number_format($precio, 2);

	$query2 = "SELECT * FROM producto_talla
	INNER JOIN tallas on producto_talla.id_talla = tallas.id_talla
	WHERE id_producto = $id_producto";
	$tallas = DatasetSQL($query2);

	while($row2 = mysqli_fetch_array($tallas)){
		if($id_producto_talla == $row2['id_producto_talla']){
			$xmlRow .= "<option selected='selected' value='".$row2['id_producto_talla']."'>".$row2['talla']."</option>";
		} else{
			$xmlRow .= "<option value='".$row2['id_producto_talla']."'>".$row2['talla']."</option>";
		}
	}



	
	$result = array( 
		'cantidad'				=> $cantidad,
		'select_tallas'			=> $xmlRow,
		'nombre_producto'		=> $nombre_producto,
		'precio'				=> $precio,
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}



if(Requesting("action") == "terminar_venta"){
	$id_venta = Requesting("id_venta");

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$query3 = "SELECT id_cliente FROM ventas WHERE id_venta = $id_venta";
	$id_cliente = GetValueSQL($query3, 'id_cliente');

	$query1 = "SELECT COUNT(*) AS cuantos FROM detalles_ventas WHERE id_venta = $id_venta";
	$cuantos = GetValueSQL($query1, 'cuantos');

	if($cuantos > 0){
		$query2 = "UPDATE ventas SET status_venta = 1 WHERE id_venta = $id_venta";
		
		if(ExecuteSQL($query2)){
			$resultStatus = "ok";
            $resultText = "Venta procesada. ";
		} else{
            $resultStatus = "error";
            $resultText = "Ocurrió un error. Inténtalo de nuevo. ";
        }


		//Restar stock a productos
		$query3 = "SELECT * FROM detalles_ventas WHERE id_venta = $id_venta";
		$detalles_ventas = DatasetSQL($query3);

		while($row3 = mysqli_fetch_array($detalles_ventas)){
			$id_producto = $row3['id_producto'];
            $cantidad = $row3['cantidad'];

			$query4 = "UPDATE productos SET stock = stock - $cantidad WHERE id_producto = $id_producto";
			ExecuteSQL($query4);
		}

	} else{
		$resultStatus = "error";
        $resultText = "Agrega productos para vender";
	}


	$result = array( 
		'id_cliente'			=> $id_cliente,
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;	
}

















if(Requesting("action") == "agregar_producto"){
	$nombre_producto = Requesting("nombre_producto");
	$descripcion = Requesting("descripcion");
	$precio = Requesting("precio");
	$stock = Requesting("stock");
	$categoria = Requesting("categoria");

    $resultStatus = "ok";
    $resultText = "Correcto.";


	$query1 = "SELECT COUNT(*) AS cuantos FROM productos WHERE nombre_producto = '$nombre_producto'";
	$cuantos = GetValueSQL($query1, 'cuantos');

	if($cuantos > 0){
		$resultStatus = "error";
        $resultText = "El producto ya existe.";
	} else{
		$query2 = "INSERT INTO productos (nombre_producto, descripcion, precio, stock, categoria) VALUES ('$nombre_producto', '$descripcion', $precio, $stock, '$categoria')";
        if(ExecuteSQL($query2)){
            $resultStatus = "ok";
            $resultText = "Producto agregado.";
        } else{
            $resultStatus = "error";
            $resultText = "Ocurrió un error. Inténtalo de nuevo. ";
        }
	}



	$result = array( 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;
}

if(Requesting("action") == "llenar_form_producto"){
	$id_producto = Requesting("id_producto");

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$query1 = "SELECT * FROM productos WHERE id_producto = $id_producto";
	$nombre_producto = GetValueSQL($query1, 'nombre_producto');
	$descripcion = GetValueSQL($query1, 'descripcion');
	$precio = GetValueSQL($query1, 'precio');
	$stock = GetValueSQL($query1,'stock');
	$categoria = GetValueSQL($query1, 'categoria');





	$result = array( 
		'id_producto'			=> $id_producto,
		'nombre_producto'		=> $nombre_producto,
		'descripcion'           => $descripcion,
        'precio'                => $precio,
        'stock'                 => $stock,
        'categoria'             => $categoria,
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;
}



if(Requesting("action") == "editar_producto"){
	$id_producto = Requesting("id_producto");
	$nombre_producto = Requesting("nombre_producto");
	$descripcion = Requesting("descripcion");
	$precio = Requesting("precio");
	$stock = Requesting("stock");
	$categoria = Requesting("categoria");

    $resultStatus = "ok";
    $resultText = "Correcto.";


	$query1 = "SELECT COUNT(*) AS existe FROM productos WHERE id_producto = $id_producto";
	// echo $query1;
	$existe = GetValueSQL($query1, 'existe');

	if($existe == 0){
		$resultStatus = "error";
        $resultText = "El producto no existe.";
	} else{
		$query2 = "UPDATE productos SET nombre_producto = '$nombre_producto', descripcion = '$descripcion', precio = $precio, stock = $stock, categoria = '$categoria' WHERE id_producto = $id_producto";
		// echo $query2;
		if(ExecuteSQL($query2)){
			$resultStatus = "ok";
            $resultText = "Producto modificado.";
        } else{ 
			$resultStatus = "error";
			$resultText = "Ocurrió un error. Inténtalo de nuevo. ";
		}
	}



	$result = array( 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;
}

if(Requesting("action") == "cambiar_status_producto"){
	$id_producto = Requesting("id_producto");
	$tipo = Requesting("tipo");

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$query1 = "UPDATE productos SET status = $tipo WHERE id_producto = $id_producto";

	switch($tipo){
		case 1:
			$resultText = "Producto activado.";
            break;
        case 0:
			$resultText = "Producto desactivado.";
			break;
	}


	if(ExecuteSQL($query1)){
		$resultStatus = "ok";
    } else{
		$resultStatus = "error";
		$resultText = "Ocurrió un error. Inténtalo de nuevo. ";
	}
	


	$result = array( 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;
}




if(Requesting("action") == "cambiar_status_talla"){
	$id_producto_talla = Requesting("id_producto_talla");
	$tipo = Requesting("tipo");

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$query1 = "UPDATE producto_talla SET status_producto_talla = $tipo WHERE id_producto_talla = $id_producto_talla";

	switch($tipo){
		case 1:
			$resultText = "Talla activada.";
            break;
        case 0:
			$resultText = "Talla desactivada.";
			break;
	}


	if(ExecuteSQL($query1)){
		$resultStatus = "ok";
    } else{
		$resultStatus = "error";
		$resultText = "Ocurrió un error. Inténtalo de nuevo. ";
	}
	


	$result = array( 
		'result' 				=> $resultStatus, 
		'result_text' 			=> $resultText
	);		
	XML_Envelope($result);     
	exit;
}



if(Requesting("action") == "llenar_select_producto_tallas"){
    $id_producto = Requesting("id_producto");

    $resultStatus = "ok";
    $resultText = "Correcto.";

    $xmlRow = '<option value="0">Selecciona una talla...</option>';

   
	$query1 = "SELECT * FROM tallas";
	$tallas = DatasetSQL($query1);

	while($row1 = mysqli_fetch_array($tallas)){
		$id_talla = $row1['id_talla'];
		$talla = $row1['talla'];
		$xmlRow .=  "<option value='$id_talla'>$talla</option>";
	}


    $result = array( 
        'select_tallas'     	=> $xmlRow, 
        'result'                => $resultStatus, 
        'result_text'           => $resultText
    );      
    XML_Envelope($result);     
    exit;   
}


if(Requesting("action") == "agregar_producto_talla"){
    $id_producto = Requesting("id_producto");
	$id_talla = Requesting("id_talla");

    $resultStatus = "ok";
    $resultText = "Correcto.";

	$query1 = "SELECT COUNT(*) AS existe FROM producto_talla WHERE id_producto = $id_producto AND id_talla = $id_talla";
	$existe = GetValueSQL($query1, 'existe');

	if($existe == 0){
		$query2 = "INSERT INTO producto_talla (id_producto, id_talla) VALUES ($id_producto, $id_talla)";
        if(ExecuteSQL($query2)){
            $resultStatus = "ok";
            $resultText = "Talla agregada.";
        } else{ 
            $resultStatus = "error";
            $resultText = "Ocurrió un error. Inténtalo de nuevo. ";
        }
	} else{
		$resultStatus = "error";
        $resultText = "La talla ya existe.";
	}


	
    $result = array( 
        'result'                => $resultStatus, 
        'result_text'           => $resultText
    );      
    XML_Envelope($result);     
    exit;  
}









?>