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
    <link rel="stylesheet" href="estilos4.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->
	<link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"> <!-- Swal -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Swal -->
</head>
<body style="background-color: ;">

    <?php
    
	require_once "include/functions.php";
	require_once "include/db_tools.php";  
    // include('main-header.php') 

    ?>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="estructura-de-tablas">Estructura de Tablas</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
        
    <div class="container">
        <h1>Punto de Venta de Tenis</h1>
        <h2>Tablas Existentes</h2>
        <div class="container">
            <button class="btn btn-success text-white" type="button" data-bs-toggle='modal' data-bs-target='#modalAgregar' data-bs-whatever="@mdo">Nueva tabla</button>
            <div class="row justify-content-center">
                <div class="col-8">
                    <table border='1' class="table" id="table_tablas">
                        <tr>
                            <th>Opciones</th>
                            <th>Tabla</th>
                            <th>Columna</th>
                            <th>Tipo de Dato</th>
                            <th>Llave Primaria</th>
                            <th>Opciones Campo</th>
                        </tr>
                        <?php
                        $query = "SHOW TABLES";
                        $result = DatasetSQL($query);

                        while($row = mysqli_fetch_array($result)){
                            $table = $row[0];
                    
                            $query_table_info = "DESCRIBE $table";
                            $result_table_info = DatasetSQL($query_table_info);
                    
                            
                            // <a class='btn btn-success' onclick=''>Editar tabla</a>
                            // <a class='btn btn-danger' onclick=''>Borrar tabla</a>
                            
                            echo "<tr>
                            <td rowspan='" . $result_table_info->num_rows . "'>
                                <button class='btn btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalAgregarCampo' data-name='".$table."'><i class='fas fa-plus-circle'></i> Agregar campo</button> <br><br>
                                <button class='btn btn-danger text-white' type='button'  data-bs-toggle='modal' data-bs-target='#modalEliminar' data-name='".$table."'><i class='fas fa-trash'></i> Borrar tabla</button> 
                            </td>";

                            // <button class='btn btn-secondary text-white' type='button' onclick=\"redirect_modificar_tabla('$table')\"><i class='fas fa-pen'></i> Editar tabla</button> <br><br>
                           
                            // echo " <td rowspan='" . $result_table_info->num_rows . "'><a href='tabla/$table'>$table</a></td>";
                        
                            echo " <td rowspan='" . $result_table_info->num_rows . "'>$table</td>";
                    
                            while ($row_table_info = mysqli_fetch_array($result_table_info)){
                                $nombre_campo = $row_table_info['Field'];
                                echo "<td>".$nombre_campo."</td>";
                                echo "<td>".$row_table_info['Type']."</td>";
                    
                                if($row_table_info['Key'] == "PRI"){
                                    echo "<td>Sí</td>";
                                } else{
                                    echo "<td>No</td>";
                                }

                                echo "<td>
                                    <a type='button' data-bs-toggle='modal' data-bs-target='#modalEditarCampo' data-name-campo='$nombre_campo'><i class='fas fa-pen fa-lg'></i></a> &nbsp;
                                    <a type='button' data-bs-toggle='modal' data-bs-target='#modalEliminarCampo' data-name-campo='$nombre_campo'><i class='fas fa-trash text-danger fa-lg'></i></a>
                                </td>";
                    
                                echo "</tr><tr>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Ventana modal para añadir nuevo campo -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Tabla</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_agregar_tabla" class="form">
                        <div class="form-group">
                            <label for="name_table">Tabla: </label>
                            <input type="text" name="name_table" id="name_table" class="input-text" required>
                        </div>
                        <div class="form-group">
                            <label for="campo_id">Campo ID: </label>
                            <input type="text" name="campo_id" id="campo_id" class="input-text" required>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button"  onclick="agregar_tabla()" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Ventana modal para eliminar campo -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Tabla</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se cargarán los datos del registro a eliminar -->
                    <p>¿Estás seguro de que deseas eliminar esta tabla?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminar">
                        <!-- Pasar el nombre de la columna de identificación única como valor -->
                        <input type="hidden" id="eliminar_nombre_tabla" name="eliminar_nombre_tabla">
                        <button type="button" class="btn btn-danger" onclick="eliminar_tabla()">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <!-- MODALES PARA CAMPOS -->

    <!-- Ventana modal para agregar campo -->
    <div class="modal fade" id="modalAgregarCampo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Agregar Campo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
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
                </div>

                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="modificar_registro('<?php echo $table; ?>')">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Ventana modal para editar campo -->
    <div class="modal fade" id="modalEditarCampo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Editar Campo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="form" id="formEditarCampo">
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

                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="modificar_registro('<?php echo $table; ?>')">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Ventana modal para eliminar campo -->
    <div class="modal fade" id="modalEliminarCampo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Campo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se cargarán los datos del registro a eliminar -->
                    <p>¿Estás seguro de que deseas eliminar este campo?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formEliminar">
                        <!-- Pasar el nombre de la columna de identificación única como valor -->
                        <input type="text" id="eliminar_nombre_campo" name="eliminar_nombre_campo">
                        <button type="button" class="btn btn-danger" onclick="eliminar_campo()">Eliminar</button>
                    </form>
                </div>
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

     <script>
    // Función para capturar el ID del registro seleccionado
        $('#modalEliminar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var name_table = button.data('name');
            var modal = $(this);
            // console.log(name_table);
            modal.find('#eliminar_nombre_tabla').val(name_table);
        });

        //Campos


        
        $('#modalEliminarCampo').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var name_campo = button.data('name-campo');
            var modal = $(this);
            // console.log(name_table);
            modal.find('#eliminar_nombre_campo').val(name_campo);
        });
    </script>
</body>
</html>