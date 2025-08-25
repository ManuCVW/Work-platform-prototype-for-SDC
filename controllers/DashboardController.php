<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;
use Model\Proyecto;


class DashboardController {
    public static function index(Router $router) {

        session_start();
        isAuth();

        if($_SESSION["tipoUsuario"] === "4") {
            header('Location: /SDC/index');
        }

        $id = $_SESSION['id'];
        $proyectos = Proyecto::all();

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos,
            'id' => $id
        ]);
    }


    public static function crear_proyecto(Router $router) {
        session_start();
        
        isAuth();

        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);
            // validación
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                // Generar una URL única 
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                // Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar el Proyecto
                $proyecto->guardar();

                // Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url);

            }
        }

        $router->render('dashboard/crear-proyecto', [
            'alertas' => $alertas,
            'titulo' => 'Crear Proyecto'
        ]);
    }

    public static function crear_usuario(Router $router) {
        session_start();
        
        isAuth();

        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $usuario = new Usuario;
        $alertas = [];

        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar el Token
                    $usuario->crearToken();

                    // Crear un nuevo usuario
                    $resultado =  $usuario->guardar();

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    

                    if($resultado) {
                        header('Location: /crear-usuario?resultado=1');
                    }
                }
            }
        }
        $router->render('dashboard/crear-usuario', [
            'alertas' => $alertas,
            'titulo' => 'Crear un Usuario'
        ]);
    }

    public static function sistema(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/SDC/index', [
            'titulo' => 'Sistema'
        ]);
    }

    public static function sectores(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/SDC/sectores', [
            'titulo' => 'Sistema'
        ]);
    }
    public static function localidades(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/SDC/localidades', [
            'titulo' => 'Sistema'
        ]);
    }
    public static function reportes(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/SDC/reportes', [
            'titulo' => 'Sistema'
        ]);
    }
    public static function configuracion(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/SDC/configuracion', [
            'titulo' => 'Sistema'
        ]);
    }
    public static function creditos(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/SDC/creditos', [
            'titulo' => 'Sistema'
        ]);
    }
    public static function clientes(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/SDC/clientes', [
            'titulo' => 'Sistema'
        ]);
    }

    //CHATS
    public static function administracionChats(Router $router) {
        session_start();
        isAuth();
        if($_SESSION["tipoUsuario"] === "3") {
            header('Location: /dashboard');
        }
        $router->render('dashboard/administracion-chats', [
            'titulo' => 'Administracion de Chats'
        ]);
    }

    public static function proyecto(Router $router) {
        session_start();
        isAuth();

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');
        // Revisar que la persona que visita el proyecto, es quien lo creo
        $proyecto = Proyecto::where('url', $token);
        // if($proyecto->propietarioId !== $_SESSION['id']) {
        //     header('Location: /dashboard');
        // }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->nombre
        ]);
    }
    
    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if(empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id ) {
                    // Mensaje de error
                    Usuario::setAlerta('error', 'Email no válido, ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();
                } else {
                    // Guardar el registro
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();

                    // Asignar el nombre nuevo a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
        
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            // Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if($resultado) {
                    $usuario->password = $usuario->password_nuevo;

                    // Eliminar propiedades No necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // Hashear el nuevo password
                    $usuario->hashPassword();

                    // Actualizar
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'Password Guardado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
         ]);
    }

    //CHAT
    public static function conexionBD2(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/chat-v3/conexionBD2', [
            'titulo' => ''
        ]);
    }
    public static function divChat(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/chat-v3/divChat', [
            'titulo' => ''
        ]);
    }

    public static function sendingChat(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/chat-v3/sendingChat', [
            'titulo' => ''
        ]);
    }
    public static function sendMessage(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/chat-v3/sendMessage', [
            'titulo' => ''
        ]);
    }
    //CHAT ADMIN
    public static function addSelectedName(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/adminChat-v3/addSelectedName', [
            'titulo' => ''
        ]);
    }
    public static function conexionBD(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/adminChat-v3/conexionBD', [
            'titulo' => ''
        ]);
    }

    public static function createChat(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/adminChat-v3/createChat', [
            'titulo' => ''
        ]);
    }

    public static function deleteChat(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/adminChat-v3/deleteChat', [
            'titulo' => ''
        ]);
    }

    public static function listChats(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/adminChat-v3/listChats', [
            'titulo' => ''
        ]);
    }

    public static function updateChat(Router $router) {
        session_start();
        isAuth();
        $router->render('dashboard/adminChat-v3/updateChat', [
            'titulo' => ''
        ]);
    }
}