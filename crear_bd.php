<?php 

include('conexion.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST["accion"])){
        $accion = $_POST["accion"];

        switch($accion){
            

            case "agregar_tabla":
                if(isset($_POST["name_table"]) && isset($_POST["campo_id"])){
                    $nameBD = $_POST["name_table"];
                    $campo_id = $_POST['campo_id'];
                    //echo $nameBD;
                    //echo $campo_id;

                    $query_check = "SHOW TABLES LIKE '".$nameBD."'";
                    $result = $conexion->query($query_check);

                    if($result->num_rows == 0){
                        $query1 = "CREATE TABLE ".$nameBD." (".$campo_id." INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (".$campo_id."))";
                        //echo $query1;
                        $result = $conexion->query($query1);
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                    } else{
                        echo "La tabla ya existe. <a href='index.php'>Volver</a>";
                    }
                }
            break;

            case "borrar_tabla":
                if(isset($_POST["name_table"])){
                    $nameBD = $_POST["name_table"];
                    //echo $nameBD;

                    $query_check = "SHOW TABLES LIKE '".$nameBD."'";
                    $result = $conexion->query($query_check);

                    if($result->num_rows > 0){
                        $query1 = "DROP TABLE ".$nameBD;
                        //echo $query1;
                        $result = $conexion->query($query1);
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                    } else{
                        echo "La tabla no existe. <a href='index.php'>Volver</a>";
                    }
                }
            break;

            
            case "agregar_campo":
                if (isset($_POST["name_table"]) && isset($_POST["columna"]) && isset($_POST["tipo_dato"])) {
                    $name_table = $_POST["name_table"];
                    $columna = $_POST["columna"];
                    $tipo_dato = $_POST["tipo_dato"];

                    if(!isset($_POST["longitud"])){
                        $longitud = 255;
                    } else{
                        $longitud = $_POST["longitud"];
                        if($longitud == null){
                            $longitud = 255;
                        }
                    }

                    

                    $query_check = "SHOW TABLES LIKE '".$name_table."'";
                    $result = $conexion->query($query_check);

                    if($result->num_rows > 0){
                        $query1 = "SHOW COLUMNS FROM ".$name_table." WHERE Field = '".$columna."'";
                        //echo $query1;
                        $result = $conexion->query($query1);
                        if($result->num_rows == 0){
                            if($tipo_dato == "VARCHAR"){
                                $query2 = "ALTER TABLE ".$name_table." ADD ".$columna." ".$tipo_dato."(".$longitud.")";
                            } else{
                                $query2 = "ALTER TABLE ".$name_table." ADD ".$columna." ".$tipo_dato;
                            }
                            
                            //echo $query2;
                            $result = $conexion->query($query2);
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                        } else{
                            echo "El campo ya existe. <a href='modificar-tablas.php'>Volver</a>";
                        }
                    } else{
                        echo "La tabla no existe. <a href='modificar-tablas.php'>Volver</a>";
                    }
                }
            break;

            case "eliminar_campo":
                if (isset($_POST["name_table"]) && isset($_POST["columna"])) {
                    $name_table = $_POST["name_table"];
                    $columna = $_POST["columna"];
                  

                    $query_check = "SHOW TABLES LIKE '".$name_table."'";
                    $result = $conexion->query($query_check);

                    if($result->num_rows > 0){
                        $query1 = "SHOW COLUMNS FROM ".$name_table." WHERE Field = '".$columna."'";
                        //echo $query1;
                        $result = $conexion->query($query1);
                        if($result->num_rows > 0){
                            
                            $query2 = "ALTER TABLE ".$name_table." DROP COLUMN ".$columna;
                            
                            //echo $query2;
                            $result = $conexion->query($query2);
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                        } else{
                            echo "El campo no existe. <a href='modificar-tablas.php'>Volver</a>";
                        }
                    } else{
                        echo "La tabla no existe. <a href='modificar-tablas.php'>Volver</a>";
                    }
                }
            break;

            
            case "modificar_campo":
                if (isset($_POST["name_table"]) && isset($_POST["columna"]) && isset($_POST["nueva_columna"]) && isset($_POST["tipo_dato"])) {
                    $name_table = $_POST["name_table"];
                    $columna = $_POST["columna"];
                    $nueva_columna = $_POST["nueva_columna"];
                    $tipo_dato = $_POST["tipo_dato"];

                    if(!isset($_POST["longitud"])){
                        $longitud = 255;
                    } else{
                        $longitud = $_POST["longitud"];
                        if($longitud == null){
                            $longitud = 255;
                        }
                    }

                    

                    $query_check = "SHOW TABLES LIKE '".$name_table."'";
                    $result = $conexion->query($query_check);

                    if($result->num_rows > 0){
                        $query1 = "SHOW COLUMNS FROM ".$name_table." WHERE Field = '".$columna."'";
                        //echo $query1;
                        $result = $conexion->query($query1);
                        if($result->num_rows > 0){
                            if($tipo_dato == "VARCHAR"){
                                $query2 = "ALTER TABLE ".$name_table." CHANGE ".$columna." ".$nueva_columna." ".$tipo_dato."(".$longitud.")";
                            } else{
                                $query2 = "ALTER TABLE ".$name_table." CHANGE ".$columna." ".$nueva_columna." ".$tipo_dato;
                            }
                            
                            //echo $query2;
                            $result = $conexion->query($query2);
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                        } else{
                            echo "El campo no existe. <a href='modificar-tablas.php'>Volver</a>";
                        }
                    } else{
                        echo "La tabla no existe. <a href='modificar-tablas.php'>Volver</a>";
                    }
                }
            break;

            case "agregar_registro":
                if(isset($_POST["tabla"])) {
                    $tabla = $_POST["tabla"];

                    // Validar los datos antes de la inserción (puedes usar funciones de validación o expresiones regulares)
                    $errores = array();

                    // Ejemplo de validación: Verificar si se proporciona un valor para cada campo
                    foreach ($_POST as $key => $value) {
                        if (empty($value)) {
                            $errores[] = "El campo $key es obligatorio.";
                        }
                    }

                    // Si hay errores, mostrar mensaje de error
                    if (!empty($errores)) {
                        echo "<div class='alert alert-danger' role='alert'>";
                        foreach ($errores as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div>";
                    } else {
                        $query_columns = "SHOW COLUMNS FROM $tabla";
                        $result_columns = $conexion->query($query_columns);

                        $columnas_tabla = array();

                        if ($result_columns && $result_columns->num_rows > 0) {
                            while ($fila = $result_columns->fetch_assoc()) {
                                $columnas_tabla[] = $fila['Field'];
                            }
                        }
                        // No hay errores, proceder con la inserción en la base de datos
                        // Filtrar solo los datos relacionados con la tabla
                        $datos_tabla = array_intersect_key($_POST, array_flip($columnas_tabla));
                        
                        // Construir la consulta de inserción
                        $columnas = implode(", ", array_keys($datos_tabla));
                        $valores = "'" . implode("', '", $datos_tabla) . "'";
                        $query = "INSERT INTO $tabla ($columnas) VALUES ($valores)";

                        // Ejecutar la consulta
                        if ($conexion->query($query) === TRUE) {
                            echo "<div class='alert alert-success' role='alert'>Registro agregado exitosamente.</div>";
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>Error al agregar el registro: " . $conexion->error . "</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>No se proporcionó la tabla para agregar el registro.</div>";
                }
            break;

            case "editar_registro":

            break;

            case "eliminar_registro":
                if(isset($_POST["tabla"]) && isset($_POST["registro_id"])) {
                    $tabla = $_POST["tabla"];
                    $nombre_columna_id = $_POST["nombre_id"];
                    $registro_id = $_POST["registro_id"];
            
                    // Construir la consulta de eliminación
                    $query = "DELETE FROM $tabla WHERE $nombre_columna_id = $registro_id"; // Reemplaza 'id' con el nombre de tu columna de identificación única
            
                    // Ejecutar la consulta
                    if ($conexion->query($query) === TRUE) {
                        echo "<div class='alert alert-success' role='alert'>Registro eliminado exitosamente.</div>";
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Error al eliminar el registro: " . $conexion->error . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>No se proporcionó la tabla o el ID del registro a eliminar.</div>";
                }
            break;

        }
    } 
}


?>