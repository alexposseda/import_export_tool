<?php
    
    namespace Transfer;
    
    use DataBase\DataBase;
    use DataBase\Row;
    
    class Export
    {
        protected $_db        = null;
        
        /**
         * Export constructor.
         *
         * @param DataBase $db
         */
        public function __construct(DataBase $db){
            $this->_db = $db;
        }
        
        /**
         * @return array
         */
        public function asTables(){
            $data = [];
            foreach($this->_db as $tableName => $table){
                if($table->isEmpty()){
                    continue;
                }
                $data[$tableName] = [];
                foreach($table as $i => $row){
                    foreach($row as $cell){
                        $data[$tableName][$i][$cell->getName()] = $cell->getValue();
                    }
                }
            }
            
            return $data;
        }
        
        /**
         * @param string $entity
         *
         * @return array
         * @throws \Exception
         */
        public function asEntities($entity){
            $data  = [];
            $table = DataBase::getTable($entity);
            foreach($table as $i => $row){
                $data[$i] = $this->createEntity($row);
            }
            
            return $data;
        }
        
        /**
         * @param Row $row
         *
         * @return array
         */
        protected function createEntity(Row $row){
            $res = [];
            foreach($row as $cell){
                $res[$cell->getName()] = $cell->getValue();
            }
            
            $relations = $row->getRelations();
            if(!empty($relations)){
                $res['relations'] = [];
                
                foreach($relations as $key => $relation){
                    if(is_array($relation)){
                        foreach($relation as $rel){
                            $res['relations'][$key][] = $this->createEntity($rel->getRelatedRow());
                        }
                    }else{
                        $res['relations'][$key] = $this->createEntity($relation->getRelatedRow());
                    }
                }
            }
            
            return $res;
        }
    }