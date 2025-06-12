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
    <link href="https://fonts.cdnfonts.com/css/wallpoet" rel="stylesheet">
                            
      <!-- Navbar content -->
    </nav>
    <title>Daniela</title> 
</head>
<body>

<nav class="navbar navbar-light" style="background-color:  rgb(236, 40, 210);">
  <div class="container">
    <a class="navbar-brand" href="index.html" style="color:white;">INICIO</a>

    <div class="collaspe navbar-collaspe" id="navbarNavDropdown">
    <ul class="nav navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="index.html" href="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false" style="color: white;">unidad 1</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="http://localhost/papeleria/Grupo4BProg/dani1.php/index.html">practica1</a><br>
          <a class="dropdown-item" href="http://localhost/papeleria/Grupo4BProg/dani2.php/index.html">practica2</a><br>
          <a class="dropdown-item" href="http://localhost/papeleria/Grupo4BProg/dani3.php/index.html">practica3</a><br>
        </div>
      </li>
    </ul>
    <ul class="nav navbar-nav">
    <li class="nav-item dropdown">
     
                <a class="nav-link dropdown-toggle" href="index.html" href="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" style="color: white;">unidad 2</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="http://localhost/papeleria/Grupo4BProg/dani4.php/index.html">practica4</a><br>
                  <a class="dropdown-item" href="http://localhost/papeleria/Grupo4BProg/daniela.php/index.html">Practica de tienda</a><br>
                  <a class="dropdown-item" href="http://localhost/papeleria/Grupo4BProg/dani5.php/index.html">practica5</a><br>
                  <a class="dropdown-item" href="http://localhost/papeleria/Grupo4BProg/dani5a.php/index.html">Practica 5a</a><br>
                  <a class="dropdown-item" href="///">practica6</a>
               
              </li>
            </ul>
                    <ul class="nav navbar-nav">
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="index.html" href="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" style="color: white;">unidad 3</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <a class="dropdown-item" href="/practica7.html">practica7</a><br>
                          <a class="dropdown-item" href="/practica8.html">practica8</a><br>
                          <a class="dropdown-item" href="/practica9.html">practica9</a><br>
                          <a class="dropdown-item" href="/practica10.html">practicafinal</a>
                        </li>
                        </ul>
                        </div>
                        </div>
</div>
<div class="jumbotron"> 
<h1 class="display-4"  style="text-align: center; color: black;font-family: 'Atomic Kittens', sans-serif;">AÑADIR DATOS</h1> 
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$servername = "localhost";
$username = "root";
$password = "";
$database = "cetis131d";

// Conexión a la base de datos
$conexion = new mysqli($servername, $username, $password, $database);
if ($conexion->connect_error) {
    die("La conexión falló: " . $conexion->connect_error);
}

function InsertarAlumno($conexion)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validar que los campos no estén vacíos
        $campos_requeridos = ["numero_control", "nombre", "apellido_paterno", "apellido_materno", "edad", "colonia", "especialidad", "genero", "correo", "telefono", "fecha_ingreso"];
        foreach ($campos_requeridos as $campo) {
            if (empty($_POST[$campo])) {
                echo "<p class='error'>Error: El campo $campo es obligatorio.</p>";
                return;
            }
        }

        // Preparar y ejecutar la consulta de inserción
        $stmt = $conexion->prepare("INSERT INTO alumnos (numero_control, nombre, apellido_paterno, apellido_materno, edad, colonia, especialidad, genero, correo, telefono, fecha_ingreso) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssissssss", $_POST["numero_control"], $_POST["nombre"], $_POST["apellido_paterno"], $_POST["apellido_materno"], $_POST["edad"], $_POST["colonia"], $_POST["especialidad"], $_POST["genero"], $_POST["correo"], $_POST["telefono"], $_POST["fecha_ingreso"]);

        if ($stmt->execute()) {
            echo "<p class='success'>Nuevo alumno agregado correctamente.</p>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<p class='error'>Error al agregar el alumno: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

InsertarAlumno($conexion);

// Obtener la lista de alumnos
$sql_alumnos = "SELECT * FROM alumnos";
$result_alumnos = $conexion->query($sql_alumnos);
$sql = "SELECT 
            a.numero_control,
            a.nombre,
            a.apellido_paterno,
            a.apellido_materno,
            e.edad AS edades,          -- Mostrar la edad real
            c.colonia AS colonia,            -- Mostrar el nombre de la colonia
            es.especialidad AS especialidad,      -- Mostrar la especialidad
            g.genero AS genero,        -- Mostrar el género
            a.correo,
            a.telefono,
            a.fecha_ingreso
        FROM alumnos a
        JOIN edades e ON a.edad = e.id
        JOIN colonia c ON a.colonia = c.id
        JOIN especialidad es ON a.especialidad = es.id
        JOIN genero g ON a.genero = g.id";

$result_alumnos = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alumnos</title>
</head>
<body>

<div class="container">
    <h2>Registrar Alumno</h2>
    <form method="POST">
        <label>Número de Control:</label>
        <input type="text" name="numero_control" required><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label>Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" required><br>

        <label>Apellido Materno:</label>
        <input type="text" name="apellido_materno" required><br>

        <label>Edad:</label>
        <input type="number" name="edad" required><br>

        <label>Colonia:</label>
        <input type="text" name="colonia" required><br>

        <label>Especialidad:</label>
        <input type="text" name="especialidad" required><br>

        <label>Género:</label>
        <input type="text" name="genero" required><br>

        <label>Correo:</label>
        <input type="email" name="correo" required><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" required><br>

        <label>Fecha de Ingreso:</label>
        <input type="date" name="fecha_ingreso" required><br>

        <input type="submit" value="Agregar Alumno">
    </form>
</div>

<div class="container">
    <h2>Lista de Alumnos Registrados</h2>
    <table>
        <tr>
            <th>Número de Control</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Edad</th>
            <th>Colonia</th>
            <th>Especialidad</th>
            <th>Género</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Fecha de Ingreso</th>
        </tr>
        <?php
        if ($result_alumnos->num_rows > 0) {
            while ($row = $result_alumnos->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['numero_control']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['apellido_paterno']}</td>
                        <td>{$row['apellido_materno']}</td>
                        <td>{$row['edades']}</td>
                        <td>{$row['colonia']}</td>
                        <td>{$row['especialidad']}</td>
                        <td>{$row['genero']}</td>
                        <td>{$row['correo']}</td>
                        <td>{$row['telefono']}</td>
                        <td>{$row['fecha_ingreso']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No hay alumnos registrados.</td></tr>";
        }
        ?>
    </table>
</div>