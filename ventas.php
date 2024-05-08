<?php 
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link rel="stylesheet" href="estilos4.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap 4 -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->
	<link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"> <!-- Swal -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Swal -->
</head>
<body style="background-color: ;">

    <?php
    
	require_once "include/functions.php";
	require_once "include/db_tools.php";  
    include('main-header.php'); 

    ?>

    
        
    <div class="container">
        <h1>Punto de Venta de Tenis</h1>
        <h2>Ventas</h2>
        <div class="container">
            <a class="btn btn-success text-white" href="nueva-venta">Nueva venta</a>
            <div class="row justify-content-center">
                <div class="col-8">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID Venta</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Empleado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_ventas">
                            <?php

                            $query1 = "SELECT COUNT(*) AS existe FROM ventas 
                            INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente
                            INNER JOIN empleados ON ventas.id_empleado = empleados.id_empleado";
                            // echo $query1;
                            $existe = GetValueSQL($query1, 'existe');

                            $total_vendido = 0;
                            
                            if($existe > 0){

                                $query4 = "SELECT SUM(ventas.total_venta) AS total_vendido FROM ventas";
                                $total_vendido = GetValueSQL($query4, 'total_vendido');

                                $query_vista = "CREATE VIEW Ventas AS
                                SELECT v.*, c.nombre_cliente, c.email, c.direccion, e.nombre_empleado
                                FROM ventas v
                                INNER JOIN clientes c ON v.id_cliente = c.id_cliente
                                INNER JOIN empleados e ON v.id_empleado = e.id_empleado
                                ORDER BY v.fecha DESC";

                                $query2 = "SELECT * FROM vista_ventas";
                                // echo $query2;
                                $result2 = DatasetSQL($query2);

                                while($row2 = mysqli_fetch_array($result2)){
                                    $id_venta = $row2['id_venta'];
                                    $id_cliente = $row2['id_cliente'];
                                    $nombre_cliente = $row2['nombre_cliente'];
                                    $nombre_empleado = $row2['nombre_empleado'];
                                    $fecha = $row2['fecha'];
                                    $total_venta = $row2['total_venta'];


                                    echo "<tr>";
                                    echo "<td>$id_venta</td>";
                                    echo "<td><a type='button' href='cliente/$id_cliente'>$nombre_cliente</a></td>";
                                    echo "<td>$nombre_empleado</td>";
                                    echo "<td>$fecha</td>";
                                    echo "<td>$".number_format($total_venta, 2)."</td>";
                                    echo "<td><a type='button' href='' onclick='ver_detalles_venta($id_venta, event)'>Ver detalles</a></td>";
                                    echo "</tr>";
                                    
                                    ?>

                                    <!-- Detalles de la venta -->
                                    <tr id="detalles_venta_<?php echo $id_venta; ?>" style='display: none;'>
                                        <td colspan='6'>
                                            <ul>
                                                
                                                <?php 

                                                $query_vista_ventas = "CREATE VIEW DetallesVenta AS
                                                SELECT v.id_venta, dv.id_detalle_venta, dv.id_producto, p.nombre_producto, dv.id_producto_talla, pt.id_talla, t.talla, dv.cantidad, dv.precio_unitario, dv.subtotal
                                                FROM ventas v
                                                INNER JOIN detalles_ventas dv ON v.id_venta = dv.id_venta
                                                INNER JOIN producto_talla pt ON dv.id_producto_talla = pt.id_producto_talla
                                                INNER JOIN tallas t ON pt.id_talla = t.id_talla
                                                INNER JOIN productos p ON dv.id_producto = p.id_producto";


                                                $query3 = "SELECT * FROM DetallesVenta WHERE id_venta = $id_venta";
                                                $detalles_ventas = DatasetSQL($query3);
                                
                                                // Imprimir los detalles de la venta
                                                while($row3 = mysqli_fetch_array($detalles_ventas)){
                                                    $nombre_producto = $row3['nombre_producto'];
                                                    $talla = $row3['talla'];
                                                    $cantidad = $row3['cantidad'];
                                                    $precio_unitario = $row3['precio_unitario'];
                                                    $subtotal = $row3['subtotal'];
                                
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
                    <?php ?>
                    <h5 id="total_vendido">Total vendido: $<?php echo number_format($total_vendido, 2) ?></h5>
                </div>
                <div class="col-4">
                    <h4 class="text">Ordenar por:</h4>
                    <select id="select_ventas" class="select" onchange="llenar_tabla_ventas()">
                            <option value="1">Todos los tiempos</option>
                            <?php
                            // Obtener la fecha actual
                            $fecha_actual = date("Y-m-d");
                            
                            // Generar opciones para meses pasados
                            for ($i = 1; $i <= 12; $i++) {
                                $fecha_mes_pasado = date("Y-m-d", strtotime("-$i month", strtotime($fecha_actual)));
                                $nombre_mes_pasado = date("F Y", strtotime("-$i month", strtotime($fecha_actual)));
                                echo "<option value='$fecha_mes_pasado'>$nombre_mes_pasado</option>";
                            }
                            
                            ?>
                    </select>
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
</body>
</html>