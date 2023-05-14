<?php

// before run `composer install`

use Planka\Bridge\Config;
use Planka\Bridge\PlankaClient;

// copy config.example.php to config.php and setup for you
$config = include('./config.php');

require __DIR__ . '/../vendor/autoload.php';

$config = new Config(
    user: $config['login'],
    password: $config['password'],
    baseUri: $config['uri'],
    port: $config['port']
);
$planka = new PlankaClient($config);
$planka->authenticate();

// создать проект
    // создать борду
        // создать карточку
            // создать и изменить данные в карточке
            // закреп атача в карточку и удаление
        // удалить карточку
    // удалить борду
// удалить проект