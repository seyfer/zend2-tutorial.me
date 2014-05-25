<?php

//chdir(dirname(__DIR__));

require __DIR__ . '/../../../init_autoloader.php';

Zend\Mvc\Application::init(include 'config/application.config.php');
