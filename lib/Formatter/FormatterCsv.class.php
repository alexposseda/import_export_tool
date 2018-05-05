<?php
    
    namespace Formatter;
    
    class FormatterCsv extends Formatter
    {
        /**
         * @inheritdoc
         */
        public function formatTo($data){
            switch($this->_scenario){
                case 'table':
                    foreach($data as $tableName => $rows){
                        $this->_formattedData[$tableName] = [
                            'columns' => array_keys($rows[0]),
                            'rows'    => $rows
                        ];
                    }
                    break;
                default:
                    throw new \Exception('Undefined scenario [' . $this->_scenario . ']');
            }
            
            return $this;
        }
        
        /**
         * @inheritdoc
         */
        public function formatFrom($path){
            $data = [];
            switch($this->_scenario){
                case 'table':
                    $dir = new \DirectoryIterator($path);
                    foreach($dir as $file){
                        if($file->isDot() or $file->isDir() or $file->getExtension() != 'csv'){
                            continue;
                        }
                        
                        $fileName         = $file->getFilename();
                        $tableName        = explode('-', $fileName)[1];
                        $data[$tableName] = [];
                        $tableData        = file($path . DIRECTORY_SEPARATOR . $fileName . '.csv');
                        $columns          = str_getcsv(array_shift($tableData));
                        foreach($tableData as $i => $line){
                            $row = str_getcsv($line);
                            foreach($row as $j => $v){
                                $data[$tableName][$i][$columns[$j]] = $v;
                            }
                        }
                    }
                    break;
                default:
                    throw new \Exception('Undefined scenario [' . $this->_scenario . ']');
            }
            
            return $data;
        }
        
        /**
         * @inheritdoc
         */
        public function save($path){
            $saveMethod = 'saveAs' . ucfirst($this->_scenario);
            if(!method_exists($this, $saveMethod)){
                throw new \Exception('Invalid scenario [' . $this->_scenario . ']');
            }
            
            $this->$saveMethod($path);
        }
        
        /**
         * @param string $path
         *
         * @throws \Exception
         */
        protected function saveAsTable($path){
            $path .= DIRECTORY_SEPARATOR . date('Y-m-d\TH:i:s');
            if(!is_dir($path)){
                mkdir($path, 0777);
            }
            foreach($this->_formattedData as $tableName => $table){
                $fileName = 'export-' . $tableName . '.csv';
                $f        = fopen($path . DIRECTORY_SEPARATOR . $fileName, 'w');
                if(!$f){
                    throw new \Exception('Cannot open file [' . $path . DIRECTORY_SEPARATOR . $fileName . ']');
                }
                fputcsv($f, $table['columns']);
                foreach($table['rows'] as $row){
                    fputcsv($f, $row);
                }
                fclose($f);
            }
        }
    }