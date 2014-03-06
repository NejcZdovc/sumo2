<?php

$setLocaleLang['hr']='';
$setLocaleLang['en-gb']='en_UK';
$setLocaleLang['en-us']='en_US';
$setLocaleLang['en']='english';
$setLocaleLang['de']='de_DE';
$setLocaleLang['de-lu']='de_AT';
$setLocaleLang['it']='';
$setLocaleLang['ru']='ru_RU';
$setLocaleLang['sr']='';
$setLocaleLang['sh']='';
$setLocaleLang['sl']='sl_SI';
$setLocaleLang['es']='es-ES';

setlocale(LC_ALL, $setLocaleLang[$_POST['langShort']].'.UTF-8');

?>