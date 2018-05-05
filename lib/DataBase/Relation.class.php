<?php
    
    namespace DataBase;
    class Relation
    {
        protected $_baseCell    = null;
        protected $_relatedCell = null;
        protected $_type;
        
        /**
         * Relation constructor.
         *
         * @param string $type
         * @param Cell   $baseCell
         * @param Cell   $relatedCell
         */
        public function __construct($type, Cell $baseCell, Cell $relatedCell){
            $this->_type = $type;
            
            $this->_baseCell    = $baseCell;
            $this->_relatedCell = $relatedCell;
        }
        
        /**
         * @return Row
         */
        public function getRelatedRow(){
            return $this->_relatedCell->getRow();
        }
        
        /**
         * @return string
         */
        public function getType(){
            return $this->_type;
        }
        
        /**
         * @return Cell
         */
        public function getBaseCell(){
            return $this->_baseCell;
        }
        
        /**
         * @return Cell
         */
        public function getRelatedCell(){
            return $this->_relatedCell;
        }
    }