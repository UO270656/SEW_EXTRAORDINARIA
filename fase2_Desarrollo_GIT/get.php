<!DOCTYPE html>

<html lang="es">

<head>
  <!-- Datos que describen el documento -->
  <meta charset="UTF-8" />
  <title>Booksew-Coleccion</title>
  <meta name="Booksew" content="Coleccion de la página web Booksew" />
  <link rel="stylesheet" type="text/css" href="base.css" />
  <base href="" />
  <!-- añadir el repositorio.io -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="scriptGET.js"></script>
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
    <h2>Ver colección</h2>
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
      public function getSelect()
      {
        $this->db = new mysqli($this->servername, $this->username, $this->password, $this->database);
        echo "
        <div>
          <label for='libros'>Escoge un libro:</label>
          <p><select onchange='documento.cambiarLibro()' name='libros' id='libros'>";
        $consultaPre = $this->db->query("SELECT * FROM libros WHERE ID_U = " . $_SESSION["user_id"]);
        while ($row = $consultaPre->fetch_assoc()) {
          echo " <option value='" . $row["ID_L"] . "'>" . $row["Nombre"] . "</option>";
        }
        echo "</select></p>";
        $this->db = new mysqli($this->servername, $this->username, $this->password, $this->database);
        $consultaPre = $this->db->query("SELECT * FROM libros WHERE ID_U = " . $_SESSION["user_id"]);
        echo "<div id='nombres'>";
        while ($row = $consultaPre->fetch_assoc()) {
          echo "<div class='hide' id='div_" . $row["ID_L"] . "'>";
          echo "<ul>
          <li>Nombre: " . $row["Nombre"] . "</li>" .
            "<li>Tipo: " . $row["Tipo"] . "</li>" .
            "<li>Dificultad: " . $row["Dificultad"] . "</li>" .
            "<li>Publicacion: " . $row["Publicacion"] . "</li>" .
            "<li>Nº paginas: " . $row["Paginas"] . "</li>" .
            "<li>Editorial: " . $row["Editorial"] . "</li>" .
            "<li>Descripcion: " . $row["Descripcion"] . "</li>" .
            "<li>Target: " . $row["Target"] . "</li>" .
            "<li>Autor: " . $row["Autor"] . "</li>" .
            "<li>Recomendacion: " . $row["Recomendacion"] . "</li>" .
            "<li>ISBN: " . $row["Isbn"] . "</li>" .
            "<li>Portada: <img src='" . $row["Portada"] . "' alt='portada_" . $row["ID_L"] . "' onkeypress='documento.cambiarFoco(this)' onclick='documento.cambiarFoco(this)'/></li></ul>";
          echo "</div>";
        }
        echo "</div>
        </div>";
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
      $base->getSelect();
    }
    ?>
  </section>
  <footer>
    <p id="credits">Copyright @2021 UO270656</p>
  </footer>
</body>

</html>