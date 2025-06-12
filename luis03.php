<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username   = "root";
$password   = "";
$servername = "localhost";
$database   = "music_db";

$conexion = new mysqli($servername, $username, $password, $database);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo'])) {
    $titulo      = $conexion->real_escape_string($_POST["titulo"]);
    $artista_id  = $conexion->real_escape_string($_POST["artista_id"]);
    $anio        = $conexion->real_escape_string($_POST["anio"]);
    $descripcion = $conexion->real_escape_string($_POST["descripcion"]);
    $portada     = $conexion->real_escape_string($_POST["portada"]);

    $sql_insert = "INSERT INTO albumes (titulo, artista_id, anio, descripcion, portada) 
                   VALUES ('$titulo', '$artista_id', '$anio', '$descripcion', '$portada')";
    if ($conexion->query($sql_insert) === TRUE) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p class='error'>Error al agregar el álbum: " . $conexion->error . "</p>";
    }
}

$sql = "SELECT albumes.titulo, artistas.nombre AS artista, albumes.anio, albumes.descripcion, albumes.portada
        FROM albumes 
        JOIN artistas ON albumes.artista_id = artistas.id";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <link href="https://fonts.cdnfonts.com/css/no-mystery" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/diablo" rel="stylesheet">
    <title>Luis Ángel 'Index</title>
    <style>
        .page-title{

            font-family: 'Blacklisted';
            src: url('fonts/Blacklisted.ttf') format('truetype');

        }
        .scrollable-table-container {
            width: 100%;
            height: 400px;
            overflow-y: auto; 
            border: 1px solid #ddd;
        }
        .page-title {
            font-family: 'Blacklisted', Arial, sans-serif;
            color: white;
        }
        @font-face {
            font-family: 'Blacklisted';
            src: url('fonts/Blacklisted.ttf') format('truetype');
        }
        body {
            background-image: url('https://i.etsystatic.com/5877854/r/il/842306/4743860934/il_fullxfull.4743860934_l6ix.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            transition: background-image 0.5s ease-in-out;
            color: white;
            margin: 0;
            padding: 0;
        }
        .table-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            flex-direction: column;
            overflow-y: auto;
            padding: 20px;
        }
        table {
            border: 1px solid white;
            border-collapse: collapse;
            width: 100%;
            max-width: 1200px;
            background-color: rgba(0, 0, 0, 0.3);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid white;
            padding: 12px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.3);
        }
        th {
            background: linear-gradient(-45deg, #000000, #36223f);
            background-size: 400% 400%;
            animation: gradient 8s ease infinite;
            color: white;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.64);
        }
        .navbar-light {
            background: linear-gradient(-45deg, #000000, #36223f);
            background-size: 400% 400%;
            animation: gradient 8s ease infinite;
        }
        .section-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-family: 'Blacklisted', Arial, sans-serif;
            color: white;
        }
        .page-title {
            text-align: center;
            color: white;
            font-family: 'so this is it', sans-serif;
            text-shadow: 0 1 1 black;
        }
        .table-container {
            background-image: url('https://i.etsystatic.com/5877854/r/il/842306/4743860934/il_fullxfull.4743860934_l6ix.jpg');
            background-repeat: repeat;
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            transition: background-image 0.5s ease-in-out;
            color: white;
        }
        .jumbotron {
            background: linear-gradient(-45deg, rgba(143, 143, 143, 0.48), rgba(135, 101, 153, 0.51));
            background-size: 400% 400%;
            animation: gradient 4s ease infinite;
            color: white;
            padding-bottom: 40px;
        }

        .form-container {
            text-align: center;
            margin-top: 20px;
        }
        .form-group-inline {
            display: inline-block;
            margin: 10px;
        }
        .form-group-inline label {
            margin-right: 5px;
        }        
        .btn-primary {
            background: linear-gradient(-45deg, #000000, #36223f);
            background-size: 400% 400%;
            animation: gradient 8s ease infinite;        
        border: white;
        }
    </style>
</head>
<body>
    <!-- Menú de navegación -->
    <nav class="navbar navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.html" style="color:white;">INICIO</a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="nav navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white;">UNIDAD 1</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="l01.php">Practica 1</a><br>
                            <a class="dropdown-item" href="l02.php">Practica 2</a><br>
                            <a class="dropdown-item" href="l03.php">Practica 3</a>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="unidad1.html" id="navbarDropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;">UNIDAD 2</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="l04.html">Practica 4</a><br>
                            <a class="dropdown-item" href="l05.html">Practica 5</a><br>
                            <a class="dropdown-item" href="l06.html">Practica 6</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="unidad1.html" id="navbarDropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;">UNIDAD 3</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="l07.html">Practica 7</a><br>
                            <a class="dropdown-item" href="l08.html">Practica 8</a><br>
                            <a class="dropdown-item" href="l09.html">Practica 9</a><br>
                            <a class="dropdown-item" href="l10.html">Practica Final</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="jumbotron">
        <h1 class="page-title">AGREGAR DATOS</h1>
        <?php echo "Conexión exitosa a la base de datos de música"; ?>
        
        <div class="form-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="form-group-inline">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                </div>
                <div class="form-group-inline">
                    <label for="artista_id">ID Artista:</label>
                    <input type="number" name="artista_id" id="artista_id" class="form-control" required>
                </div>
                <div class="form-group-inline">
                    <label for="anio">Año:</label>
                    <input type="number" name="anio" id="anio" class="form-control" required>
                </div>
                <div class="form-group-inline">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control" required>
                </div>
                <div class="form-group-inline">
                    <label for="portada">Portada (URL):</label>
                    <input type="text" name="portada" id="portada" class="form-control" required>
                </div>
                <div class="form-group-inline">
                    <button type="submit" class="btn btn-primary">Agregar Álbum</button>
                </div>
            </form>
        </div>

        <div class="container table-container">
            <h1 class="section-title">Albumes</h1>
            <?php if ($resultado && $resultado->num_rows > 0) : ?>
                <table>
                    <tr>
                        <th>Título</th>
                        <th>Artista</th>
                        <th>Año</th>
                        <th>Descripción</th>
                        <th>Portada</th>
                    </tr>
                    <?php while ($fila = $resultado->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $fila['titulo']; ?></td>
                            <td><?php echo $fila['artista']; ?></td>
                            <td><?php echo $fila['anio']; ?></td>
                            <td><?php echo $fila['descripcion']; ?></td>
                            <td><img src="/<?php echo $fila['portada']; ?>" alt="Portada del álbum" width="100"></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No se encontraron álbumes en la base de datos.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php $conexion->close(); ?>
