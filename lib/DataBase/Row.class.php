<?php
    
    namespace DataBase;
    class Row implements \IteratorAggregate
    {
        protected $_relations = [];
        protected $_table     = null;
        protected $_cells     = [];
        protected $_saved     = false;
        
        /**
         * Row constructor.
         *
         * @param Table $table
         * @param array $data
         *
         * @throws \Exception
         */
        public function __construct(Table $table, $data){
            $this->_table = $table;
            
            foreach($table->getColumns() as $fieldName => $fieldParams){
                $field = new Cell($fieldName, $this, $fieldParams);
                if(isset($data[$fieldName])){
                    $field->setValue($data[$fieldName]);
                }
                $this->_cells[$fieldName] = $field;
            }
        }
        
        /**
         * @param string $key
         *
         * @return Cell|mixed
         * @throws \Exception
         */
        public function __get($key){
            if(method_exists($this, 'get' . ucfirst($key))){
                $m = 'get' . ucfirst($key);
                
                return $this->$m();
            }
            
            if(isset($this->_cells[$key])){
                return $this->_cells[$key];
            }
            
            foreach($this->_cells as $field){
                if($field->getAlias() == $key){
                    return $field;
                }
            }
            
            throw new \Exception('Undefined column [' . $key . '] in [' . $this->_table->getName() . ']');
        }
        
        /**
         * @return Table
         */
        public function getTable(){
            return $this->_table;
        }
        
        /**
         * @throws \Exception
         */
        public function save(){
            if(!$this->_saved){
                foreach($this->getRelations() as $relation){
                    if($relation->getType() == 'dependsOn'){
                        $relation->getRelatedRow()
                                 ->save();
                        $relation->getBaseCell()->setValue($relation->getRelatedCell()
                                                                    ->getValue());
                        
                    }
                    
                    if($relation->getType() == 'linkedThrough'){
                        $relation->getBaseCell()
                                 ->getRow()
                                 ->save();
                    }
                }
                
                $row = [];
                foreach($this as $cell){
                    $row[$cell->getName()] = $cell->getValue();
                }
                
                $result = $this->_table->getProvider()
                                       ->save($row,
                                              $this->getTable()
                                                   ->getPK());
                
                $this->_saved = true;
                $this->update($result);
                
                foreach($this->getRelations() as $relation){
                    if($relation->getType() == 'connectedWith'){
                        $relation->getRelatedCell()
                                 ->setValue($relation->getBaseCell()
                                                     ->getValue());
                        $relation->getRelatedRow()
                                 ->save();
                    }
                }
            }
        }
        
        /**
         * @param array $data
         *
         * @return $this
         * @throws \Exception
         */
        protected function update($data){
            foreach($this->_cells as $cell){
                if(isset($data[$cell->getName()])){
                    $cell->setValue($data[$cell->getName()]);
                }
            }
            
            return $this;
        }
        
        /**
         * @param array $relationParams
         *
         * @throws \Exception
         */
        public function createRelations($relationParams){
            if(empty($this->_relations)){
                foreach($relationParams as $param){
                    $type     = $param['type'];
                    $baseCell = $this->$param['field'];
                    if(is_null($baseCell->getValue())){
                        continue;
                    }
                    
                    $relatedTable = DataBase::getTable($param['table']['name']);
                    switch($type){
                        case 'dependsOn':
                            $relatedRow = $relatedTable->findOne($param['table']['field'], $baseCell->getValue());
                            if(empty($relatedRow)){
                                throw new \Exception('Cannot create Relation!');
                            }
                            $relation = new Relation($type, $baseCell, $relatedRow->$param['table']['field']);
                            $baseCell->addRelation($relatedTable->getName(), $relation);
                            
                            $this->_relations[$relatedTable->getName()] = $relation;
                            break;
                        case 'connectedWith':
                            $relatedRows = $relatedTable->findAll($param['table']['field'], $baseCell->getValue());
                            foreach($relatedRows as $relatedRow){
                                $relation = new Relation($type, $baseCell, $relatedRow->$param['table']['field']);
                                
                                $baseCell->addRelation($relatedTable->getName(), $relation);
                                $this->_relations[$relatedTable->getName()][] = $relation;
                            }
                            break;
                        case 'linkedThrough':
                            $linkTable = DataBase::getTable($param['link']['name']);
                            $linkRow   = $linkTable->findOne($param['link']['field'], $baseCell->getValue());
                            if(!$linkRow){
                                continue;
                            }
                            $relatedRows = $relatedTable->findAll($param['table']['field'],
                                                                  $linkRow->{$param['link']['link_field']}->getValue());
                            
                            foreach($relatedRows as $relatedRow){
                                $relation = new Relation($type,
                                                         $linkRow->{$param['link']['link_field']},
                                                         $relatedRow->$param['table']['field']);
                                
                                $baseCell->addRelation($relatedTable->getName(), $relation);
                                $this->_relations[$relatedTable->getName()][] = $relation;
                            }
                            break;
                        default:
                            throw new \Exception('Unknown relation type!');
                            break;
                    }
                }
            }
        }
        
        /**
         * @return Relation[]
         */
        public function getRelations(){
            $relations = [];
            foreach($this->_relations as $v){
                if(is_array($v)){
                    foreach($v as $r){
                        $relations[] = $r;
                    }
                }else{
                    $relations[] = $v;
                }
            }
            
            return $relations;
        }
        
        /**
         * @return \ArrayIterator|\Traversable
         */
        public function getIterator(){
            return new \ArrayIterator($this->_cells);
        }
    }