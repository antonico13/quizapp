<?php

use Framework\Renderer\Renderer;
use Framework\Router\Router;

return [
    'renderer' => [
        Renderer::CONFIG_KEY_BASE_VIEW_PATH => dirname(__DIR__) . '/views/'
    ],
    'dispatcher' => [
        'controllerNamespace' => 'Quizapp\Controller',
            'controllerSuffix' => 'Controller'
        ],
    'routing' => [
        'routes' => [
            'user_homepage' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getHomepage',
                Router::CONFIG_KEY_PATH => '/user',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'admin_dashboard' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getDashboard',
                Router::CONFIG_KEY_PATH => '/admin',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'user_login' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'login',
                Router::CONFIG_KEY_PATH => '/login',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'user_getLogin' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getLogin',
                Router::CONFIG_KEY_PATH => '/',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'user_logout' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'logout',
                Router::CONFIG_KEY_PATH => '/logout',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'admin_get_quizzes' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'user',
                Router::CONFIG_KEY_ACTION => 'getQuizzes',
                Router::CONFIG_KEY_PATH => '/admin/quizzes',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'get_questions' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'getQuestions',
                Router::CONFIG_KEY_PATH => '/admin/questions',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'add_questions_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'addQuestions',
                Router::CONFIG_KEY_PATH => '/admin/questions/add',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'add_question' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'add',
                Router::CONFIG_KEY_PATH => '/admin/questions/add',
                Router::CONFIG_KEY_ATTRIBUTES => [
                ]
            ],
            'delete_question' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'delete',
                Router::CONFIG_KEY_PATH => '/admin/questions/delete/{id}',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],
            'edit_questions_page' => [
                Router::CONFIG_KEY_METHOD => 'GET',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'editQuestion',
                Router::CONFIG_KEY_PATH => '/admin/questions/edit/{id}',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],
            'edit_question' => [
                Router::CONFIG_KEY_METHOD => 'POST',
                Router::CONFIG_KEY_CONTROLLER => 'questionTemplate',
                Router::CONFIG_KEY_ACTION => 'edit',
                Router::CONFIG_KEY_PATH => '/admin/questions/edit/{id}',
                Router::CONFIG_KEY_ATTRIBUTES => [
                    'id' => '\d+'
                ]
            ],
        ],
    ],
    'database' => [
        'host' => 'localhost',
        'db' => 'quiz',
        'user' => 'toni',
        'pass' => 'Evozon123!',
        'charset' => 'utf8mb4'
    ],
    'options' => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ]
];