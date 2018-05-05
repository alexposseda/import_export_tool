<?php
    
    namespace Provider;
    abstract class Provider
    {
        public static $_queries = 0;
        /**
         * @param array $config
         *
         * @return Provider
         * @throws \Exception
         */
        public static function getProvider($config){
            if(!isset($config['type'])){
                throw new \Exception('Config Error! Undefined provider type!');
            }
            
            $className = __NAMESPACE__ . '\Provider' . ucfirst(strtolower($config['type']));
            if(!class_exists($className)){
                throw new \Exception('Provider [' . $config['type'] . '] not defined!');
            }
            
            return new $className($config);
        }
        
        abstract public function find($key, $value);
        
        abstract public function save($data);
        
        abstract public function getColumns();
    }