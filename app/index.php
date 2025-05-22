<?php

/*
  Aclaración: para todos los puntos se deberán crear o modificar las correspondientes clases de PHP,
  POO:obligatorio el uso de POO y MIDDLEWARE. Crear un APi Rest para la venta de criptomonedas
  
  1er Parte
  1-(POST) Verifique usuario:(JWT)
  verificar si el usuario( mail , tipo: {comprador, vendedor} y clave) coinciden con los guardados en la
  base de datos, retorna un objeto con la respuesta en OK y el tipo de perfil del usuario .
  2-(POST) Alta armamento antiaéreo ( precio, nombre, foto, nacionalidad)->solo admin/(JWT)
  3-(1pt)(GET) Listado de todas las armas-> sin autentificación
  4-(GET) Listado de todas las armas de una nacionalidad pasada por parámetro-> sin autentificación
  5-(1pt)(GET) Traer un arma por ID->cualquier usuario registrado
  6-(POST) Alta de ventaArmas (id,fecha,cantidad...y demás datos que crea necesarios) además de tener
  una imagen (jpg , jpeg ,png)asociada a la venta que será nombrada por el nombre de la arma,el
  nombre del cliente más la fecha en la carpeta /FotosArma2023 ->cualquier usuario registrado(JWT)
  
  2da Parte
  7- (GET)Traer todas las ventas de Armas de “EEUU” entre en 13 y 16 de noviembre ->solo admin(JWT)
  8-(GET)l Traer todos los usuarios que compraron armas “exocet” (o cualquier otra, buscada por
  nombre)->solo admin(JWT)

  3era Parte
  9-(DELETE) Borrado de un arma por ID->solo admin (JWT), Crear un Middleware que guarde un registro
  en la tabla logs (id_usuario, id_arma, accion, fecha_accion) luego de borrar.
  10-(PUT) Puede modificar los datos de un arma incluso la imagen , y si la imagen ya existe debe
  guardarla en la carpeta /Backup_2023 dentro de fotos.->solo admin (JWT)
  11-(GET) Descargar un CSV con el listado de todas las armas.
*/

// Establecer el nivel de error a -1 para mostrar todos los errores
error_reporting(-1);
// Habilitar la visualización de errores en pantalla
ini_set('display_errors', 1);

// Importar la clase AppFactory de Slim Factory
use Slim\Factory\AppFactory;
// Importar la clase RouteCollectorProxy de Slim Routing
use Slim\Routing\RouteCollectorProxy;
// Importar Middleware JWT
use App\Middlewares\JwtTokenValidatorMiddleware;

// Requerir el archivo autoload.php para cargar las dependencias
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/controllers/ProductoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/VentaController.php';
require_once __DIR__ . '/db/AccesoDatos.php';
require_once __DIR__ . '/Middlewares/JwtTokenGeneratorMiddleware.php';
require_once __DIR__ . '/Middlewares/JwtTokenValidatorMiddleware.php';
require_once __DIR__ . '/Middlewares/LoginMiddleware.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

// Crear una instancia de la aplicación Slim
$app = AppFactory::create();

// Agregar el middleware de manejo de errores
$app->addErrorMiddleware(true, true, true);

// Agregar el middleware de análisis del cuerpo de la solicitud
$app->addBodyParsingMiddleware();

// Defino las rutas dentro del grupo '/productos'
$app->group('/productos', function (RouteCollectorProxy $group) {
    // Ruta para el verbo POST
    $group->post('[/]', \ProductoController ::class . ':CargarUno')->add(new JwtTokenValidatorMiddleware("UTNFRA2023#", ["admin"]));
    // Ruta para el verbo GET
    $group->get('[/]', \ProductoController ::class . ':TraerTodos');
    // Ruta para el verbo GET con filtro por nacionalidad
    $group->get('/nacionalidad/{nacionalidad}', \ProductoController ::class . ':TraerPorNacionalidad');
    // Ruta para el verbo GET con filtro por id
    $group->get('/id/{id}', \ProductoController ::class . ':TraerPorId')->add(new JwtTokenValidatorMiddleware("UTNFRA2023#", ["admin", "comprador", "vendedor"]));
    // Subruta para el verbo GET (CSV)
    $group->get('/csv[/]', \ProductoController ::class . ':DescargarTodosCSV');
    // Ruta para el verbo PUT
    $group->put('[/]', \ProductoController ::class . ':ModificarPorId')->add(new JwtTokenValidatorMiddleware("UTNFRA2023#", ["admin"]));
});
  // Ruta para el verbo DELETE
  $app->delete('/borrar', \ProductoController ::class . ':BorrarPorId')->add(new JwtTokenValidatorMiddleware("UTNFRA2023#", ["admin"]));


  // Defino las rutas dentro del grupo '/pedidos'
  $app->group('/ventas', function (RouteCollectorProxy $group) {
    // Ruta para el verbo POST
    $group->post('[/]', \VentaController ::class . ':CargarUno')->add(new JwtTokenValidatorMiddleware("UTNFRA2023#", ["admin", "comprador", "vendedor"]));
    // Subruta para el verbo GET
    $group->get('/producto/{producto}', \VentaController ::class . ':TraerClientesPorProducto')->add(new JwtTokenValidatorMiddleware("UTNFRA2023#", ["admin"]));
    // Subruta para el verbo GET
    $group->get('/circunstanciasMuyEspecificas[/]', \VentaController ::class . ':TraerVentasPorPeriodo')->add(new JwtTokenValidatorMiddleware("UTNFRA2023#", ["admin"]));
  });


  $app->post('/login', function ($request, $response, $args) {
    return $response;
  })
  ->add(new JwtTokenGeneratorMiddleware("UTNFRA2023#"))
  ->add(new LoginMiddleware())
  ;

$app->run();

// composer update
// php -S localhost:666 router.php
// token admin Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2wiOiJhZG1pbiJ9.0APX0-RkBFmXdIHrJOMP43hkBf9jqeOdNNPmekoVfMk
// token vendedor Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2wiOiJ2ZW5kZWRvciJ9.GJQghh2GEo4aGanyBhPCzgWY5OFfdPCpoNNOZrPwj3I
// token comprador Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2wiOiJjb21wcmFkb3IifQ.FSRh7dd-KT9Cc4u1TrWV3LAWvTOeBWFZfh6ffcvJspQ

?>