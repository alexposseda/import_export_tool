<?php
    
    namespace Formatter;
    class FormatterJson extends Formatter
    {
        /**
         * @inheritdoc
         */
        public function formatTo($data){
            $this->_formattedData = json_encode($data);
            
            return $this;
        }
    
        /**
         * @inheritdoc
         */
        public function formatFrom($path){
            if(!file_exists($path)){
                throw new \Exception('File not exists ['.$path.']');
            }
            $data = file_get_contents($path);
            return json_decode($data, true);
        }
    
        /**
         * @inheritdoc
         */
        public function save($path){
            $fileName = 'export-'.date('Y-m-d\TH:i:s').'.json';
            $f = fopen($path.DIRECTORY_SEPARATOR.$fileName, 'w');
            if(!$f){
                throw new \Exception('Cannot open file ['.$path.DIRECTORY_SEPARATOR.$fileName.']');
            }
            fwrite($f, $this->_formattedData);
            fclose($f);
        }
    }