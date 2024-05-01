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
<body id="body">

    <?php
    
	require_once "include/functions.php";
	require_once "include/db_tools.php";  
    // include('main-header.php') 

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
                        <a class="nav-link active" aria-current="page" href="index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="estructura-de-tablas">Estructura de Tablas</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
        
    <div class="container" id="container_tabla">
        <h1>Punto de Venta de Tenis</h1>
        <h2>Tabla <?php echo $table; ?></h2>
        <div class="container">
        <input id="nombre_tabla" type="hidden" value="<?php echo $table; ?>">
            <button class="btn btn-success text-white" type="button" data-bs-toggle="modal" data-bs-target="#modalAgregar" data-bs-whatever="@mdo">Nuevo registro</button>
            
            <div class="row justify-content-center mt-3">
                <div class="col-xl-8">
                    <table border='1' class="table table-striped" id="table_tablas">
                        <thead>
                            <tr>
                                <?php
                                $query2 = "SHOW COLUMNS FROM $table";
                                $columnas = DatasetSQL($query2);

                                $num_columnas = $columnas->num_rows;

                                while($row2 = mysqli_fetch_array($columnas)){
                                    echo "<th>".$row2['Field']."</th>";
                                }
                                ?>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_a_rellenar"></tbody>
                        
                        
                    </table>
                </div>
                
                <div class="col-xl-4">
                    <h4 class="text">Ordenar por:</h4>
                    <select id="select_ordenamiento" class="select">
                        <option value="1">ID de menor a mayor</option>
                        <option value="2">ID de mayor a menor</option>

                        <?php
                        $query3 = "SHOW COLUMNS FROM $table";
                        $columnas = DatasetSQL($query2);

                        $num_columnas = $columnas->num_rows;

                        while($row3 = mysqli_fetch_array($columnas)){
                            Switch($row3['Field']){
                                case 'status':
                                    echo "<option value='3'>".$row3['Field']." de menor a mayor</option>";
                                    echo "<option value='4'>".$row3['Field']." de mayor a menor</option>";
                                break;
                                case 'precio':
                                    echo "<option value='5'>".$row3['Field']." de menor a mayor</option>";
                                    echo "<option value='6'>".$row3['Field']." de mayor a menor</option>";
                                    break;
                                case 'subtotal':
                                    echo "<option value='7'>".$row3['Field']." de menor a mayor</option>";
                                    echo "<option value='8'>".$row3['Field']." de mayor a menor</option>";
                                    break;
                                case 'cantidad':
                                    echo "<option value='9'>".$row3['Field']." de menor a mayor</option>";
                                    echo "<option value='10'>".$row3['Field']." de mayor a menor</option>";
                                    break;
                                case 'stock':
                                    echo "<option value='11'>".$row3['Field']." de menor a mayor</option>";
                                    echo "<option value='12'>".$row3['Field']." de mayor a menor</option>";
                                    break;
                                case 'fecha':
                                    echo "<option value='13'>".$row3['Field']." de menor a mayor</option>";
                                    echo "<option value='14'>".$row3['Field']." de mayor a menor</option>";
                                    break;

                            }
                        }
                        ?>
                    </select>
                    <br>
                </div>
            </div>
        </div>
    </div>
    

   <!-- Ventana modal para añadir nuevo registro -->
   <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Añadir Nuevo Registro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_nuevo_registro">
                        <input type="hidden" name="tabla" value="<?php echo $table; ?>">
                        <?php
                        // Obtener los nombres de las columnas de la tabla
                        $query_columns = "SHOW COLUMNS FROM $table";
                        $result_columns = DatasetSQL($query_columns);

                        if ($result_columns && $result_columns->num_rows > 0) {
                            // Contador para omitir el primer campo
                            $contador = 0;
                            while ($fila = mysqli_fetch_array($result_columns)) {
                                $columna = $fila['Field'];
                                // Omitir el primer campo
                                if ($contador > 0) {
                                    // Mostrar un campo de entrada para cada columna de la tabla
                                    echo "<div class='mb-3'>";
                                    echo "<label for='$columna' class='form-label' id='add_$columna'>$columna:</label>";
                                    echo "<input type='text' class='form-control' id='$columna' name='$columna'>";
                                    echo "</div>";
                                }
                                $contador++;
                            }
                        }
                        ?>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick='agregar_registro("<?php echo $table; ?>")'>Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventana modal para editar registro -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_editar_registro">
                        <?php 
                        // Obtener el nombre de la columna de identificación única
                        $query_id_column = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
                        $result_id_column = DatasetSQL($query_id_column);
                        $id_column_name = '';
                        if($result_id_column->num_rows > 0) {
                            $row_id_column = mysqli_fetch_array($result_id_column);
                            $id_column_name = $row_id_column['Column_name'];
                        }
                        ?>
                        <input type="hidden" id="editar_nombre_id" name="editar_nombre_id" value="<?php echo $id_column_name; ?>">
                        <input type="hidden" id="editar_id_registro" name="editar_id_registro" value="">
                        <input type="hidden" id="tabla" name="tabla" value="<?php echo $table; ?>">
                        <?php
                        // Obtener los nombres de las columnas de la tabla
                        $query_columns = "SHOW COLUMNS FROM $table";
                        $result_columns = DatasetSQL($query_columns);

                        

                        if ($result_columns && $result_columns->num_rows > 0) {
                            // Contador para omitir el primer campo
                            $contador = 0;
                            while ($fila = mysqli_fetch_array($result_columns)) {
                                $columna = $fila['Field'];
                                // Omitir el primer campo
                                if($contador > 0){
                                    // Mostrar un campo de entrada para cada columna de la tabla
                                    echo "<div class='mb-3'>";
                                    echo "<label for='$columna' class='form-label' id='label_editar_$columna'>$columna:</label>";
                                    echo "<input type='text' class='form-control' id='editar_$columna' name='$columna'>";
                                    echo "</div>";
                                }
                                $contador++;
                            }
                        }
                        ?>
                        
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

    <!-- Ventana modal para eliminar registro -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se cargarán los datos del registro a eliminar -->
                    <p>¿Estás seguro de que deseas eliminar este registro?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <!-- Formulario para enviar la solicitud de eliminación -->
                    <?php
                    // Obtener el nombre de la columna de identificación única
                    $query_id_column = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
                    $result_id_column = DatasetSQL($query_id_column);
                    $id_column_name = '';
                    if($result_id_column->num_rows > 0) {
                        $row_id_column = mysqli_fetch_array($result_id_column);
                        $id_column_name = $row_id_column['Column_name'];
                    }
                    ?>
                    <form id="formEliminar">
                        <!-- Pasar el nombre de la columna de identificación única como valor -->
                        <input type="hidden" id="eliminar_nombre_id" name="eliminar_nombre_id" value="<?php echo $id_column_name; ?>">
                        <input type="hidden" id="eliminar_nombre_tabla" name="eliminar_nombre_tabla" value="<?php echo $table; ?>">
                        <input type="hidden" id="eliminar_id_registro" name="eliminar_id_registro" value="">
                        <button type="button" class="btn btn-danger" onclick="eliminar_registro()">Eliminar</button>
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
        $(document).ready(function() { 
            var tabla = $("#nombre_tabla").val();
            //modificar-tablas
            llenar_tabla(tabla, 1);
            
        });

        // Función para capturar el ID del registro seleccionado
        $('#modalEliminar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            // console.log(id);
            modal.find('#eliminar_id_registro').val(id);
        });

        // Función para capturar el ID del registro seleccionado
        $('#modalEditar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            // console.log(id);
            modal.find('#editar_id_registro').val(id);
        });
    </script>
</body>
</html>