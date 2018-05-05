<?php
    
    namespace Transfer;
    
    use DataBase\DataBase;
    
    class Import
    {
        protected $_db = null;
    
        /**
         * Import constructor.
         *
         * @param array $data
         * @param array $config
         */
        public function __construct($data, $config){
            $this->_db = new DataBase($config);
            try{
                $this->_db->loadData($data);
            }catch(\Exception $e){
                die('DB loadData Error: ' . $e->getMessage());
            }
        }
    
        /**
         * @return DataBase
         */
        public function save(){
            foreach ($this->_db as $table) {
                foreach ($table as $row) {
                    $row->save();
                }
            }
            
            return $this->_db;
        }
    }