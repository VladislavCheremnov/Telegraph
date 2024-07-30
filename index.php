<?php

use App\Core\Templates\Spl;
use App\Core\Templates\Swig;
use App\Entities\TelegraphText;
use App\Interfaces\IRender;

include_once 'autoload.php';
include_once 'vendor\autoload.php';

$telegraphText = new TelegraphText('Vasya', 'Some slug');
$telegraphText->editText('Some title', 'Some text');

$swig = new Swig('telegraph_text');
$swig->addVariablesToTemplate(['slug', 'text']);

$spl = new Spl('telegraph_text');
$spl->addVariablesToTemplate(['slug', 'title', 'text']);

$templateEngines = [$swig, $spl];
foreach ($templateEngines as $engine) {
    if ($engine instanceof IRender) {
        echo $engine->render($telegraphText) . PHP_EOL;
    } else {
        echo 'Template engine does not support render interface' . PHP_EOL;
    }
}