<?php
    
    namespace DataBase;
    
    use Provider\Provider;
    
    class Table implements \IteratorAggregate
    {
        protected $_index = [];
        
        protected $_rows         = [];
        protected $_name;
        protected $_columns      = [];
        protected $_indexColumns = [];
        protected $_relations    = [];
        protected $_dataProvider = null;
        protected $_pk           = 'id';
        
        
        /**
         * Table constructor.
         *
         * @param string $name
         * @param array  $columns
         * @param array  $params
         *
         * @throws \Exception
         */
        public function __construct($name, $columns = [], $params = []){
            $this->_name    = $name;
            
            if(isset($params['primaryKey'])){
                $this->_pk = $params['primaryKey'];
            }
            
            if(isset($params['indexes'])){
                $this->_indexColumns = $params['indexes'];
            }
            
            if(isset($params['relations'])){
                $this->_relations = $params['relations'];
            }
            
            if(!isset($params['provider'])){
                throw new \Exception('Invalid Configuration for [' . $name . ']! property [provider] not defined!');
            }
            $this->_dataProvider = Provider::getProvider($params['provider']);
    
            $this->_columns = $this->getProvider()->getColumns();
            if(!empty($columns)){
                $this->_columns = array_merge($this->_columns, $columns);
            }
    
        }
    
        /**
         * @param array $data
         *
         * @return Row
         * @throws \Exception
         */
        public function addRow($data){
            $row           = new Row($this, $data);
            $this->_rows[] = $row;
            
            if(!empty($this->_indexColumns)){
                foreach($this->_indexColumns as $columnName){
                    $this->addIndex($columnName, $row);
                }
            }
            
            return $row;
        }
    
        /**
         * @param string $field
         * @param mixed  $value
         *
         * @return Row[]
         * @throws \Exception
         */
        public function findAll($field, $value){
            return $this->search($field, $value);
        }
    
        /**
         * @param string $field
         * @param mixed  $value
         *
         * @return Row
         * @throws \Exception
         */
        public function findOne($field, $value){
            $result = $this->findAll($field, $value);
            
            return current($result);
        }
    
        /**
         * @param string $key
         * @param Row    $row
         *
         * @throws \Exception
         */
        protected function addIndex($key, Row $row){
            if(!isset($this->_index[$key])){
                $this->_index[$key] = [];
            }
            
            $v = $row->$key->getValue();
            if(!isset($this->_index[$key][$v])){
                $this->_index[$key][$v] = [];
            }
            
            $this->_index[$key][$v][] = $row;
        }
    
        /**
         * @param string $field
         * @param mixed  $value
         *
         * @return Row[]
         * @throws \Exception
         */
        protected function search($field, $value){
            $rows = $this->searchInIndex($field, $value);
            
            if(empty($rows)){
                foreach($this->_rows as $row){
                    if($row->$field->getValue() == $value){
                        $rows[] = $row;
                    }
                }
            }
            
            if(empty($rows)){
                $rows = $this->searchInDataProvider($field, $value);
            }
            
            return $rows;
        }
        
        /**
         * @param string $field
         * @param mixed  $value
         *
         * @return Row[]
         */
        protected function searchInIndex($field, $value){
            if(isset($this->_index[$field][$value])){
                return $this->_index[$field][$value];
            }
            
            return [];
        }
    
        /**
         * @param string $field
         * @param mixed  $value
         *
         * @return Row[]
         * @throws \Exception
         */
        protected function searchInDataProvider($field, $value){
            $data = $this->_dataProvider->find($field, $value);
            $rows = [];
            foreach($data as $item){
                $rows[] = $this->addRow($item);
            }
            
            return $rows;
        }
        
        /**
         * @return Provider|null
         */
        public function getProvider(){
            return $this->_dataProvider;
        }
        
        /**
         * @return string
         */
        public function getName(){
            return $this->_name;
        }
        
        /**
         * @return string|null
         */
        public function getPK(){
            return $this->_pk;
        }
        
        /**
         * @return array
         */
        public function getColumns(){
            return $this->_columns;
        }
        
        /**
         * @return \ArrayIterator|\Traversable
         */
        public function getIterator(){
            return new \ArrayIterator($this->_rows);
        }
        
        public function createRelations(){
            if(!empty($this->_relations)){
                foreach($this->_rows as $row){
                    $row->createRelations($this->_relations);
                }
            }
        }
        
        /**
         * @return bool
         */
        public function isEmpty(){
            return empty($this->_rows);
        }
    }