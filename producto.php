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
    include('main-header.php'); 


    if(isset($_GET['id_producto'])){
        $id_producto = $_GET['id_producto'];

        if(!is_numeric($id_producto)){
            header('Location: ../index');
            exit;
        }



        $query1 = "SELECT COUNT(*) AS existe FROM productos WHERE id_producto = $id_producto";
        $existe_producto = GetValueSQL($query1, 'existe');

        if($existe_producto == 0){
            header('Location: ../index');
            exit;
        }
    } else{
        header('Location: index');
        exit;
    }


    ?>

    <!--=====================================
			#region Titulos de tablas
	======================================-->  
    <?php 
    $query2 = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $nombre_producto = GetValueSQL($query2, 'nombre_producto');
    $descripcion = GetValueSQL($query2, 'descripcion');
    $precio = GetValueSQL($query2, 'precio');
    $stock = GetValueSQL($query2, 'stock');
    $categoria = GetValueSQL($query2, 'categoria');
    $status = GetValueSQL($query2, 'status');




    
    ?>
    
    
    <div class="container">
        <h1 class="mb-4">Punto de Venta de Tenis</h1>
        <h2><?php echo $nombre_producto; ?></h2>
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h5 class="mt-4">Descripción del producto</h5>
                        <p><?php echo $descripcion; ?></p>
                    </div>
                    <div class="col-12">
                        <h5 class="mt-2">Precio</h5>
                        <p>$<?php echo number_format($precio, 2); ?></p>
                    </div>
                    <div class="col-12">
                        <h5 class="mt-2">Stock</h5>
                        <p><?php echo $stock; ?></p>
                    </div>
                    <div class="col-12">
                        <h5 class="mt-2">Categoría</h5>
                        <p><?php echo $categoria; ?></p>
                    </div>
                    <div class="col-12">
                        <h5 class="mt-2">Estado</h5>
                        <p><?php echo $status == 1 ? 'Activo' : 'Inactivo'; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <h3 class="mt-3">Tallas</h3> 
                <div class="table-responsive" id="div_tabla_tallas">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Talla</th>
                                <th scope="col">Status</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query3 = "SELECT COUNT(*) AS cuantos FROM producto_talla
                            INNER JOIN tallas ON producto_talla.id_talla = tallas.id_talla
                            WHERE id_producto = $id_producto";
                            $cuantos = GetValueSQL($query3, 'cuantos');

                            if($cuantos > 0){
                                $query4 = "SELECT producto_talla.*, tallas.talla 
                                FROM producto_talla 
                                INNER JOIN tallas ON producto_talla.id_talla = tallas.id_talla 
                                WHERE id_producto = $id_producto 
                                ORDER BY tallas.id_talla";
                                $tallas = DatasetSQL($query4);

                                while($row4 = mysqli_fetch_array($tallas)){
                                    $id_producto_talla = $row4['id_producto_talla'];
                                    $talla = $row4['talla'];
                                    $status = $row4['status_producto_talla'];

                                    if($status == 1){
                                        $status = "Activo";
                                        $icon = "<a href='' onclick='cambiar_status_talla($id_producto_talla, 0, event)'><i class='fas fa-toggle-on fa-lg text-success'></i></a>";
                                    } else{
                                        $status = "Inactivo";
                                        $icon = "<a href='' onclick='cambiar_status_talla($id_producto_talla, 1, event)'><i class='fas fa-toggle-off fa-lg text-secondary'></i></a>";
                                    }
                                    
                                    echo "<tr>
                                        <td>$talla</td>
                                        <td>$status</td>
                                        <td>$icon</td>
                                    </tr>";
                                    
                                }
                            }

                            ?>
                        </tbody>
                    </table><a class="btn btn-success text-white"  data-bs-toggle='modal' data-bs-target='#modalAgregarTalla' onclick="llenar_select_producto_tallas(<?php echo $id_producto; ?>, event)">Agregar talla</a>
                </div>
            </div>
            
        </div>
    </div>

<!-- Ventana modal para añadir nueva talla -->
<div class="modal fade" id="modalAgregarTalla" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar talla</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="form_agregar_talla">
                        <div class='mb-3'>
                            <label for='add_nombre_producto' class='form-label'>Nombre del Producto:</label>
                            <input disabled type="text"name="add_nombre_producto" id="add_nombre_producto" class="form-control" value="<?php echo $nombre_producto; ?>"></input>
                        </div>
                        <div class='mb-3'>
                            <label for='select_talla' class='form-label'>Talla: </label>
                            <select name="select_talla" id="select_talla" class="form-control obligatorio"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick='agregar_producto_talla(<?php echo $id_producto; ?>)'>Guardar</button>
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

     </script>
</body>
</html>