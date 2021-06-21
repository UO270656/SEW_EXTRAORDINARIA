<!DOCTYPE html>

<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Booksew-Bibliotecas</title>
    <meta name="Booksew" content="Bibliotecas de la página web Booksew" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="base.css" />
    <base href="" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="scriptGEO.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6j4mF6blrc4kZ54S6vYZ2_FpMY9VzyRU&callback=documento.initMap"></script>
</head>

<body onload="documento.initMap()">
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
        <h2>Ver Bibliotecas</h2>
        <div id="bibliotecas">
        </div>
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
            public function getBibliotecas()
            {
                $this->db = new mysqli($this->servername, $this->username, $this->password, $this->database);
                $consultaPre = $this->db->query("SELECT * FROM bibliotecas");

                while ($row = $consultaPre->fetch_assoc()) {
                    $apiKey = "&key=AIzaSyC6j4mF6blrc4kZ54S6vYZ2_FpMY9VzyRU";
                    $url = "https://maps.googleapis.com/maps/api/staticmap?";
                    $centro = "center=" . $row['Longitud'] . "," . $row['Lat'];
                    $zoom = "&zoom=15";
                    $tamaño = "&size=800x600";
                    $marcador = "&markers=color:red%7Clabel:S%7C" . $row['Longitud'] . "," . $row['Lat'];
                    $sensor = "&sensor=false";
                    $imagenMapa = $url . $centro . $zoom . $tamaño . $marcador . $sensor . $apiKey;
                    echo "<ul>
                     <li>Nombre: " . $row['Nombre'] . "</li>
                     <li>Ubicacion: <img src='" . $imagenMapa . "' id='" . $row['ID_B'] . "' alt='biblioteca_" . $row['ID_B'] . "' onkeypress='documento.cambiarFoco(this)' onclick='documento.cambiarFoco(this)'/></li>
                     </ul>";
                }
                echo "<div id='mapa'></div>";
                $consultaPre->close();
                $this->db->close();
            }
        }
        $base = new BaseDatos();
        if (isset($_SESSION['base'])) {
            $base = $_SESSION['base'];
        } else {
            $_SESSION['base'] = $base;
        }
        if ($base->modo == 0) {
            $base->getBibliotecas();
        }
        ?>
    </section>
    <footer>
        <p id="credits">Copyright @2021 UO270656</p>
    </footer>
</body>

</html>