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
    <!-- Bootstrap 4 -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->
	<link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"> <!-- Swal -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Swal -->
</head>
<body">

    <?php
    
	require_once "include/functions.php";
	require_once "include/db_tools.php";  
    include('main-header.php') 

    ?>

    <!--=====================================
			#region Titulos de tablas
	======================================-->    
    <div class="container">
        <h1>Punto de Venta de Tenis</h1>
        <h2>&nbsp;Productos</h2>
        <div class="container" id="div_tabla_productos">
            <a class="btn btn-success text-white"  data-bs-toggle='modal' data-bs-target='#modalAgregarProducto'>Nuevo producto</a>
            <div class="row justify-content-center">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID Producto</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Precio</th>
                                <th scope="col">En inventario</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Status</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_ventas">

                        <!--=====================================
                                #region Creacion de vista
                        ======================================-->
                            <?php

                            $query2 = "SELECT COUNT(*) AS existe FROM productos";
                            $existe_productos = GetValueSQL($query2, 'existe');
                            
                            if($existe_productos > 0){

                                $query2 = "SELECT * FROM productos";
                                $result2 = DatasetSQL($query2);

                                while($row2 = mysqli_fetch_array($result2)){
                                    $id_producto = $row2['id_producto'];
                                    $nombre_producto = $row2['nombre_producto'];
                                    $descripcion = $row2['descripcion'];
                                    $precio = $row2['precio'];
                                    $stock = $row2['stock'];
                                    $categoria = $row2['categoria'];
                                    $status = $row2['status'];

                                    if($status == 1){
                                        $status = "Activo";
                                        $icon = "<a href='' onclick='cambiar_status_producto($id_producto, 0, event)'><i class='fas fa-toggle-on fa-lg text-success'></i></a>";
                                    } else{
                                        $status = "Inactivo";
                                        $icon = "<a href='' onclick='cambiar_status_producto($id_producto, 1, event)'><i class='fas fa-toggle-off fa-lg text-secondary'></i></a>";
                                    }


                                    echo "<tr>";
                                    echo "<td>$id_producto</td>";
                                    echo "<td><a href='producto/$id_producto'>$nombre_producto</a></td>";
                                    echo "<td>$".number_format($precio, 2)."</td>";
                                    echo "<td>$stock</td>";
                                    echo "<td>$categoria</td>";
                                    echo "<td>$status</td>";
                                    echo "<td>
                                        
                                        <a type='button' data-bs-toggle='modal' data-bs-target='#modalEditarProducto' data-id-producto='$id_producto' title='Editar' onclick='llenar_form_producto($id_producto)'><i class='fas fa-pen fa-lg'></i></a> 
                                        &nbsp;
                                        $icon
                                        &nbsp;
                                        <a type='button' href='' onclick='ver_descripcion_producto($id_producto, event)'>(Ver descripción)</a>
                                    </td>";
                                    echo "</tr>";

                                    echo "<tr id='descripcion_producto_$id_producto' style='display: none;'>
                                        <td colspan='7'><strong>Descripción: </strong>$descripcion</td>
                                    </tr>";
                                    
                                }
                            }
            
                            ?>

                            
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>



   <!-- Ventana modal para añadir nuevo producto -->
   <div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="form_agregar_producto">
                        <div class='mb-3'>
                            <label for='add_nombre_producto' class='form-label'>Nombre del Producto:</label>
                            <input type="text"name="add_nombre_producto" id="add_nombre_producto" class="form-control obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='add_descripcion' class='form-label'>Descripción:</label>
                            <textarea rows=5 name="add_descripcion" id="add_descripcion" class="form-control obligatorio"></textarea>
                        </div>
                        <div class='mb-3'>
                            <label for='add_precio' class='form-label'>Precio unitario: </label>
                            <input type="number" name="add_precio" id="add_precio" class="form-control obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='add_stock' class='form-label'>En almacén: </label>
                            <input type="number" name="add_stock" id="add_stock" class="form-control obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='add_categoria' class='form-label'>Categoría:</label>
                            <input type="text"name="add_categoria" id="add_categoria" class="form-control obligatorio"></input>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick='agregar_producto()'>Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    

    <!-- Ventana modal para editar producto -->
    <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_producto" name="edit_id_producto" value="">
                    <form id="form_editar_producto">
                        <div class='mb-3'>
                            <label for='edit_nombre_producto' class='form-label'>Nombre del Producto:</label>
                            <input type="text"name="edit_nombre_producto" id="edit_nombre_producto" class="form-control obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='edit_descripcion' class='form-label'>Descripción:</label>
                            <textarea rows=5 name="edit_descripcion" id="edit_descripcion" class="form-control obligatorio"></textarea>
                        </div>
                        <div class='mb-3'>
                            <label for='edit_precio' class='form-label'>Precio unitario: </label>
                            <input type="number" name="edit_precio" id="edit_precio" class="form-control obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='edit_stock' class='form-label'>En almacén: </label>
                            <input type="number" name="edit_stock" id="edit_stock" class="form-control obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='edit_categoria' class='form-label'>Categoría:</label>
                            <input type="text"name="edit_categoria" id="edit_categoria" class="form-control obligatorio"></input>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="editar_producto()">Guardar</button>
                    </div>
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
            
            // llenar_select_clientes();
            // llenar_select_empleados();
            // llenar_select_productos();
        });

        // Función para capturar el ID del registro seleccionado
        $('#modalEditarProducto').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            // console.log(id);
            modal.find('#editar_id_producto').val(id);
        });
     </script>
</body>
</html>