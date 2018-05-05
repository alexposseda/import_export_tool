<?php
    function __autoload($classname){
        require_once 'lib'.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.class.php';
    }
    
    $data = json_decode(file_get_contents('result/form.json'), true);
    
    $import = new \Transfer\Import($data, include 'config/import/forms.config.php');
    $import->save();