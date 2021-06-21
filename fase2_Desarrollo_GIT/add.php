<!DOCTYPE html>

<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Booksew-Agregar</title>
    <meta name="Booksew" content="Agregar de la página web Booksew" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="base.css" />
    <base href="" />
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->
    <header>
        <h1 class="principal">
            <img src="img/logo.png" id="logo" alt="Logo de la página web" />
        </h1>
        <nav class="menu">
            <ul>
                <li>
                    <a id="add" href="add.php">Agregar datos</a>
                </li>
                <li>
                    <a id="get" href="get.php">Ver colección</a>
                </li>
                <li>
                    <a id="nyt" href="nyt.html">Ver BestSellers</a>
                </li>
                <li>
                    <a id="geo" href="geo.php">Ver Bibliotecas</a>
                </li>
                <li>
                    <a id="xml" href="xml.html">Validar libros</a>
                </li>
                <li>
                    <a id="logout" href="logout.php">Log out</a>
                </li>
            </ul>
        </nav>
    </header>
    <section>
        <h2>Agregar datos</h2>
        <?php
        session_start();
        class BaseDatos
        {
            public $modo;
            public $create;
            public $db;
            protected $servername;
            protected $username;
            protected $password;
            protected $database;
            public $informe;
            public function __construct()
            {
                $this->modo = 0;
                $this->create = false;
                $this->servername = "localhost";
                $this->username = "DBUSER2020";
                $this->password = "DBPSWD2020";
                $this->database = "booksew";
                $this->informe = array();
            }
            public function iniciar()
            {
                $this->createBD();
            }
            public function createBD()
            {
                $this->db = new mysqli($this->servername, $this->username, $this->password);
                if ($this->db->connect_error) {
                    exit("<p>ERROR de conexión:" . $this->db->connect_error . "</p>");
                }

                $cadenaSQL = "CREATE DATABASE IF NOT EXISTS $this->database COLLATE utf8_spanish_ci";
                if ($this->db->query($cadenaSQL) === TRUE) {
                    $this->create = true;
                } else {
                    echo "<p>ERROR en la creación de la Base de Datos '$this->database'. Error: " . $this->db->error . "</p>";
                    exit();
                }
                $this->db->close();
            }
            public function añadir()
            {
                $archivo = $_POST['enviarArchivo'];
                $data = file_get_contents($archivo);
                $libros = json_decode($data, true);

                foreach ($libros as $libro) {
                    $this->db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                    $consultaPre = $this->db->query("SELECT MAX(ID_L) FROM libros");
                    $row = $consultaPre->fetch_assoc();

                    if ($row != null) {
                        $newIDLibro = $row['MAX(ID_L)'] + 1;
                        $consultaPre->close();
                    } else {
                        $_SESSION['sinErroresAñadir'] = 1;
                        echo "<p>Error al registrar el libro</p>";
                        exit();
                    }
                    $consultaPre = $this->db->prepare("INSERT INTO libros (ID_L, ID_U, Publicacion, Paginas, Editorial, Descripcion, Target, Autor, Recomendacion, Nombre, Tipo, Dificultad, Portada, Isbn)  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

                    //añade los parámetros de la variable Predefinida $_POST
                    // sss indica que se añaden 3 string
                    $consultaPre->bind_param(
                        'iisissssisssss',
                        $newIDLibro,
                        $_SESSION['user_id'],
                        $libro["fecha_publicacion"],
                        $libro["paginas"],
                        $libro["editorial"],
                        $libro["descripcion"],
                        $libro["target"],
                        $libro["autor"],
                        $libro["recomendacion"],
                        $libro["nombre"],
                        $libro["tipo"],
                        $libro["dificultad"],
                        $libro["portada"],
                        $libro["isbn"],
                    );

                    $consultaPre->execute();
                    if ($consultaPre->affected_rows > 0) {
                        $consultaPre = $this->db->query("SELECT MAX(ID_B) FROM bibliotecas");
                        $row = $consultaPre->fetch_assoc();

                        if ($row != null) {
                            $newIDBiblio = $row['MAX(ID_B)'] + 1;
                            $consultaPre->close();
                        } else {
                            $_SESSION['sinErroresAñadir'] = 1;
                            echo "<p>Error al registrar la biblioteca</p>";
                            exit();
                        }
                        foreach ($libro["bibliotecas"] as $biblioteca) {
                            $consultaPre = $this->db->prepare("INSERT INTO bibliotecas (ID_B, Nombre, Longitud, Lat, Alt)  VALUES (?,?,?,?,?)");

                            //añade los parámetros de la variable Predefinida $_POST
                            // sss indica que se añaden 3 string
                            $consultaPre->bind_param(
                                'isddd',
                                $newIDBiblio,
                                $biblioteca["nombre"],
                                $biblioteca["long_biblioteca"],
                                $biblioteca["lat_biblioteca"],
                                $biblioteca["alt_biblioteca"]
                            );

                            $consultaPre->execute();
                            if ($consultaPre->affected_rows > 0) {
                                $consultaPre = $this->db->prepare("INSERT INTO libros_bibliotecas (ID_L, ID_B)  VALUES (?,?)");

                                //añade los parámetros de la variable Predefinida $_POST
                                // sss indica que se añaden 3 string
                                $consultaPre->bind_param(
                                    'ii',
                                    $newIDLibro,
                                    $newIDBiblio
                                );

                                $consultaPre->execute();
                                if ($consultaPre->affected_rows > 0) {
                                    $newIDBiblio = $newIDBiblio + 1;
                                } else {
                                    $_SESSION['sinErroresAñadir'] = 1;
                                    echo "<p>Error al registrar la relaccion</p>";
                                }
                            } else {
                                $_SESSION['sinErroresAñadir'] = 1;
                                echo "<p>Error al registrar la biblioteca</p>";
                            }
                        }
                    } else {
                        $_SESSION['sinErroresAñadir'] = 1;
                        echo "<p>Error al registrar el libro</p>";
                    }
                }
                $this->db->close();
            }
        }
        $base = new BaseDatos();
        if (isset($_SESSION['base'])) {
            $base = $_SESSION['base'];
        } else {
            $_SESSION['base'] = $base;
        }
        if (isset($_SESSION['username'])) {
            echo '<script>
            alert("Se ha iniciado como ' . $_SESSION['username'] . '");
            </script>';
            $_SESSION['username'] = null;
        }
        if (isset($_POST['enviarArchivo'])) {
            $base->añadir();
            if (!isset($_SESSION['sinErroresAñadir'])) {
                header("Location: get.php");
                die();
            }
        }
        ?>
        <div>
            <form method='post' action='' name='add_form'>
                <div class='form-element'>
                    <label for="subirArchivos">Selecione archivos JSON para añadir datos</label>
                    <input id='subirArchivos' type='file' name='enviarArchivo' required />
                </div>
                <button type='submit' name='login' value='login'>Añadir</button>
            </form>
        </div>
    </section>
    <footer>
        <p id="credits">Copyright @2021 UO270656</p>
    </footer>
</body>

</html>