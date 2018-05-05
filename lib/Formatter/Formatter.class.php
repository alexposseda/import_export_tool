<?php
    
    namespace Formatter;
    abstract class Formatter
    {
        protected $_config   = [];
        protected $_scenario = 'table';
        
        protected $_formattedData;
        
        /**
         * Formatter constructor.
         *
         * @param array $config
         */
        protected function __construct($config){
            $this->_config = $config;
        }
        
        /**
         * @param array $data
         *
         * @return $this
         * @throws \Exception
         */
        abstract public function formatTo($data);
        
        /**
         * @param string $path
         *
         * @return array
         * @throws \Exception
         */
        abstract public function formatFrom($path);
        
        /**
         * @param string $path
         *
         * @throws \Exception
         */
        abstract public function save($path);
        
        /**
         * @return string
         */
        public function getScenario(){
            return $this->_scenario;
        }
        
        /**
         * @param string $scenario
         */
        public function setScenario($scenario){
            $this->_scenario = $scenario;
        }
        
        /**
         * @return mixed
         */
        public function getFormattedData(){
            return $this->_formattedData;
        }
        
        //        protected function createLine($data, $prefix = ''){
        //            $line = [];
        //            foreach($data as $key => $value){
        //                if($key == 'relations'){
        //                    $relations = $value;
        //                    foreach($relations as $relKey => $relValue){
        //                        if(in_array($relKey, $this->_config['line_format']['excluded_relations'])){
        //                            continue;
        //                        }
        //                        if(in_array($relKey, array_keys($this->_config['line_format']['custom_format']))){
        //                            foreach($relValue as $i => $rel){
        //                                $ki   = call_user_func($this->_config['line_format']['custom_format'][$relKey], $rel);
        //                                $line = array_merge($line,
        //                                                    $this->createLine($rel, $prefix . $relKey . '.' . $ki . '.'));
        //                            }
        //                        }else{
        //                            if(isset($relValue[0])){
        //                                foreach($relValue as $i => $rel){
        //                                    $line = array_merge($line,
        //                                                        $this->createLine($rel, $prefix . $relKey . '.' . $i . '.'));
        //                                }
        //                            }else{
        //                                $line = array_merge($line, $this->createLine($relValue, $prefix . $relKey . '.'));
        //                            }
        //                        }
        //                    }
        //                }else{
        //                    if(is_array($value)){
        //                        $line = array_merge($line, $this->createLine($value, $prefix . $key . '.'));
        //                    }else{
        //                        $line[$prefix . $key] = $value;
        //                    }
        //                }
        //            }
        //
        //            return $line;
        //        }
        
        /**
         * @param string $type
         * @param array  $config
         *
         * @return self
         * @throws \Exception
         */
        public static function getFormatter($type, $config){
            $className = __NAMESPACE__ . '\Formatter' . ucfirst(strtolower($type));
            if(class_exists($className)){
                return new $className($config);
            }
            
            throw new \Exception('Formatter [' . $type . '] not exists!');
        }
    }