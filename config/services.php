<?php


use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\DependencyInjection\SymfonyContainer;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Quizapp\Controller\UserController;
use Quizapp\Entity\Quiz;
use Quizapp\Entity\User;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\QuizRepository;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReallyOrm\Test\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$config = require __DIR__. '/config.php';
$container = new ContainerBuilder();
$container->setParameter('config', $config);
$container->register(RouterInterface::class, Router::class)
            ->addArgument('%config%');
$container->setParameter('baseViewPath', dirname(__DIR__, 1).'/views/');
$container->register(RendererInterface::class, Renderer::class)
    ->addArgument('%baseViewPath%');
$container->register(UserController::class,UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addTag('controller');
$container->setParameter('controllerNamespace', $config['dispatcher']['controllerNamespace']);
$container->setParameter('controllerSuffix', $config['dispatcher']['controllerSuffix']);
$container->register(DispatcherInterface::class, Dispatcher::class)
    ->addArgument('%controllerNamespace%')
    ->addArgument( '%controllerSuffix%');
$dispatcher = $container->getDefinition(DispatcherInterface::class);
foreach ($container->findTaggedServiceIds('controller') as $id => $value) {
    $controller = $container->getDefinition($id);
    $dispatcher->addMethodCall('addController', [$controller]);
}
$container->setParameter('dsn', "mysql:host={$config['database']['host']};dbname={$config['database']['db']};charset={$config['database']['charset']}");
$container->setParameter('user', $config['database']['user']);
$container->setParameter('pass', $config['database']['pass']);
$container->setParameter('options', $config['options']);
$container->register(PDO::class, PDO::class)
    ->addArgument('%dsn%')
    ->addArgument('%user%')
    ->addArgument('%pass%')
    ->addArgument('%options');
$container->register(RepositoryManagerInterface::class, RepositoryManager::class);
$container->register(HydratorInterface::class, Hydrator::class)
        ->addArgument(new Reference(RepositoryManagerInterface::class));
$container->register(RepositoryInterface::class, UserRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(new Reference(User::class))
    ->addArgument(new Reference(Hydrator::class))
    ->addTag('repository');
$container->register(RepositoryInterface::class, QuizRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(new Reference(Quiz::class))
    ->addArgument(new Reference(Hydrator::class))
    ->addTag('repository');
$repoManager = $container->getDefinition(RepositoryManagerInterface::class);
foreach ($container->findTaggedServiceIds('repository') as $id => $value) {
    $repository = $container->getDefinition($id);
    $repoManager->addMethodCall('addRepository', [$repository]);
}

return new SymfonyContainer($container);