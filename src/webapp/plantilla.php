<?php
echo ('<!DOCTYPE html>');
echo ('<html lang="en">');
include './src/php/Controladores/vistasControlador.php';
$peticionAJAX = false;
$vt = new vistasControlador();
session_start();
if (isset($_SESSION["t"])) {
    switch ($_SESSION["t"]) {
        case 'admin':
            $viewsAdmin = $vt->obtener_vistas_controlador_admin();
            if ($viewsAdmin == "404") {
                echo ('<body >');
                require_once './src/webapp/views/contenidos/' . $viewsAdmin . '-view.html';
                session_destroy();
            } elseif ($viewsAdmin == "login") {
                require_once './src/webapp/views/modulos/head.php';
                echo ("<body >");
                require_once './src/webapp/views/contenidos/' . $viewsAdmin . '-view.html';
                require_once './src/webapp/views/modulos/script.php';
                echo ("</body >");
                session_destroy();
            } else {
                require_once './src/webapp/views/modulos/head.php';
                echo ('<body >');
                require_once './src/webapp/views/modulos/navLateral.php';
                echo ('<div id="right-panel" class="right-panel">');
                require_once './src/webapp/views/modulos/header.php';
                require_once './src/webapp/views/contenidos/' . $viewsAdmin . '-view.html';
                // footer
                // require_once './src/webapp/views/modulos/footerpublico.php';
                echo ('</div>');
                //  script
                require_once './src/webapp/views/modulos/script.php';
            }
            echo ('</body>');
            break;
        default:
            # code...
            break;
    }

} else {
    $viewsR = $vt->obtener_vistas_controlador();
    if ($viewsR == "404") {
        require_once './src/webapp/views/contenidos/' . $viewsR . '-view.html';
    } else {
        require_once './src/webapp/views/modulos/head.php';
        echo ("<body >");
        require_once './src/webapp/views/contenidos/' . $viewsR . '-view.html';
        require_once './src/webapp/views/modulos/script.php';
        echo ("</body >");
    }

}
echo ('</html>');
