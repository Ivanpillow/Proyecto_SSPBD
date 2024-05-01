
<?php

if(isset($_GET['tabla'])) {
    $tabla = $_GET['tabla'];
    
} else {
    header("Location: gestion-registros");
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar registros</title>
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

    ?>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="gestion-tablas">Gestión de Tablas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="estructura-de-tablas">Estructura de Tablas</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
    <div class="body">
        
        <div class="container">
            <h1>Tabla <?php echo $tabla; ?></h1>

            <hr>

            <div>
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <?php 
                        $query1 = "SHOW COLUMNS FROM $tabla";
                        $result1 = DatasetSQL($query1);

                        if ($result1) {
                            while ($fila = mysqli_fetch_array($result1)) {
                                // Utiliza $fila['Field'] para obtener el nombre de la columna
                                echo "<th scope='col'>" . $fila['Field'] . "</th>";
                            }
                            // Añadir columnas adicionales para editar y eliminar
                            echo "<th scope='col'>Editar</th>";
                            echo "<th scope='col'>Eliminar</th>";
                        } else {
                            // Mostrar mensaje de error si la consulta falla
                            echo "<th scope='col' colspan='3'>Error al obtener las columnas de la tabla.</th>";
                        }
                        ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $query2 = "SELECT * FROM $tabla";
                    $result2 = DatasetSQL($query2);

                    // Obtener el nombre de la columna de identificación única
                    $query_id_column = "SHOW KEYS FROM $tabla WHERE Key_name = 'PRIMARY'";
                    $result_id_column = DatasetSQL($query_id_column);
                    $id_column_name = '';
                    if ($result_id_column->num_rows > 0) {
                        $row_id_column = mysqli_fetch_array($result_id_column);
                        $id_column_name = $row_id_column['Column_name'];
                    }

                    while($row = mysqli_fetch_array($result2)){
                        
                        echo "<tr>";

                        $query3 = "SHOW COLUMNS FROM $tabla";
                        $nombre_columnas = DatasetSQL($query3);
                    
                        while($row2 = mysqli_fetch_array($nombre_columnas)){
                            $columna = $row2['Field'];

                            $cell = $row[$columna]; 
                            echo "<td>".$cell."</td>";  
                        }

                        
                        
                        // Obtener el id del registro
                        $id = $row[$id_column_name]; // Usar el nombre de la columna de identificación única
                        // Agregar columnas adicionales para editar y eliminar
                        echo "<td><button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#modalEditar' data-id='$id'><i class='fas fa-edit'></i>Editar</button></td>";
                        echo "<td><button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalEliminar' data-id='$id'><i class='fas fa-trash-alt'></i>Eliminar</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="<?php echo $result1->num_rows + 2; ?>"><button type="button" class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#modalAgregar" data-bs-whatever="@mdo">Añadir Nuevo Registro</button></td>
                    </tr>
                </tfoot>
            </table>



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
                    <form method="POST" action="crear_bd.php">
                        <input type="hidden" name="accion" value="agregar_registro">
                        <input type="hidden" name="tabla" value="<?php echo $tabla; ?>">
                        <?php
                        // Obtener los nombres de las columnas de la tabla
                        $query_columns = "SHOW COLUMNS FROM $tabla";
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
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
                    <form method="POST" action="crear_bd.php">
                        <input type="hidden" name="accion" value="agregar_registro">
                        <input type="hidden" name="tabla" value="<?php echo $tabla; ?>">
                        <?php
                        // Obtener los nombres de las columnas de la tabla
                        $query_columns = "SHOW COLUMNS FROM $tabla";
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
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
                    $query_id_column = "SHOW KEYS FROM $tabla WHERE Key_name = 'PRIMARY'";
                    $result_id_column = DatasetSQL($query_id_column);
                    $id_column_name = '';
                    if ($result_id_column->num_rows > 0) {
                        $row_id_column = mysqli_fetch_array($result_id_column);
                        $id_column_name = $row_id_column['Column_name'];
                    }
                    ?>
                    <form id="formEliminar" action="crear_bd.php" method="POST">
                        <input type="hidden" name="accion" value="eliminar_registro">
                        <!-- Pasar el nombre de la columna de identificación única como valor -->
                        <input type="hidden" name="nombre_id" value="<?php echo $id_column_name; ?>">
                        <input type="hidden" name="tabla" value="<?php echo $tabla; ?>">
                        <input type="hidden" id="registro_id" name="registro_id" value="">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Función para capturar el ID del registro seleccionado
        $('#modalEliminar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            console.log(id);
            modal.find('#registro_id').val(id);
        });
    </script>

    <script src="script.js"></script>
     <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>