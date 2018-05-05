<?php
    
    namespace DataBase;
    class Cell
    {
        protected $_columnName;
        protected $_columnAlias;
        protected $_value;
        protected $_row      = null;
        protected $_type;
        protected $_nullable = false;
        
        protected $_relations = [];
        
        /**
         * Field constructor.
         *
         * @param string $columnName
         * @param Row    $row
         * @param array  $params
         *
         * @throws \Exception
         */
        public function __construct($columnName, Row $row, $params){
            $this->_columnName = $columnName;
            $this->_row        = $row;
            if(isset($params['alias'])){
                $this->_columnAlias = $params['alias'];
            }
            
            if(!isset($params['type'])){
                throw new \Exception('Property [type] not defined for [' . $row->getTable()
                                                                               ->getName() . '][' . $columnName . ']');
            }
            $this->_type = $params['type'];
            
            if(isset($params['nullable'])){
                $this->_nullable = (bool)$params['nullable'];
            }
        }
        
        /**
         * @param mixed $value
         *
         * @throws \Exception
         */
        public function setValue($value){
            $this->_value = $this->filter($value);
        }
        
        /**
         * @param string|null $relation
         *
         * @return mixed
         * @throws \Exception
         */
        public function getValue($relation = null){
            if(is_null($relation)){
                return $this->_value;
            }
            
            if(isset($this->_relations[$relation])){
                return $this->_relations[$relation];
            }
            
            throw new \Exception('Undefined relation [' . $relation . ']');
        }
        
        /**
         * @param mixed $value
         *
         * @return bool|int|null|string
         * @throws \Exception
         */
        protected function filter($value){
            if($this->_nullable == true){
                if(is_null($value) OR empty($value) OR strtoupper($value) == 'NULL'){
                    return null;
                }
            }else{
                if($value != 0 AND empty($value)){
                    throw new \Exception('Filter error: [' . $this->_row->getTable()
                                                                        ->getName() . '][' . $this->_columnName . '] cannot be an empty!');
                }
            }
            
            switch($this->_type){
                case 'int':
                    return (int)$value;
                    break;
                case 'float':
                    return (float)$value;
                    break;
                case 'string':
                    return (string)$value;
                    break;
                case 'datetime':
                case 'date':
                case 'time':
                    return (string)$value;
                    break;
                case 'boolean':
                    return (int)(bool)$value;
                    break;
            }
            
            throw new \Exception('Filter error: [' . $this->_type . '] not found for [' . $this->_row->getTable()
                                                                                                     ->getName() . '][' . $this->_columnName . ']');
        }
        
        /**
         * @return string
         */
        public function getAlias(){
            if(!empty($this->__columnAlias)){
                return $this->_columnAlias;
            }
            
            return $this->getName();
        }
        
        /**
         * @return string
         */
        public function getName(){
            return $this->_columnName;
        }
        
        /**
         * @return Row
         */
        public function getRow(){
            return $this->_row;
        }
        
        /**
         * @param string   $key
         * @param Relation $relation
         */
        public function addRelation($key, Relation $relation){
            $this->_relations[$key][] = $relation;
        }
    }