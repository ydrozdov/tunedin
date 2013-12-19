<?php
require __DIR__ . '/bootstrap.php';

$app = $container->get('silex');

$app['container'] = $container;

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->get('/', 'TN\Controller\RestController::indexAction');
$app->delete('/', 'TN\Controller\RestController::deleteAction');
$app->match('/', 'TN\Controller\RestController::patchAction')
    ->method('PATCH');

$app->error(function (\Exception $e, $code) {
    return new Symfony\Component\HttpFoundation\JsonResponse(
        array($e->getMessage())
    );
});

return $app;