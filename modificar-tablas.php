<?php 
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
	<?php include("headertagbase.php"); ?>
    <link rel="stylesheet" href="estilos4.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->
	<link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"> <!-- Swal -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Swal -->
</head>
<body >
    <?php
    
	    
        require_once "include/functions.php";
        require_once "include/db_tools.php";  
        //include('main-header.php')


        //include('conexion.php'); 

        if(isset($_GET['tabla'])){
            $table = $_GET['tabla'];

            $query1 = "SHOW TABLES LIKE '".$table."'";

            $existe_tabla = DatasetSQL($query1); 
            if($existe_tabla->num_rows > 0){
            } else{
                header('Location: ../index');
                exit;
            }
        } else{
            header('Location: index');
            exit;
        }

    ?>
    
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="gestion-registros">Gestión de Registros</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <input id="nombre_tabla" type="hidden" value="<?php echo $table; ?>">
        <h1>Modificar Tablas</h1>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8">
                    <!-- Formulario para modificar tablas -->
                    <form class="form">
                        <h4>Modificar Tabla</h4>
                        <div class="form-group">
                            <label for="accion_tabla">Acción:</label>
                            <select name="accion_tabla" id="accion_tabla" class="select" required>
                                <option value="agregar_columna">Agregar Campo</option>
                                <option value="borrar_columna">Borrar Campo</option>
                                <option value="modificar_columna">Modificar Campo</option>
                            </select>
                        </div>
                    </form>

                    <hr>

                    <form class="form" id="formAgregarCampo">
                        <h4>Agregar Campo</h4>
                        <div class="form-group">
                            <label for="columna">Nombre de la Columna:</label>
                            <input type="text" name="columna" id="agregar_campo_columna" class="input-text" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo_dato">Tipo de Dato:</label>
                            <select name="tipo_dato" class="select tipo_dato" id="agregar_campo_tipo_dato" required>
                                <option value="INT">INT</option>
                                <option value="VARCHAR" selected>VARCHAR</option>
                                <option value="FLOAT">FLOAT</option>
                                <option value="DATE">DATE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="longitud">Longitud:</label>
                            <input type="number" name="longitud" class="longitud input-text" id="agregar_campo_longitud" disabled>
                        </div>
                        <div class="form-group">
                            <button onclick="agregar_campo(event)" class="btn btn-success btn-submit">Guardar</button>
                        </div>
                    </form>


                    <form class="form" id="formEliminarCampo">
                        <h4>Eliminar Campo</h4>
                        <div class="form-group">
                            <label for="columna">Nombre de la Columna:</label>
                            <input type="text" name="columna" id="eliminar_campo_columna" class="input-text" required>
                        </div>
                        <div class="form-group">
                            <button onclick="eliminar_campo(event)" class="btn btn-danger btn-submit">Eliminar</button>
                        </div>
                    </form>

                    
                    <form action="crear_bd.php" method="POST" class="form" id="formModificarCampo">
                        <h4>Modificar Campo</h4>
                        <div class="form-group">
                            <label for="columna">Nombre de la Columna:</label>
                            <input type="text" name="columna" id="columna" class="input-text" required>
                        </div>
                        <div class="form-group">
                            <label for="nueva_columna">Nuevo Nombre de la Columna:</label>
                            <input type="text" name="nueva_columna" id="nueva_columna" class="input-text" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo_dato">Tipo de Dato:</label>
                            <select name="tipo_dato"  class="select tipo_dato" required>
                                <option value="INT">INT</option>
                                <option value="VARCHAR" selected>VARCHAR</option>
                                <option value="FLOAT">FLOAT</option>
                                <option value="DATE">DATE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="longitud">Longitud:</label>
                            <input type="number" name="longitud" class="longitud" class="input-text" disabled>
                        </div>
                        <div class="form-group">
                            <button onclick="modificar_campo(event)" class="btn btn-danger btn-submit">Eliminar</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <h2>Estructura Tabla <?php echo $table; ?></h2>
                <table border='1' class="table" id="table_tablas">
                    <tr>
                        <th>Tabla</th>
                        <th>Columna</th>
                        <th>Tipo de Dato</th>
                        <th>Llave Primaria</th>
                    </tr>
                    <?php


                    
                        $query_table_info = "DESCRIBE $table";
                        $result_table_info = DatasetSQL($query_table_info);
                
                        echo "<tr><td rowspan='" . $result_table_info->num_rows . "'>$table</td>";
                
                        while ($row_table_info = mysqli_fetch_array($result_table_info)){
                            echo "<td>" . $row_table_info['Field'] . "</td>";
                            echo "<td>" . $row_table_info['Type'] . "</td>";
                
                            if($row_table_info['Key'] == "PRI"){
                                echo "<td>Yes</td>";
                            } else{
                                echo "<td>No</td>";
                            }
                
                            echo "</tr><tr>";
                        }
                        echo "</tr>";
                    ?>
                </table>
            </div>
        </div>
        
    </div>

    
    <script src="js/main.js"></script>
    
	<script src="assets/js/jquery-2.2.4.min.js"></script>
	<script src="assets/js/slick.min.js"></script>
	<script src="assets/js/jquery-ui.js"></script>
	<script src="assets/js/jquery.nice-select.js"></script>
	<script src="assets/js/scripts.js"></script>
	<script src="assets/js/funciones.js"></script>
	
	<script src="assets/plugins/sweetalert/sweetalert.min.js"></script> 
	<script src="assets/plugins/sweetalert/jquery.sweet-alert.custom.js"></script>

    <script src="script.js"></script>
     <!-- Incluir Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

