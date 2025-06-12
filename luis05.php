<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username   = "root";
$password   = "";
$servername = "localhost";
$database   = "musica_db";  

$conexion = new mysqli($servername, $username, $password, $database);
if ($conexion->connect_error) {
    die("La conexión falló: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_artista        = $conexion->real_escape_string($_POST["id_artista"]); 
    $nombre_artistico  = $conexion->real_escape_string($_POST["nombre_artistico"]);  
    $apellido_paterno  = $conexion->real_escape_string($_POST["apellido_paterno"]);
    $apellido_materno  = $conexion->real_escape_string($_POST["apellido_materno"]);

    $año_input         = $conexion->real_escape_string($_POST["año_nacimiento"]);
    $ciudad_input      = $conexion->real_escape_string($_POST["ciudad"]);
    $instrumento_input = $conexion->real_escape_string($_POST["instrumento"]);
    $genero_input      = $conexion->real_escape_string($_POST["genero_musical"]);
    $fecha_debut       = $conexion->real_escape_string($_POST["fecha_debut"]);

    $sql_check_year = "SELECT id FROM años_nacimiento WHERE año = '$año_input'";
    $result_year = $conexion->query($sql_check_year);
    if(!$result_year){
        die("Error al verificar el año: " . $conexion->error);
    }
    if ($result_year->num_rows > 0) {
        $row_year = $result_year->fetch_assoc();
        $año_id = $row_year['id'];
    } else {
        $sql_insert_year = "INSERT INTO años_nacimiento (año) VALUES ('$año_input')";
        if (!$conexion->query($sql_insert_year)) {
            die("Error al insertar el año: " . $conexion->error);
        }
        $año_id = $conexion->insert_id;
    }

    // ----- Procesar Ciudad -----
    $sql_check_ciudad = "SELECT id FROM ciudades WHERE nombre_ciudad = '$ciudad_input'";
    $result_ciudad = $conexion->query($sql_check_ciudad);
    if(!$result_ciudad){
        die("Error al verificar ciudad: " . $conexion->error);
    }
    if ($result_ciudad->num_rows > 0) {
        $row_ciudad = $result_ciudad->fetch_assoc();
        $ciudad_id = $row_ciudad['id'];
    } else {
        $sql_insert_ciudad = "INSERT INTO ciudades (nombre_ciudad) VALUES ('$ciudad_input')";
        if (!$conexion->query($sql_insert_ciudad)) {
            die("Error al insertar ciudad: " . $conexion->error);
        }
        $ciudad_id = $conexion->insert_id;
    }


    $sql_check_instrumento = "SELECT id FROM instrumentos WHERE nombre_instrumento = '$instrumento_input'";
    $result_instrumento = $conexion->query($sql_check_instrumento);
    if(!$result_instrumento){
        die("Error al verificar instrumento: " . $conexion->error);
    }
    if ($result_instrumento->num_rows > 0) {
        $row_instrumento = $result_instrumento->fetch_assoc();
        $instrumento_id = $row_instrumento['id'];
    } else {
        $sql_insert_instrumento = "INSERT INTO instrumentos (nombre_instrumento) VALUES ('$instrumento_input')";
        if (!$conexion->query($sql_insert_instrumento)) {
            die("Error al insertar instrumento: " . $conexion->error);
        }
        $instrumento_id = $conexion->insert_id;
    }

    $sql_check_genero = "SELECT id FROM generos_musicales WHERE nombre_genero = '$genero_input'";
    $result_genero = $conexion->query($sql_check_genero);
    if(!$result_genero){
        die("Error al verificar género musical: " . $conexion->error);
    }
    if ($result_genero->num_rows > 0) {
        $row_genero = $result_genero->fetch_assoc();
        $genero_id = $row_genero['id'];
    } else {
        $sql_insert_genero = "INSERT INTO generos_musicales (nombre_genero) VALUES ('$genero_input')";
        if (!$conexion->query($sql_insert_genero)) {
            die("Error al insertar género musical: " . $conexion->error);
        }
        $genero_id = $conexion->insert_id;
    }

    $sql_insert = "INSERT INTO artistas (
        id_artista, nombre_artistico, apellido_paterno, apellido_materno,
        id_año_nacimiento, id_ciudad, id_instrumento, id_genero_musical, fecha_debut
    ) VALUES (
        '$id_artista', '$nombre_artistico', '$apellido_paterno', '$apellido_materno',
        '$año_id', '$ciudad_id', '$instrumento_id', '$genero_id', '$fecha_debut'
    )";

    if ($conexion->query($sql_insert) === TRUE) {
        echo "<p class='success'>Nuevo artista agregado con éxito</p>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error al insertar artista: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  
  <link href="https://fonts.cdnfonts.com/css/no-mystery" rel="stylesheet">
  <link href="https://fonts.cdnfonts.com/css/diablc" rel="stylesheet">
  <title>Luis Ángel Index</title>
  <style>
    body {
      background-image: url('https://wallpapers.com/images/hd/black-and-white-music-1600-x-1066-wallpaper-m4g94usortrco3os.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    
    h1, h2 {
      text-align: center;
      color: #ffcc80;
      margin-bottom: 20px;
      font-family: "Georgia", serif;
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
      letter-spacing: 3px;
      text-transform: uppercase;
      font-weight: bold;
    }
    
    .container1 {
      width: 40%;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
      margin: auto;
      background-color: rgba(0, 0, 0, 0.7);
    }
    
    form {
      display: flex;
      flex-direction: column;
    }
    
    label {
      font-size: 15px;
      margin-bottom: 7px;
      color: #a0e6ff;
      font-family: "Palatino Linotype", serif;
      font-weight: bold;
      letter-spacing: 1px;
    }
    
    input[type="text"],
    input[type="number"],
    input[type="date"] {
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid rgba(255, 204, 128, 0.3);
      border-radius: 4px;
      font-size: 15px;
      background-color: rgba(26, 59, 92, 0.7);
      color: #e8f4ff;
      transition: all 0.3s;
      font-family: "Bookman Old Style", serif;
      letter-spacing: 1px;
    }
    
    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus {
      background-color: rgba(255, 255, 255, 0.2);
      outline: none;
    }
    
    input[type="submit"] {
      padding: 14px;
      margin-bottom: 10px;
      border: 2px solid rgba(255, 204, 128, 0.4);
      border-radius: 4px;
      font-size: 15px;
      background-color: rgba(26, 59, 92, 0.8);
      color: #ffcc80;
      cursor: pointer;
      transition: all 0.3s ease;
      font-family: "Century Gothic", sans-serif;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: bold;
    }
    
    input[type="submit"]:hover {
      background-color: rgba(40, 80, 120, 0.8);
      border-color: rgba(255, 204, 128, 0.7);
      box-shadow: 0 0 15px rgba(255, 204, 128, 0.3);
      transform: translateY(-2px);
    }
    
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    
    td {
      padding: 12px;
      text-align: center;
      font-size: 14px;
      letter-spacing: 0.5px;
    }
    
    tr:nth-child(even) {
      background-color: rgba(40, 70, 100, 0.8);
      color: #e8f4ff;
      font-family: "Book Antiqua", serif;
    }
    
    tr:nth-child(odd) {
      background-color: rgba(25, 45, 65, 0.8);
      color: #e8f4ff;
      font-family: "Book Antiqua", serif;
    }
    
    th {
      background-color: rgba(20, 40, 60, 0.9);
      color: #ffcc80;
      font-weight: bold;
      font-family: "Copperplate Gothic", serif;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 14px;
      padding: 15px;
    }
    
    .navbar-dark {
      background-color: rgba(0, 0, 0, 0.7) !important;
    }
    
    .dropdown-menu {
      background-color: rgba(0, 0, 0, 0.8);
    }
    
    .dropdown-item {
      color: white !important;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark" style="background-color: rgba(0, 0, 0, 0.7);">
    <div class="container">
        <a class="navbar-brand" href="index.html" style="color: white; font-weight: bold;">INICIO</a>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="nav navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;">
                        UNIDAD 1
                    </a>
                    <div class="dropdown-menu" style="background-color: rgba(0, 0, 0, 0.8);" aria-labelledby="navbarDropdownMenuLink1">
                        <a class="dropdown-item" href="luis01.php" style="color: white;">Práctica 1</a><br>
                        <a class="dropdown-item" href="l02.php" style="color: white;">Práctica 2</a><br>
                        <a class="dropdown-item" href="luis03.php" style="color: white;">Práctica 3</a><br>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;">
                        UNIDAD 2
                    </a>
                    <div class="dropdown-menu" style="background-color: rgba(0, 0, 0, 0.8);" aria-labelledby="navbarDropdownMenuLink2">
                        <a class="dropdown-item" href="luis04.PHP" style="color: white;">Práctica 4</a><br>
                        <a class="dropdown-item" href="luis05.php" style="color: white;">Práctica 5</a><br>
                        <a class="dropdown-item" href="luis06.html" style="color: white;">Práctica 6</a><br>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;">
                        UNIDAD 3
                    </a>
                    <div class="dropdown-menu" style="background-color: rgba(0, 0, 0, 0.8);" aria-labelledby="navbarDropdownMenuLink3">
                        <a class="dropdown-item" href="luis07.html" style="color: white;">Práctica 7</a><br>
                        <a class="dropdown-item" href="luis08.html" style="color: white;">Práctica 8</a><br>
                        <a class="dropdown-item" href="luis09.html" style="color: white;">Práctica 9</a><br>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container1">
  <form method="POST">
    <label for="id_artista">ID Artista:</label>
    <input type="text" id="id_artista" name="id_artista" required>

    <label for="nombre_artistico">Nombre Artístico:</label>
    <input type="text" id="nombre_artistico" name="nombre_artistico" required>

    <label for="apellido_paterno">Apellido Paterno:</label>
    <input type="text" id="apellido_paterno" name="apellido_paterno" required>

    <label for="apellido_materno">Apellido Materno:</label>
    <input type="text" id="apellido_materno" name="apellido_materno" required>

    <label for="año_nacimiento">Año de Nacimiento:</label>
    <input type="number" name="año_nacimiento" required placeholder="Ej. 2000">

    <label for="ciudad">Ciudad:</label>
    <input type="text" name="ciudad" required placeholder="Ej. Ciudad de México">

    <label for="instrumento">Instrumento:</label>
    <input type="text" name="instrumento" required placeholder="Ej. Guitarra">

    <label for="genero_musical">Género Musical:</label>
    <input type="text" name="genero_musical" required placeholder="Ej. Rock">

    <label for="fecha_debut">Fecha de Debut:</label>
    <input type="text" name="fecha_debut" required placeholder="YYYY-MM-DD">

    <input type="submit" value="Agregar Artista">
  </form>
</div>

<h2>Lista de Artistas</h2>
<table>
  <tr>
    <th>ID Artista</th>
    <th>Nombre Artístico</th>
    <th>Apellido Paterno</th>
    <th>Apellido Materno</th>
    <th>Año de Nacimiento</th>
    <th>Ciudad</th>
    <th>Instrumento</th>
    <th>Género Musical</th>
    <th>Fecha de Debut</th>
  </tr>
  <?php

  $sql = "SELECT
            a.id_artista,
            a.nombre_artistico,
            a.apellido_paterno,
            a.apellido_materno,
            an.año AS año_nacimiento,
            c.nombre_ciudad,
            i.nombre_instrumento,
            gm.nombre_genero AS genero_musical,
            a.fecha_debut
          FROM artistas a
          JOIN años_nacimiento an ON a.id_año_nacimiento = an.id
          JOIN ciudades c ON a.id_ciudad = c.id
          JOIN instrumentos i ON a.id_instrumento = i.id
          JOIN generos_musicales gm ON a.id_genero_musical = gm.id";
  $resultado = $conexion->query($sql);
  if ($resultado && $resultado->num_rows > 0) {
      while ($row = $resultado->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id_artista']}</td>
                  <td>{$row['nombre_artistico']}</td>
                  <td>{$row['apellido_paterno']}</td>
                  <td>{$row['apellido_materno']}</td>
                  <td>{$row['año_nacimiento']}</td>
                  <td>{$row['nombre_ciudad']}</td>
                  <td>{$row['nombre_instrumento']}</td>
                  <td>{$row['genero_musical']}</td>
                  <td>{$row['fecha_debut']}</td>
                </tr>";
      }
  } else {
      echo "<tr><td colspan='9'>No hay artistas registrados</td></tr>";
  }
  ?>
</table>

</body>
</html>
