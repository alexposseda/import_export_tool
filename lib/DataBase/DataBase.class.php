<?php
    
    namespace DataBase;
    class DataBase implements \IteratorAggregate
    {
        protected static $_tables = [];
        protected        $_config = [];
        
        /**
         * DataBase constructor.
         *
         * @param array $config
         */
        public function __construct($config){
            $this->_config = $config;
        }
        
        /**
         * @param string $name
         *
         * @return Table
         * @throws \Exception
         */
        public static function getTable($name){
            if(isset(self::$_tables[$name])){
                return self::$_tables[$name];
            }
            
            throw new \Exception('Unknown table [' . $name . ']!');
        }
        
        /**
         * @param array $data
         *
         * @return Table[]
         * @throws \Exception
         */
        public function loadData($data){
            global $start;
            foreach($this->_config as $tableName => $params){
                $columns = (isset($params['fields'])) ? $params['fields'] : [];
                $table   = new Table($tableName, $columns, $params);
                if(isset($data[$tableName])){
                    foreach($data[$tableName] as $row){
                        $table->addRow($row);
                    }
                    unset($data[$tableName]);
                }
                self::$_tables[$tableName] = $table;
                echo 'Table ['.$tableName.'] Loaded! duration: '.(microtime(true) - $start).'s'."\n";
            }
            
            foreach(self::$_tables as $table){
                $table->createRelations();
                echo 'Relations for table ['.$table->getName().'] Created! duration: '.(microtime(true) - $start).'s'."\n";
            }
            
            return self::$_tables;
        }
        
        /**
         * @return \ArrayIterator|\Traversable
         */
        public function getIterator(){
            return new \ArrayIterator(self::$_tables);
        }
    }