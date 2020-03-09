<?php

use Framework\Contracts\DispatcherInterface;
use Framework\Contracts\RendererInterface;
use Framework\Contracts\RouterInterface;
use Framework\Contracts\SessionInterface;
use Framework\DependencyInjection\SymfonyContainer;
use Framework\Dispatcher\Dispatcher;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\Session\Session;
use Quizapp\Controller\QuestionTemplateController;
use Quizapp\Controller\QuizTemplateController;
use Quizapp\Controller\UserController;
use Quizapp\Entity\QuestionTemplate;
use Quizapp\Entity\QuizInstance;
use Quizapp\Entity\QuizTemplate;
use Quizapp\Entity\User;
use Quizapp\Repository\QuestionTemplateRepository;
use Quizapp\Repository\QuizTemplateRepository;
use Quizapp\Repository\UserRepository;
use Quizapp\Service\QuestionTemplateService;
use Quizapp\Service\QuizTemplateService;
use Quizapp\Service\UserService;
use ReallyOrm\Hydrator\HydratorInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Hydrator\Hydrator;
use ReallyOrm\Test\Repository\RepositoryManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$config = require __DIR__. '/config.php';
$container = new ContainerBuilder();

$container->setParameter('dsn', "mysql:host={$config['database']['host']};dbname={$config['database']['db']};charset={$config['database']['charset']}");
$container->setParameter('user', $config['database']['user']);
$container->setParameter('pass', $config['database']['pass']);
$container->setParameter('options', $config['options']);

$container->register(PDO::class, PDO::class)
    ->addArgument('%dsn%')
    ->addArgument('%user%')
    ->addArgument('%pass%')
    ->addArgument('%options%');

$container->register(RepositoryManagerInterface::class, RepositoryManager::class);

$container->register(HydratorInterface::class, Hydrator::class)
    ->addArgument(new Reference(RepositoryManagerInterface::class));

$container->register(UserRepository::class, UserRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(User::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->register(QuizTemplateRepository::class, QuizTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuizTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$container->register(QuestionTemplateRepository::class, QuestionTemplateRepository::class)
    ->addArgument(new Reference(PDO::class))
    ->addArgument(QuestionTemplate::class)
    ->addArgument(new Reference(HydratorInterface::class))
    ->addTag('repository');

$repoManager = $container->getDefinition(RepositoryManagerInterface::class);
foreach ($container->findTaggedServiceIds('repository') as $id => $value) {
    $repository = $container->getDefinition($id);
    $repoManager->addMethodCall('addRepository', [$repository]);
}

$container->setParameter('config', $config);
$container->register(RouterInterface::class, Router::class)
            ->addArgument('%config%');
$container->setParameter('baseViewPath', dirname(__DIR__, 1).'/views/');
$container->register(RendererInterface::class, Renderer::class)
    ->addArgument('%baseViewPath%');

$container->register(SessionInterface::class, Session::class);

$container->register(UserService::class, UserService::class)
        ->addArgument(new Reference(UserRepository::class))
        ->addArgument(new Reference(SessionInterface::class));

$container->register(UserController::class,UserController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(UserService::class))
    ->addTag('controller');

$container->register(QuestionTemplateService::class, QuestionTemplateService::class)
    ->addArgument(new Reference(QuestionTemplateRepository::class))
    ->addArgument(new Reference(SessionInterface::class));

$container->register(QuestionTemplateController::class,QuestionTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(QuestionTemplateService::class))
    ->addTag('controller');

$container->register(QuizTemplateService::class, QuizTemplateService::class)
    ->addArgument(new Reference(QuizTemplateRepository::class))
    ->addArgument(new Reference(SessionInterface::class));

$container->register(QuizTemplateController::class,QuizTemplateController::class)
    ->addArgument(new Reference(RendererInterface::class))
    ->addArgument(new Reference(QuizTemplateService::class))
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

return new SymfonyContainer($container);