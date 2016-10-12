<?php

require_once __DIR__.'/../vendor/autoload.php';

use Crey\Conjurer\{
    Conjurer,
    Service
};

use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Log;
use PHPWorldWide\Stats\Auth;
use PHPWorldWide\Stats\Command\GenerateCommand;
use PHPWorldWide\Stats\Command\ClearLogsCommand;
use PHPWorldWide\Stats\Command\OffensiveWordsCommand;
use Symfony\Component\Console\Application;

$container = new Conjurer();

$container->register(new Service(Config::class));
$config = $container->make(Config::class, [[
    __DIR__.'/../app/config/parameters.yml',
    __DIR__.'/../app/config/points.yml',
    __DIR__.'/../app/config/offensive_words.yml',
]]);

$container->register(new Service(Log::class));
$log = $container->make(Log::class);

$container->register(new Service(Auth::class));
$auth = $container->make(Auth::class, [$config]);

$container->register(new Service(Twig_Loader_Filesystem::class));
$twigLoader = $container->make(Twig_Loader_Filesystem::class, [__DIR__.'/../app/templates']);

$container->register(new Service(Twig_Environment::class));
$twig = $container->make(Twig_Environment::class, [$twigLoader]);

$container->register(new Service(GenerateCommand::class));
$generateCommand = $container->make(GenerateCommand::class);
$generateCommand->setConfig($config);
$generateCommand->setTemplate($twig);
$generateCommand->setAuth($auth);
$generateCommand->setLog($log);

$container->register(new Service(ClearLogsCommand::class));
$clearLogsCommand = $container->make(ClearLogsCommand::class);
$clearLogsCommand->setLog($log);

$container->register(new Service(OffensiveWordsCommand::class));
$offensiveWordsCommand = $container->make(OffensiveWordsCommand::class);
$offensiveWordsCommand->setOffensiveWords($config->get('offensive_words'));

$application = new Application('FB Groups Stats Generator', 'v0.7.0');
$application->add($generateCommand);
$application->add($clearLogsCommand);
$application->add($offensiveWordsCommand);
