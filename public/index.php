<?php
declare(strict_types=1);

use App\Application\Application;
use App\Infrastructure\Container;
use App\Infrastructure\Dispatcher;
use App\Infrastructure\Router;
use App\Infrastructure\LoggerFactory;
use App\Infrastructure\Image\Service\WatermarkServiceFactory;
use App\Infrastructure\RoutesConfigurator;
use App\Http\Controllers\WatermarkController;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Dotenv\Dotenv;

/**
 * Microservice entry point
 * php version 8.1
 *
 * @category PHP_Microservice
 * @package  PHP_Microservice
 * @author   Istvan Dobrentei <info@dobrenteiistvan.hu>
 * @license  https://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.en.dobrenteiistvan.hu
 */

require_once __DIR__ . '/../vendor/autoload.php';

//load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

//instantiate components
$router = new Router();
$dispatcher = new Dispatcher($router);
$container = new Container();
$container->bind(WatermarkController::class, fn() =>
    new WatermarkController(LoggerFactory::create(), WatermarkServiceFactory::create())
);

//setup routes
$configurator = new RoutesConfigurator();
$configurator->configure($router, $container);

//create and run app
$application = new Application($dispatcher);

$psr17 = new Psr17Factory();
$creator = new ServerRequestCreator($psr17, $psr17, $psr17, $psr17);
$request = $creator->fromGlobals();

$application->run($request);
