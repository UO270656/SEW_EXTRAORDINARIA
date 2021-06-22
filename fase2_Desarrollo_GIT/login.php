<!DOCTYPE html>

<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Booksew-Login</title>
    <meta name="Booksew" content="Login de la página web Booksew" />
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
                    <a id="inicio" href="inicio.html">Inicio</a>
                </li>
                <li>
                    <a id="about" href="about.html">Sobre el autor</a>
                </li>
                <li>
                    <a id="faq" href="faq.html">Ayuda</a>
                </li>
                <li>
                    <a id="login" href="login.php">Identificarse</a>
                </li>
                <li>
                    <a id="register" href="register.php">Registrarse</a>
                </li>
            </ul>
        </nav>
    </header>
    <section>
        <h2>Iniciar sesión</h2>

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
                    echo "
                    <div>
                        <form method='post' action='' name='signin-form'>
                            <div class='form-element'>
                                <label for='username'>Username (Cadena de letras y numeros sin espacios')</label>
                                <p><input id='username' type='text' name='username' pattern='[a-zA-Z0-9]+' required /></p>
                            </div>
                            <div class='form-element'>
                                <label for='password'>Password (Cadena de cualquier caracter')</label>
                                <p><input id='password' type='password' name='password' required /></p>
                            </div>
                            <p><button type='submit' name='login' value='login'>Log In</button></p>
                        </form>
                    </div>";
                } else {
                    echo "<p>ERROR en la creación de la Base de Datos '$this->database'. Error: " . $this->db->error . "</p>";
                    exit();
                }
                $this->db->close();
            }
            public function login()
            {
                $this->db = new mysqli($this->servername, $this->username, $this->password, $this->database);

                $consultaPre = $this->db->query("SELECT * FROM usuarios WHERE Usuario='" . $_POST["username"] . "' AND Contraseña='" . $_POST["password"] . "'");
                $row = $consultaPre->fetch_assoc();

                if ($row != null) {
                    $_SESSION['user_id'] = $row['ID_U'];
                    $_SESSION['username'] = $row['Usuario'];
                    $consultaPre->close();
                    header("Location: add.php");
                    die();
                } else {
                    echo "<p>Error al iniciar session</p>";
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
        if (isset($_POST['login'])) {
            $base->login();
        }
        if ($base->modo == 0) {
            $base->iniciar();
        }
        if (isset($_SESSION['registrado'])) {
            echo '<script>
            alert("Usuario ' . $_SESSION['registrado'] . ' registrado");
            </script>';
            $_SESSION['registrado'] = null;
        }
        ?>
    </section>
    <footer>
        <p id="credits">Copyright @2021 UO270656</p>
    </footer>
</body>

</html>