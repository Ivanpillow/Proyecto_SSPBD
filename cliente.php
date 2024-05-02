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

    if(isset($_GET['cliente'])){
        $id_cliente = $_GET['cliente'];

        $query1 = "SELECT COUNT(*) AS existe FROM clientes WHERE id_cliente = '$id_cliente'";
        $existe_cliente = GetValueSQL($query1, 'existe');

        if($existe_cliente == 0){
            header('Location: ../index');
            exit;
        }
    } else{
        header('Location: index');
        exit;
    }


    ?>


    <?php 
    $query_vista_info_cliente = "CREATE VIEW InformacionCliente AS
    SELECT c.nombre_cliente, c.email, c.direccion,
           IFNULL(v.cuantas_ventas, 0) AS cuantas_ventas,
           IFNULL(v.total_ventas, 0) AS total_ventas
    FROM clientes c
    LEFT JOIN (
        SELECT id_cliente, COUNT(*) AS cuantas_ventas, SUM(total_venta) AS total_ventas
        FROM ventas
        GROUP BY id_cliente
    ) v ON c.id_cliente = v.id_cliente";


    $query2 = "SELECT * FROM InformacionCliente WHERE id_cliente = $id_cliente";


    $nombre_cliente = GetValueSQL($query2, 'nombre_cliente');
    $email = GetValueSQL($query2, 'email');
    $direccion = GetValueSQL($query2, 'direccion');
    $cuantas_ventas = GetValueSQL($query2, 'cuantas_ventas');

    if($cuantas_ventas > 0){
        $total_ventas = GetValueSQL($query2, 'total_ventas');
    } else{
        $total_ventas = 0;
    }

    
    ?>
        
    <div class="container" id="container_tabla">
        <h1>Punto de Venta de Tenis</h1>
        <h2>Cliente: <?php echo $nombre_cliente; ?></h2>
        <div class="container">
            <input id="id_cliente" type="hidden" value="<?php echo $id_cliente; ?>">
            <div class="row justify-content-center mt-3">
                <div class="col-xl-12">
                    <strong>Correo Electrónico: </strong><?php echo $email; ?> <br>
                    <strong>Domicilio: </strong><?php echo $direccion; ?><br>
                    <strong>Ventas Realizadas: </strong><?php echo $cuantas_ventas; ?><br>
                    <strong>Total: </strong>$<?php echo number_format($total_ventas, 2); ?><br>
                </div>

                <br><br>
                <div class="col-xl-12 mt-4">
                    <table border='1' class="table table-striped" id="table_ventas_cliente">
                        <thead>
                            <tr>
                                <th scope="col">ID Venta</th>
                                <th scope="col">Empleado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_ventas_cliente">
                            <?php 
                            $query3 = "SELECT COUNT(*) AS cuantos FROM ventas WHERE id_cliente = $id_cliente";
                            $cuantas_ventas = GetValueSQL($query3, 'cuantos');

                            if($cuantas_ventas > 0){
                                $query4 = "SELECT * FROM ventas
                                INNER JOIN empleados ON ventas.id_empleado = empleados.id_empleado
                                WHERE id_cliente = $id_cliente
                                ORDER BY fecha DESC";
                                $ventas_cliente = DatasetSQL($query4);

                                while($row4 = mysqli_fetch_array($ventas_cliente)){
                                    $id_venta = $row4['id_venta'];
                                    $nombre_empleado = $row4['nombre_empleado'];
                                    $fecha = $row4['fecha'];
                                    $total_venta = $row4['total_venta'];

                                    echo "<tr>";
                                    echo "<td>".$id_venta."</td>";
                                    echo "<td>".$nombre_empleado."</td>";
                                    echo "<td>".$fecha."</td>";
                                    echo "<td>$".number_format($total_venta, 2)."</td>";
                                    echo "<td><a type='button' href='' onclick='ver_detalles_venta($id_venta, event)'>Ver detalles</a></td>";

                                    echo "</tr>";

                                    ?>

                                    <!-- Detalles de la venta -->
                                    <tr id="detalles_venta_<?php echo $id_venta; ?>" style='display: none;'>
                                        <td colspan='6'>
                                            <ul>
                                                
                                                <?php 
                                                $query5 ="SELECT * FROM ventas
                                                INNER JOIN detalles_ventas ON ventas.id_venta = detalles_ventas.id_venta
                                                INNER JOIN productos ON detalles_ventas.id_producto = productos.id_producto
                                                INNER JOIN tallas ON productos.id_talla = tallas.id_talla
                                                WHERE ventas.id_venta = $id_venta";
                                                $detalles_ventas = DatasetSQL($query5);
                                
                                                // Imprimir los detalles de la venta
                                                while($row5 = mysqli_fetch_array($detalles_ventas)){
                                                    $nombre_producto = $row5['nombre_producto'];
                                                    $talla = $row5['talla'];
                                                    $cantidad = $row5['cantidad'];
                                                    $precio_unitario = $row5['precio_unitario'];
                                                    $subtotal = $row5['subtotal'];
                                
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