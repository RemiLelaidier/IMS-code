<?php

// TODO : Add business logic controllers
$controllers = [
    'core.controller' => 'App\Core\Controller\CoreController',
    'security.auth.controller' => 'App\Security\Controller\AuthController',
    'ims.convention.controller' => 'App\Ims\Convention\Controller\ConventionController'
];

foreach ($controllers as $key => $class) {
    $container[$key] = function ($container) use ($class) {
        return new $class($container);
    };
}
