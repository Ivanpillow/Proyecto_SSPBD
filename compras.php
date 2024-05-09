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

    <!--=====================================
        #region Titulos de tabla
    ======================================-->
        
    <div class="container">
        <h1>Punto de Venta de Tenis</h1>
        <h2>&nbspCompras</h2>
        <div class="container">
            <a class="btn btn-success text-white" href="nueva-venta">Nueva venta</a>
            <div class="row justify-content-center">
                <div class="col-8">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID Compra</th>
                                <th scope="col">Proveedor</th>
                                <th scope="col">Empleado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_compras">

                        <!--=====================================
                            #region Detalles de la compra
                        ======================================-->

                            <?php

                            $query1 = "SELECT COUNT(*) AS existe FROM compras 
                            INNER JOIN proveedores ON compras.id_proveedor = proveedores.id_proveedor
                            INNER JOIN empleados ON compras.id_empleado = empleados.id_empleado";
                            // echo $query1;
                            $existe = GetValueSQL($query1, 'existe');

                            $total_vendido = 0;
                            
                            if($existe > 0){

                                $query4 = "SELECT SUM(compras.total_compra) AS total_vendido FROM compras";
                                $total_vendido = GetValueSQL($query4, 'total_vendido');

                                $query_vista = "CREATE VIEW compras AS
                                SELECT cmp.*, prove.nombre_proveedor, prove.direccion, prove.telefono, prove.cantidadReestock, empl.nombre_empleado
                                FROM compras cmp
                                INNER JOIN proveedores prove ON cmp.id_proveedor = prove.id_proveedor
                                INNER JOIN empleados empl ON cmp.id_empleado = empl.id_empleado
                                ORDER BY cmp.fecha DESC";

                                $query2 = "SELECT * FROM vista_compras";
                                // echo $query2;
                                $result2 = DatasetSQL($query2);

                                while($row2 = mysqli_fetch_array($result2)){
                                    $id_compra = $row2['id_compra'];
                                    $id_proveedor = $row2['id_proveedor'];
                                    $nombre_proveedor = $row2['nombre_proveedor'];
                                    $nombre_empleado = $row2['nombre_empleado'];
                                    $fecha = $row2['fecha'];
                                    $total_compra = $row2['total_compra'];


                                    echo "<tr>";
                                    echo "<td>$id_compra</td>";
                                    echo "<td><a type='button' href='proveedor/$id_proveedor'>$nombre_proveedor</a></td>";
                                    echo "<td>$nombre_empleado</td>";
                                    echo "<td>$fecha</td>";
                                    echo "<td>$".number_format($total_compra, 2)."</td>";
                                    echo "<td><a type='button' href='' onclick='ver_detalles_compra($id_compra, event)'>Ver detalles</a></td>";
                                    echo "</tr>";
                                    
                                    ?>

                                    <!--=====================================
                                            #region Detalles de la compra
                                    ======================================-->

                                    <!-- Falta hacer las funciones al final en el controler para las compras -->
                                    <tr id="detalles_compra_<?php echo $id_compra; ?>" style='display: none;'>
                                        <td colspan='6'>
                                            <ul>
                                                
                                                <?php 

                                                $query_vista_compras = "CREATE VIEW DetallesCompras AS
                                                SELECT cmp.id_compra, dc.id_detalle_compra, dc.id_producto, p.nombre_producto, dc.id_producto_talla, pt.id_talla, t.talla, dc.cantidad, dc.precio_unitario, dc.subtotal
                                                FROM compras cmp
                                                INNER JOIN detalles_compras dc ON cmp.id_compra = dc.id_compra
                                                INNER JOIN producto_talla pt ON dc.id_producto_talla = pt.id_producto_talla
                                                INNER JOIN tallas t ON pt.id_talla = t.id_talla
                                                INNER JOIN productos p ON dc.id_producto = p.id_producto";


                                                $query3 = "SELECT * FROM DetallesCompras WHERE id_compra = $id_compra";
                                                $detalles_compras = DatasetSQL($query3);
                                
                                                // Imprimir los detalles de la compra
                                                while($row3 = mysqli_fetch_array($detalles_compras)){
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

                    <!--=====================================
                        #region Total y ordenamiento
                    ======================================-->

                    <h5 id="total_vendido">Total vendido: $<?php echo number_format($total_vendido, 2) ?></h5>
                </div>
                <div class="col-4">
                    <h4 class="text">Ordenar por:</h4>
                    <select id="select_compras" class="select" onchange="llenar_tabla_ventas()">
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