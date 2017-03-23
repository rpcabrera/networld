<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

/** Loaders para los bundles terceros utilizados */
$loader->add('Ladybug', __DIR__ . '/../vendor/RaulFraile/Ladybug/lib');
$loader->add('RaulFraile', __DIR__ . '/../vendor');
$loader->add('Spraed', __DIR__ . '/../vendor');
$loader->add('FOS', __DIR__ . '/../vendor');

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
