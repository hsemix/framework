<?php
// Here you can initialize variables that will be available to your tests
// Emulate session
$_SESSION = [];
$_COOKIE[session_name()] = uniqid();

// init application
$env = getenv('BLUZ_ENV') ?: 'testing';

$app = \Bluz\Tests\BootstrapTest::getInstance();
$app->init($env);