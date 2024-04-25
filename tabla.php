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
<body>

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
                        <a class="nav-link " aria-current="page" href="gestion-registros">Gestión de Registros</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
        
    <div class="container">
        <h1>Punto de Venta de Tenis</h1>
        <h2>Tabla <?php echo $table; ?></h2>
        <div class="container">
        <input id="nombre_tabla" type="hidden" value="<?php echo $table; ?>">
            <button class="btn btn-success text-white" type="button" data-bs-toggle='modal' data-bs-target='#modalAgregar' data-bs-whatever="@mdo">Nuevo registro</button>
            
            <div class="row justify-content-center mt-3">
                <div class="col-8">
                    <table border='1' class="table" id="table_tablas">
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
                
                <div class="col-4">
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
                        <div class="form-group">
                            <button  onclick="agregar_tabla()" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventana modal para eliminar nuevo registro -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Tabla</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_eliminar_tabla" class="form">
                        <p class="text">Se eliminará permanentemente. ¿Continuar?</p>
                        <input type="text" id="tabla_nombre" name="tabla_nombre">
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button onclick="eliminar_tabla()" class="btn btn-danger">Eliminar</button>
                        </div>
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
            var tableName = button.data('table');
            var modal = $(this);
            modal.find('#tabla_nombre').val(tableName);
        });
    </script>
</body>
</html>