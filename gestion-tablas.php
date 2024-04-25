<!--  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Registros</title>
    <link rel="stylesheet" href="estilos4.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="body">
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
                        <a class="nav-link active" aria-current="page" href="gestion-tablas">Gestión de Tablas</a>
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

        <div class="row justify-content-center">
            <div class="col-6">
                <form action="crear_bd.php" method="POST" class="form">
                    <h4>Agregar Tabla</h4>
                    <input type="hidden" name="accion" value="agregar_tabla">
                    <div class="form-group">
                        <label for="name_table">Tabla:</label>
                        <input type="text" name="name_table" id="name_table" class="input-text" required>
                    </div>
                    <div class="form-group">
                        <label for="campo_id">Campo ID:</label>
                        <input type="text" name="campo_id" id="campo_id" class="input-text" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Guardar" class="btn-submit">
                    </div>
                </form>

                <hr>

                <form action="crear_bd.php" method="POST" class="form">
                    <h4>Borrar Tabla</h4>
                    <input type="hidden" name="accion" value="borrar_tabla">
                    <div class="form-group">
                        <label for="name_table">Tabla:</label>
                        <input type="text" name="name_table" id="name_table" class="input-text" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Borrar" class="btn-delete">
                    </div>
                </form>

                <hr>
            </div>
        </div>

        

        
        <a href="modificar-tablas">Modificar Tablas</a>

    </div>
    
    <div class="container">
        <h2>Tablas Existentes</h2>
        <table border='1' class="table">
            <tr>
                <th>Tabla</th>
                <th>Columna</th>
                <th>Tipo de Dato</th>
                <th>Llave Primaria</th>
            </tr>
            <?php
            $query = "SHOW TABLES";
            $result = DatasetSQL($query);

            while($row = mysqli_fetch_array($result)){
                $table = $row[0];
        
                $query_table_info = "DESCRIBE $table";
                $result_table_info = DatasetSQL($query_table_info);
        
                echo "<tr><td rowspan='" . $result_table_info->num_rows . "'>$table</td>";
        
                while ($row_table_info = mysqli_fetch_array($result_table_info)){
                    echo "<td>" . $row_table_info['Field'] . "</td>";
                    echo "<td>" . $row_table_info['Type'] . "</td>";
        
                    if($row_table_info['Key'] == "PRI"){
                        echo "<td>Yes</td>";
                    } else{
                        echo "<td>No</td>";
                    }
        
                    echo "</tr><tr>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>


    <script src="script.js"></script>
     <!-- Incluir Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>