<!DOCTYPE html>

<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Booksew-LogOut</title>
    <meta name="Booksew" content="LogOut de la página web Booksew" />
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
        <h2>Log out</h2>

        <?php
        $_SESSION['user_id'] = null;

        header("Location: inicio.html");
        die();
        ?>
    </section>
    <footer>
        <p id="credits">Copyright @2021 UO270656</p>
    </footer>
</body>

</html>