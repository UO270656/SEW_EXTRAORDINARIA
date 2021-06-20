<!DOCTYPE html>

<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Booksew-Registrarse</title>
    <meta name="Booksew" content="Registrarse de la página web Booksew" />
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
                    <a id="inicio" href="inicio.html">Inicio</a>
                </li>
                <li>
                    <a id="about" href="about.html">Sobre el autor</a>
                </li>
                <li>
                    <a id="faq" href="faq.html">FAQ</a>
                </li>
                <li>
                    <a id="login" href="login.php">Sign in</a>
                </li>
                <li>
                    <a id="register" href="register.php">Register</a>
                </li>
            </ul>
        </nav>
    </header>
    <section>
        <h2>Registrarse</h2>

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
                    echo "
                    <div>
                        <form method='post' action='' name='register-form'>
                            <div class='form-element'>
                                <label for='username'>Username</label>
                                <input id='username' type='text' name='username' pattern='[a-zA-Z0-9]+' required />
                            </div>
                            <div class='form-element'>
                                <label for='password'>Password</label>
                                <input type='password' name='password' required />
                            </div>
                            <button type='submit' name='register' value='register'>Register</button>
                        </form>
                    </div>";
                    $this->create = true;
                } else {
                    echo "<p>ERROR en la creación de la Base de Datos '$this->database'. Error: " . $this->db->error . "</p>";
                    exit();
                }
                $this->db->close();
            }
            public function register()
            {
                $this->db = new mysqli($this->servername, $this->username, $this->password, $this->database);

                $consultaPre = $this->db->query("SELECT MAX(ID_U) as ID_U FROM usuarios");
                $row = $consultaPre->fetch_assoc();

                if ($row != null) {
                    $newID = $row['ID_U'] + 1;
                    $consultaPre->close();
                } else {
                    echo "<p>Error al registrar el usuario</p>";
                    exit();
                }
                $consultaPre = $this->db->prepare("INSERT INTO usuarios (ID_U, Usuario, Contraseña)  VALUES (?,?,?)");

                //añade los parámetros de la variable Predefinida $_POST
                // sss indica que se añaden 3 string
                $consultaPre->bind_param(
                    'iss',
                    $newID,
                    $_POST["username"],
                    $_POST["password"]
                );

                $consultaPre->execute();
                if ($consultaPre->affected_rows > 0) {
                    $consultaPre->close();
                    $_SESSION['registrado'] = $_POST["username"];
                    header("Location: login.php");
                    die();
                } else {
                    echo '<script type="text/javascript">
                            alert("Error al registrar el usuario");
                        </script>';
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
        if (isset($_POST['register'])) {
            $base->register();
        }
        if ($base->modo == 0) {
            $base->iniciar();
        }
        ?>
    </section>
    <footer>
        <p id="credits">Copyright @2021 UO270656</p>
    </footer>
</body>

</html>