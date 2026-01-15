<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new App\Kernel('test', false);
$kernel->boot();

$application = new Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
$application->setAutoExit(false);

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:database:drop',
    '--if-exists' => '1',
    '--force' => '1',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:database:create',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:migrations:migrate',
    '--no-interaction' => '1',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:fixtures:load',
    '--no-interaction' => '1',
]));
// required to init cache directory for twig
$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'cache:warmup',
    '--no-interaction' => '1',
]));
