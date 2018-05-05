<?php
    
    namespace Provider;
    
    class ProviderMysqli extends Provider
    {
        protected static $_connections = [];
        protected        $_mysqli      = null;
        
        protected $_database;
        protected $_table;
        
        /**
         * ProviderMysqli constructor.
         *
         * @param array $config
         *
         * @throws \Exception
         */
        public function __construct($config){
            $this->_database = $config['connection']['database'];
            $this->_table    = $config['table'];
            if(!isset(self::$_connections[$this->_database])){
                $mysqli = new \mysqli($config['connection']['host'],
                                      $config['connection']['user'],
                                      $config['connection']['password']);
                if($mysqli->connect_error){
                    throw new \Exception('Connection Error: ' . $mysqli->connect_error);
                }
                
                $mysqli->set_charset('utf8');
                self::$_connections[$this->_database] = $mysqli;
            }
            
            $this->_mysqli = self::getConnection($this->_database);
        }
        
        /**
         * @param string $name
         *
         * @return \mysqli
         * @throws \Exception
         */
        public static function getConnection($name){
            if(isset(self::$_connections[$name])){
                return self::$_connections[$name];
            }
            
            throw new \Exception('Connection [' . $name . '] not exists!');
        }
        
        /**
         * @param string $key
         * @param mixed  $value
         *
         * @return array
         * @throws \Exception
         */
        public function find($key, $value){
            if(is_null($value)){
                $sql = "SELECT * FROM " . $this->_database . '.' . $this->_table . " WHERE $key IS NULL";
            }else{
                $sql = "SELECT * FROM " . $this->_database . '.' . $this->_table . " WHERE $key = '" . $this->_mysqli->escape_string($value) . "'";
            }
            $r = $this->_mysqli->query($sql);
            Provider::$_queries++;
            if(!$r){
                throw new \Exception("Query Error!\n\tSQL: $sql\n\tError: " . $this->_mysqli->error);
            }
            
            return $r->fetch_all(MYSQLI_ASSOC);
        }
        
        /**
         * @param array       $data
         * @param string|null $pk
         *
         * @return array
         * @throws \Exception
         */
        public function save($data, $pk = null){
            $set = [];
            foreach($data as $k => $v){
                if(!is_null($pk) and $pk == $k){
                    continue;
                }
                
                if(is_null($v) OR strtolower($v) == 'null'){
                    $set[] = $k . "= NULL";
                }else{
                    $set[] = $k . "='" . $this->_mysqli->escape_string($v) . "'";
                }
            }
            
            $sql = 'INSERT INTO ' . $this->_database . '.' . $this->_table . ' SET ' . implode(',',
                                                                                               $set) . ' ON DUPLICATE KEY UPDATE ' . implode(',',
                                                                                                                                             $set);
            
            $r = $this->_mysqli->query($sql);
            if(!$r){
                throw new \Exception("Query Error!\n\tSQL: $sql\n\tError: " . $this->_mysqli->error);
            }
            
            $conditions = [];
            foreach($data as $k => $v){
                if(!is_null($pk) and $pk == $k){
                    continue;
                }
                
                if(is_null($v) OR strtolower($v) == 'null'){
                    $conditions[] = $k . " IS NULL";
                }else{
                    $conditions[] = $k . "='" . $this->_mysqli->escape_string($v) . "'";
                }
            }
            $sql = "SELECT " . implode(',',
                                       array_keys($data)) . " FROM " . $this->_database . '.' . $this->_table . " WHERE " . implode(' AND ',
                                                                                                                                    $conditions);
            $r   = $this->_mysqli->query($sql);
            if(!$r){
                throw new \Exception("Query Error!\n\tSQL: $sql\n\tError: " . $this->_mysqli->error);
            }
            $data = $r->fetch_assoc();
            
            
            return $data;
        }
    
        /**
         * @return array
         * @throws \Exception
         */
        public function getColumns(){
            $sql = "DESCRIBE " . $this->_database . '.' . $this->_table;
            $r   = $this->_mysqli->query($sql);
            if(!$r){
                throw new \Exception("Query Error!\n\tSQL: $sql\n\tError: " . $this->_mysqli->error);
            }
            
            $columns = [];
            $types   = [
                '/(int|bigint|smallint)/' => 'int',
                '/(float|double)/'        => 'float',
                '/(varchar|char|text)/'   => 'string',
                '/(tinyint)/'             => 'boolean',
                '/date/'                  => 'date',
                '/time/'                  => 'time',
                '/datetime/'              => 'datetime',
            ];
            while($row = $r->fetch_assoc()){
                $type                   = preg_replace('/(\(.*?\))/', '', $row['Type']);
                foreach($types as $pattern => $newType){
                    if(preg_match($pattern, $type) == 1){
                        $type = $newType;
                        break;
                    }
                }
                if(!in_array($type, $types)){
                    throw new \Exception('Undefined type ['.$type.']');
                }
                
                $nullable = ($row['Null'] == 'YES') ? true : false;
                $columns[$row['Field']] = [
                    'type'     => $type,
                    'nullable' => $nullable
                ];
            }
            
            return $columns;
        }
    }