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
<body id="body">

    <?php
    
	require_once "include/functions.php";
	require_once "include/db_tools.php";  
    include('main-header.php');

    if(isset($_GET['proveedor'])){
        $id_proveedor = $_GET['proveedor'];

        $query1 = "SELECT COUNT(*) AS existe FROM proveedores WHERE id_proveedor = '$id_proveedor'";
        $existe_proveedor = GetValueSQL($query1, 'existe');

        if($existe_proveedor == 0){
            header('Location: ../index');
            exit;
        }
    } else{
        header('Location: index');
        exit;
    }


    ?>


    <?php 

    $query1 = "SELECT COUNT(*) AS cuantas_compras FROM proveedores 
    INNER JOIN compras ON proveedores.id_proveedor = compras.id_proveedor 
    WHERE proveedores.id_proveedor = $id_proveedor";

    $cuantas_compras = GetValueSQL($query1, 'cuantas_compras');
    if($cuantas_compras > 0)
    {
        $query2 = "SELECT proveedores.nombre_proveedor, 
                  proveedores.direccion, 
                  proveedores.telefono, 
                  SUM(compras.total_compra) AS suma_total_compras 
           FROM proveedores 
           INNER JOIN compras ON proveedores.id_proveedor = compras.id_proveedor 
           WHERE proveedores.id_proveedor = $id_proveedor";

        $nombre_proveedor = GetValueSQL($query2, 'nombre_proveedor');
        $direccion = GetValueSQL($query2, 'direccion');
        $telefono = GetValueSQL($query2, 'telefono');
        $suma_total_compras = GetValueSQL($query2, 'suma_total_compras');
    } else{
        $query2 = "SELECT * FROM proveedores WHERE id_proveedor = $id_proveedor";

        $nombre_proveedor = GetValueSQL($query2, 'nombre_proveedor');
        $direccion = GetValueSQL($query2, 'direccion');
        $telefono = GetValueSQL($query2, 'telefono');
        $suma_total_compras = 0;
    } 
    
    ?>
        
        <div class="container" id="container_tabla">
            <h1>Registro de Compras de Tenis</h1>
            <h2>Proveedor: <?php echo $nombre_proveedor; ?></h2>
            <div class="container">
                <div class="row justify-content-center mt-3">
                    <div class="col-xl-12">
                        <strong>Dirección: </strong><?php echo $direccion; ?><br>
                        <strong>Teléfono: </strong><?php echo $telefono; ?><br>
                        <strong>Total Compras Realizadas: </strong><?php echo $cuantas_compras; ?><br>
                        <strong>Subtotal: </strong> $<?php echo number_format($suma_total_compras, 2); ?><br>
                    </div>

                    <br><br>
                    <div class="col-xl-12 mt-4">
                        <table border='1' class="table table-striped" id="table_compras_proveedor">
                            <thead>
                                <tr>
                                    <th scope="col">ID Compra</th>
                                    <th scope="col">Empleado</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla_compras_proveedor">
                                <?php 
                                $query3 = "SELECT COUNT(*) AS cuantos FROM compras WHERE id_proveedor = $id_proveedor";
                                $cuantas_compras = GetValueSQL($query3, 'cuantos');

                                if($cuantas_compras > 0){
                                    $query4 = "SELECT * FROM compras
                                    INNER JOIN empleados ON compras.id_empleado = empleados.id_empleado
                                    WHERE id_proveedor = $id_proveedor
                                    ORDER BY fecha DESC";
                                    $compras_proveedor = DatasetSQL($query4);

                                    while($row4 = mysqli_fetch_array($compras_proveedor)){
                                        $id_compra = $row4['id_compra'];
                                        $nombre_empleado = $row4['nombre_empleado'];
                                        $fecha = $row4['fecha'];
                                        $total_compra = $row4['total_compra'];

                                        echo "<tr>";
                                        echo "<td>".$id_compra."</td>";
                                        echo "<td>".$nombre_empleado."</td>";
                                        echo "<td>".$fecha."</td>";
                                        echo "<td>$".number_format($total_compra, 2)."</td>";
                                        echo "<td><a type='button' href='' onclick='ver_detalles_compra($id_compra, event)'>Ver detalles</a></td>";

                                        echo "</tr>";

                                        ?>

                                    <!-- Detalles de la compra -->
                                <tr id="detalles_compra_<?php echo $id_compra; ?>" style="display: none;">
                                    <td colspan='5'>
                                        <ul>
                                            <?php 
                                            $query5 ="SELECT * FROM compras
                                            INNER JOIN detalles_compras ON compras.id_compra = detalles_compras.id_compra
                                            INNER JOIN productos ON detalles_compras.id_producto = productos.id_producto
                                            WHERE compras.id_compra = $id_compra";
                                            $detalles_compras = DatasetSQL($query5);

                                            // Imprimir los detalles de la compra
                                            while($row5 = mysqli_fetch_array($detalles_compras)){
                                                $nombre_producto = $row5['nombre_producto'];
                                                $id_producto_talla = $row5['id_producto_talla'];
                                                $cantidad = $row5['cantidad'];
                                                $precio_unitario = $row5['precio_unitario'];
                                                $subtotal = $row5['subtotal'];

                                                $query6 = "SELECT * FROM producto_talla
                                                INNER JOIN tallas ON producto_talla.id_talla = tallas.id_talla
                                                WHERE id_producto_talla = $id_producto_talla";
                                                $talla = GetValueSQL($query6, 'talla');

                                                echo "<li><strong>Producto: </strong>$nombre_producto</li>";
                                                echo "<strong>Talla: </strong>$talla<br>";
                                                echo "<strong>Cantidad: </strong>$cantidad<br>";
                                                echo "<strong>Precio Unitario: </strong>$".number_format($precio_unitario, 2)."<br>";
                                                echo "<strong>Subtotal: </strong>$".number_format($subtotal, 2)."<br>";
                                                echo "<br>";
                                            }
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>

                                <?php
                                }
                            }

                            ?>    
                        </tbody>
                    </table>
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