<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AdminController;
use Controllers\LoginController;
use Controllers\TareaController;
use Controllers\PaginaController;
use Controllers\AdminChatController;
use Controllers\DashboardController;

$router = new Router();

//Pagina principal


// Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crear Cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

// Formulario de olvide mi password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);


// ZONA DE PROYECTOS
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->get('/crear-usuario', [DashboardController::class, 'crear_usuario']);
$router->post('/crear-usuario', [DashboardController::class, 'crear_usuario']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->get('/administracion-chats', [DashboardController::class, 'administracionChats']);

//ADMIN
$router->get('/SDC/index', [DashboardController::class, 'sistema']);
$router->get('/SDC/sectores', [DashboardController::class, 'sectores']);
$router->get('/SDC/localidades', [DashboardController::class, 'localidades']);
$router->get('/SDC/reportes', [DashboardController::class, 'reportes']);
$router->get('/SDC/configuracion', [DashboardController::class, 'configuracion']);
$router->get('/SDC/clientes', [DashboardController::class, 'clientes']);
$router->get('/SDC/creditos', [DashboardController::class, 'creditos']);

//Ventas
// $router->get('/SDC/clientes', [DashboardController::class, 'clientes']);

//Chat
$router->get('/chat-v3/conexionBD', [DashboardController::class, 'conexionBD2']);
$router->get('/chat-v3/divChat', [DashboardController::class, 'divChat']);
$router->get('/chat-v3/sendingChat', [DashboardController::class, 'sendingChat']);
$router->get('/chat-v3/sendMessage', [DashboardController::class, 'sendMessage']);
//chat ADMIN
$router->get('/adminChat-v3/addSelectedName', [DashboardController::class, 'addSelectedName']);
$router->get('/adminChat-v3/conexionDB', [DashboardController::class, 'conexionDB']);
$router->get('/adminChat-v3/createChat', [DashboardController::class, 'createChat']);
$router->get('/adminChat-v3/deleteChat', [DashboardController::class, 'deleteChat']);
$router->get('/adminChat-v3/listChats', [DashboardController::class, 'listChats']);
$router->get('/adminChat-v3/updateChat', [DashboardController::class, 'updateChat']);

// API para las tareas
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);

// Zona de cobranza / administracion
// $router->get("/SDC", [AdminController::class, "index"]);





// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();