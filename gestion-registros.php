
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas</title>
    <link rel="stylesheet" href="estilos4.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                        <a class="nav-link active" aria-current="page" href="gestion-registros">Gestión de Registros</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
    <div class="body">
        
        <div class="container">
            <h1>Tablas</h1>

            <hr>

            <div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Tabla</th>
                            <th scope="col">N° Columnas</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query1 = "SHOW TABLES";
                        $result = DatasetSQL($query1);

                        while($row = mysqli_fetch_array($result)){
                            $table = $row[0];

                            $query2 = "SHOW COLUMNS FROM $table";
                            $result2 = DatasetSQL($query2);

                            $num_columnas = $result2->num_rows;

                            echo "<tr>";
                            echo "<td>$table</td>";
                            echo "<td>$num_columnas</td>";
                            echo "<td><a href='modificar-registros?tabla=$table' class='btn btn-secondary'>Modificar registros</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            

        </div>
    </div>
    

    <script src="script.js"></script>
     <!-- Incluir Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>