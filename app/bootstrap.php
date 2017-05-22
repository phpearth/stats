<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$container = new ContainerBuilder();

$container
    ->register('config', 'PHPWorldWide\Stats\Config')
    ->addArgument([
        __DIR__.'/../app/config/parameters.yml',
        __DIR__.'/../app/config/points.yml',
        __DIR__.'/../app/config/offensive_words.yml'
    ])
;

$container
    ->register('twig_loader_filesystem', 'Twig_Loader_Filesystem')
    ->addArgument(__DIR__.'/templates')
;

$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/config'));
$loader->load('services.yml');

$application = new Application('FB Groups Stats Generator', 'v0.7.0');
$application->add($container->get('generate_command'));
$application->add($container->get('clear_logs_command'));
$application->add($container->get('offensive_words_command'));
