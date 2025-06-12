<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    session_start();
    
    $username = "root";
    $password = "";
    $servername = "localhost";
    $database = "cetis131";
    
    try {
        $conexion = new mysqli($servername, $username, $password, $database);
        if($conexion->connect_error){
            throw new Exception("La conexión falló: " . $conexion->connect_error);
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
        exit;
    }

    $dropdowns = [
        'edades' => "SELECT id, edad FROM edades",
        'colonias' => "SELECT id, colonias FROM colonias",
        'especialidades' => "SELECT id, especialidades FROM especialidades",
        'generos' => "SELECT id, generos FROM generos"
    ];
    
    $results = [];
    
    foreach ($dropdowns as $key => $sql) {
        $result = $conexion->query($sql);
        if (!$result) {
            echo "<div class='alert alert-danger'>Error al cargar datos de $key: " . $conexion->error . "</div>";
        } else {
            $results[$key] = $result;
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        try {
            $required_fields = ['numero_control', 'nombre', 'apellido_paterno', 'apellido_materno', 
                              'edad', 'colonia', 'especialidad', 'genero', 'correo', 'telefono', 'fecha_ingreso'];
            
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("El campo '$field' es obligatorio y no fue proporcionado.");
                }
            }
            
            $numero_control = $conexion->real_escape_string($_POST["numero_control"]);
            $nombre = $conexion->real_escape_string($_POST["nombre"]);
            $apellido_paterno = $conexion->real_escape_string($_POST["apellido_paterno"]);
            $apellido_materno = $conexion->real_escape_string($_POST["apellido_materno"]);
            $edad = $conexion->real_escape_string($_POST["edad"]);
            $colonia = $conexion->real_escape_string($_POST["colonia"]);
            $especialidad = $conexion->real_escape_string($_POST["especialidad"]);
            $genero = $conexion->real_escape_string($_POST["genero"]);
            $correo = $conexion->real_escape_string($_POST["correo"]);
            $telefono = $conexion->real_escape_string($_POST["telefono"]);
            $fecha_ingreso = $conexion->real_escape_string($_POST["fecha_ingreso"]);
            
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del correo electrónico no es válido.");
            }
            
            $sql_insert = "INSERT INTO alumnos (numero_control, nombre, apellido_paterno, apellido_materno, 
                          id_edad, id_colonia, id_especialidad, id_genero, correo, telefono, fecha_ingreso) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conexion->prepare($sql_insert);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            
            $stmt->bind_param("ssssiiiisss", $numero_control, $nombre, $apellido_paterno, $apellido_materno, 
                             $edad, $colonia, $especialidad, $genero, $correo, $telefono, $fecha_ingreso);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Nuevo alumno agregado con éxito.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                throw new Exception("Error al agregar alumno: " . $stmt->error);
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos - CETIS 132</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <link href="https://fonts.cdnfonts.com/css/bungee-outline" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/madelis-script" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/feltpen" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/brushpen-story" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/snowpe" rel="stylesheet">
    <style>
        body {
            background-image: url('https://wallpapers.com/images/hd/black-and-white-music-1600-x-1066-wallpaper-m4g94usortrco3os.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        h1 {
            text-align: center;
            color: #e0e0e0;
            margin-bottom: 20px;
            font-family: "Courier New", monospace;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.9);
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 50px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        
        tr:nth-child(even) {
            background-color: rgba(50, 80, 100, 0.8);
            color: #e8f4ff;
            font-family: "Verdana", sans-serif;
        }
        
        tr:nth-child(odd) {
            background-color: rgba(30, 50, 70, 0.8);
            color: #e8f4ff;
            font-family: "Verdana", sans-serif;
        }
        
        th {            
            background-color: #1a3b5c;
            color: #ffcc80;
            font-weight: bold;
            font-family: "Georgia", serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        
        .container1 {
            width: 50%;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        form {
            flex-direction: column;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            color: white;
        }
        
        label {
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
            color: #a0e6ff;
            font-family: "Trebuchet MS", sans-serif;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        input[type="text"], input[type="email"], input[type="date"], select {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid rgba(160, 230, 255, 0.3);
            border-radius: 3px;
            font-size: 15px;
            background-color: rgba(15, 40, 60, 0.6);
            color: #d4f0ff;
            transition: all 0.3s;
            font-family: "Segoe UI", Tahoma, sans-serif;
        }
        
        input[type="text"]:focus, input[type="email"]:focus, input[type="date"]:focus, select:focus {
            background-color: rgba(255, 255, 255, 0.2);
            outline: none;
        }
        
        input[type="submit"] {
            padding: 12px 20px;
            background-color: #1a3b5c;
            border: 2px solid rgba(160, 230, 255, 0.3);
            color: #ffcc80;
            font-size: 15px;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
            width: 100%;
            font-family: "Trebuchet MS", sans-serif;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
        }
        
        input[type="submit"]:hover {
            background-color: #2a4b6c;
            border-color: rgba(160, 230, 255, 0.5);
            box-shadow: 0 0 10px rgba(160, 230, 255, 0.3);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.8);
            border: 1px solid #28a745;
            color: white;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.8);
            border: 1px solid #dc3545;
            color: white;
        }
        
        .jumbotron {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            border-radius: 0.3rem;
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
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" style="color: white;">UNIDAD 1</a>
                        <div class="dropdown-menu" style="background-color: rgba(0, 0, 0, 0.8);" aria-labelledby="navbarDropdownMenuLink1">
                            <a class="dropdown-item" href="luis01.php" style="color: white;">Práctica 1</a><br>
                            <a class="dropdown-item" href="l02.php" style="color: white;">Práctica 2</a><br>
                            <a class="dropdown-item" href="luis03.php" style="color: white;">Práctica 3</a>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" style="color: white;">UNIDAD 2</a>
                        <div class="dropdown-menu" style="background-color: rgba(0, 0, 0, 0.8);" aria-labelledby="navbarDropdownMenuLink2">
                            <a class="dropdown-item" href="luis04.PHP" style="color: white;">Práctica 4</a><br>
                            <a class="dropdown-item" href="luis05.php" style="color: white;">Práctica 5</a><br>
                            <a class="dropdown-item" href="luis06.html" style="color: white;">Práctica 6</a>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" style="color: white;">UNIDAD 3</a>
                        <div class="dropdown-menu" style="background-color: rgba(0, 0, 0, 0.8);" aria-labelledby="navbarDropdownMenuLink3">
                            <a class="dropdown-item" href="luis07.html" style="color: white;">Práctica 7</a><br>
                            <a class="dropdown-item" href="luis08.html" style="color: white;">Práctica 8</a><br>
                            <a class="dropdown-item" href="luis09.html" style="color: white;">Práctica 9</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="jumbotron">
        <h1 class="display-4 font" style="text-align: center; font-family: 'Snowpe', sans-serif;">Gestión de Alumnos</h1>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="Formulario">
            <h3 style="color:rgb(184, 121, 255);">Registrar nuevo alumno</h3>
            
            <label for="numero_control">Número de control:</label>
            <input type="text" id="numero_control" name="numero_control" required>
            
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="apellido_paterno">Apellido paterno:</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" required>
            
            <label for="apellido_materno">Apellido materno:</label>
            <input type="text" id="apellido_materno" name="apellido_materno" required>

            <label for="edad">Edad:</label>
            <select name="edad" id="edad" required>
                <option value="">Seleccione una edad</option>
                <?php 
                if (isset($results['edades'])) {
                    while ($row = $results['edades']->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["edad"] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="colonia">Colonia:</label>
            <select name="colonia" id="colonia" required>
                <option value="">Seleccione una colonia</option>
                <?php 
                if (isset($results['colonias'])) {
                    while ($row = $results['colonias']->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["colonias"] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="especialidad">Especialidad:</label>
            <select name="especialidad" id="especialidad" required>
                <option value="">Seleccione una especialidad</option>
                <?php 
                if (isset($results['especialidades'])) {
                    while ($row = $results['especialidades']->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["especialidades"] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="genero">Género:</label>
            <select name="genero" id="genero" required>
                <option value="">Seleccione un género</option>
                <?php 
                if (isset($results['generos'])) {
                    while ($row = $results['generos']->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["generos"] . '</option>';
                    }
                }
                ?>
            </select>
            
            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>
            
            <label for="fecha_ingreso">Fecha de Ingreso:</label>
            <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>

            <input type="submit" value="Agregar alumno">
        </form>
    </div>

    <div class="container" style="margin-top: 30px;">
        <h2 style="color:rgb(193, 13, 238); text-align: center;">Lista de alumnos</h2>
        <table border="1">
            <tr>
                <th>Número de control</th>
                <th>Nombre</th>
                <th>Apellido paterno</th>
                <th>Apellido materno</th>
                <th>Edad</th>
                <th>Colonia</th>
                <th>Especialidad</th>
                <th>Género</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Fecha de ingreso</th>
            </tr>
            <?php
            $sql = "SELECT
                        a.numero_control,
                        a.nombre,
                        a.apellido_paterno,
                        a.apellido_materno,
                        e.edad,
                        c.colonias as nombre_colonia,
                        es.especialidades as nombre_especialidad,
                        g.generos as nombre_genero,
                        a.correo,
                        a.telefono,
                        a.fecha_ingreso
                        FROM alumnos a
                        JOIN edades e ON a.id_edad = e.id
                        JOIN colonias c ON a.id_colonia = c.id
                        JOIN especialidades es ON a.id_especialidad = es.id
                        JOIN generos g ON a.id_genero = g.id";
            
            try {
                $resultado = $conexion->query($sql);
                if (!$resultado) {
                    throw new Exception("Error en la consulta: " . $conexion->error);
                }
                
                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['numero_control']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['apellido_paterno']}</td>
                                <td>{$row['apellido_materno']}</td>
                                <td>{$row['edad']}</td>
                                <td>{$row['nombre_colonia']}</td>
                                <td>{$row['nombre_especialidad']}</td>
                                <td>{$row['nombre_genero']}</td>
                                <td>{$row['correo']}</td>
                                <td>{$row['telefono']}</td>
                                <td>{$row['fecha_ingreso']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' style='color:white; text-align:center;'>No se encontraron registros en la base de datos.</td></tr>";
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='11' style='color:white; background-color:#a94442; text-align:center;'>" . $e->getMessage() . "</td></tr>";
            }
            
            $conexion->close();
            ?>
        </table>
    </div>

    <script>
    document.getElementById('Formulario').addEventListener('submit', function(e) {
        let isValid = true;
        const required = this.querySelectorAll('[required]');
        
        required.forEach(function(field) {
            if (!field.value.trim()) {
                field.style.borderColor = 'red';
                isValid = false;
            } else {
                field.style.borderColor = '';
            }
            
            if (field.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    field.style.borderColor = 'red';
                    isValid = false;
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor completa todos los campos requeridos correctamente.');
        }
    });
    </script>
</body>
</html>