<?php
    
    use DataBase\DataBase;
    use Formatter\Formatter;
    use Provider\Provider;
    use Transfer\Export;
    
    function __autoload($classname){
        require_once 'lib' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.class.php';
    }
    
    $start  = microtime(true);
    $mysqli = new \mysqli('localhost', 'root', 'root', 'system');
    $sql    = "SELECT * FROM system_form";
    $r      = $mysqli->query($sql);
    if(!$r){
        die('SQL: ' . $sql . "\n\tError:" . $mysqli->error);
    }
    $data = $r->fetch_all(MYSQLI_ASSOC);
    
    echo 'Data Selected! duration: ' . (microtime(true) - $start) . 's' . "\n";
    $config = include 'config/export/front.config.php';
    $db     = new DataBase($config);
    try{
        $db->loadData(['user' => &$data]);
        echo 'DataBase Structure created! duration: ' . (microtime(true) - $start) . 's' . "\n";
        unset($data);
    }catch(Exception $e){
        die('DB loadData Error: ' . $e->getMessage());
    }
    
    $export = new Export($db);
    try{
        $formatter = Formatter::getFormatter('csv', []);
        //        $formatter = Formatter::getFormatter('json', []);
        
        $res = $export->asTables();
        echo 'Export Array Created! duration: ' . (microtime(true) - $start) . 's' . "\n";
        $formatter->formatTo($res)
                  ->save('result/csv');
        //    $formatter->formatTo($res)->save('result/json');
        echo 'Data saved! duration: ' . (microtime(true) - $start) . 's. Queries Count: ' . Provider::$_queries . "\n";
    }catch(Exception $e){
        die('Formatter Error' . $e->getMessage());
    }
    