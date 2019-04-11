<?php
$peticionAJAX = true;
require_once '../Core/configGeneral.php';
require_once '../controladores/loginControlador.php';
$inslogin = new loginControlador();
$_POST = json_decode(file_get_contents('php://input'), true);
if (isset($_POST['accion'])) {
  switch ($_POST['accion']) {
      case 'save':
          # code...
          break;
      case 'update':
          # code...
          break;
      case 'delete':
          # code...
          break;
      case 'data':
         $user=$inslogin->datos_login_controlador();
         if ($user=="ninguno") {
            echo'<div
            class="sufee-alert alert with-close alert-danger alert-dismissible fade show"
          >
            <span class="badge badge-pill badge-danger">Error</span>
            Email y Password incorrecto.
            <button
              type="button"
              class="close"
              data-dismiss="alert"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
         } else {
             print_r($user);
             session_start();
            //     $_SESSION["password"] = $user[0]["clave"];
            //     $_SESSION["user"] = $user[0]["usuario"];
                $_SESSION["t"] = $user[0]["tipo"];
            //     $_SESSION["foto"] = $user[0]["foto"];
            //     $_SESSION["cuentaCodigo"] = $user[0]["CuentaCodigo"];
            echo '<script> window.location.href="' . SERVERURL . 'home" </script>';
         }
         
          break;
      case 'list':
          # code...
          break;
      default:
          echo("ya perdiste");
          break;
  }
} else {
    session_start();
    session_destroy();
    echo '<script> window.location.href="' . SERVERURL . 'login" </script>';
}
