<?php
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('TestCase', __DIR__);
$loader->add('Transforms', __DIR__);
$loader->add('Contracts', __DIR__);
$loader->add('Data', __DIR__);
$loader->add('Mock', __DIR__);

require_once __DIR__.'/BaseTest.php';
