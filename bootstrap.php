<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '256M');

$container = new ContainerBuilder();

$env = getenv('APP_ENV') ?: 'dev';

$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config/'));
$loader->load("config.$env.yml");