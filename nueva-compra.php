<?php 
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras</title>
	<?php include("headertagbase.php"); ?>
    <link rel="stylesheet" href="estilos4.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap 4 -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->
	<link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"> <!-- Swal -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Swal -->
</head>
<body>

    <?php
    
	require_once "include/functions.php";
	require_once "include/db_tools.php";  
    include('main-header.php'); 

    ?>

    
        
    <div class="container">
        <h1>Punto de Venta de Tenis</h1>
        <h2>&nbspCompras</h2>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <h4 class="text">Proveedor:</h4>
                    <select id="select_proveedores" class="select"></select>
                </div>
                <div class="col-lg-4">
                    <h4 class="text">Empleado:</h4>
                    <select id="select_empleados" class="select"></select>
                </div>
                <div class="col-lg-4">
                    <h4 class="text">.</h4>
                    <button class="btn btn-success text-white" onclick="crear_compra()">Crear compra</button>
                </div>
            </div>

            <hr>
            
            <div class="row justify-content-center mt-5" id="div_detalles_producto">
                <input type="hidden" id="id_proveedor">
                <input type="hidden" id="id_compra">
                <div class="col-lg-12">
                    <button class="btn btn-secondary text-white" type="button" data-bs-toggle="modal" data-bs-target="#modalNuevaDC" data-bs-whatever="@mdo">Nuevo producto</button>
                </div>
                <div class="col-lg-12 table-responsive mt-4">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Producto</th>
                                <th scope="col">Talla</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Precio Unitario</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="table_detalle_compra"></tbody>
                    </table>
                </div>
                <div class="col-lg-12 text-end mt-5">
                    <button class="btn btn-success text-white" onclick="terminar_compra()">Terminar Compra</button>
                </div>
            </div>
        </div>
    </div>




    
   <!-- Ventana modal para añadir nuevo registro -->
   <div class="modal fade" id="modalNuevaDC" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Seleccionar Producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_agregar_dc">
                        <div class='mb-3'>
                            <label for='select_producto' class='form-label'>Producto:</label>
                            <select name="select_producto" id="select_producto" class="select obligatorio" onchange="llenar_select_tallas()"></select>
                        </div>
                        <div class='mb-3'>
                            <label for='select_talla' class='form-label'>Talla:</label>
                            <select name="select_talla" id="select_talla" class="select obligatorio"></select>
                        </div>
                        <div class='mb-3'>
                            <label for='dc_cantidad' class='form-label'>Cantidad:</label>
                            <input type="number" value=1 name="dc_cantidad" id="dc_cantidad" class="select obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='dc_cantidad' class='form-label'>Precio unitario: </label>
                            <input disabled type="text" name="dc_precio_unitario" id="dc_precio_unitario" class="form-control obligatorio"></input>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick='agregar_dc()'>Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Ventana modal para editar registro -->
    <div class="modal fade" id="modalEditarDC" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editar_id_dc" name="editar_id_dc" value="">
                    <form id="form_editar_dc">
                        <div class='mb-3'>
                            <label for='editar_dc_producto' class='form-label'>Producto:</label>
                            <input disabled name="editar_dc_producto" id="editar_dc_producto" class="form-control obligatorio">
                        </div>
                        <div class='mb-3'>
                            <label for='editar_select_talla' class='form-label'>Talla:</label>
                            <select name="editar_select_talla" id="editar_select_talla" class="select obligatorio"></select>
                        </div>
                        <div class='mb-3'>
                            <label for='editar_dc_cantidad' class='form-label'>Cantidad:</label>
                            <input type="number" value=1 name="editar_dc_cantidad" id="editar_dc_cantidad" class="select obligatorio"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='editar_dc_precio_unitario' class='form-label'>Precio unitario: </label>
                            <input disabled type="text" name="editar_dc_precio_unitario" id="editar_dc_precio_unitario" class="form-control obligatorio"></input>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="editar_dc()">Guardar</button>
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
            
            llenar_select_proveedores();
            llenar_select_empleados();
            llenar_select_productos();
        });


         // Función para capturar el ID del registro seleccionado
         $('#modalEliminarDC').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            // console.log(id);
            modal.find('#eliminar_id_dc').val(id);
        });

        // Función para capturar el ID del registro seleccionado
        $('#modalEditarDC').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            // console.log(id);
            modal.find('#editar_id_dc').val(id);
        });
     </script>
</body>
</html>