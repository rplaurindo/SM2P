<?php

class SQLAssociativeTablesHelper {
    
    private $associationsMap;
    
    private $map;
    
    private $select;
    
    private $from;
    
    private $where;
    
    function __construct() {
        $this->map = [
            'select' => '',
            'from' => '',
            'where' => ''
        ];
        
        $this->select = [];
        $this->from = [];
        $this->where = [];
    }
    
    function compose(array $data, array $associationsMap) {
        // deve retornar um array associativo com a chave select, from e where
        $this->associationsMap = $associationsMap;
        
        array_walk($data, function ($value, $field) {
            if (array_key_exists($field, $this->associationsMap)) {
                if (array_key_exists('isForeignKeyComesFrom',
                    $this->associationsMap[$field])) {
                        
                        $tableData = $this->associationsMap[$field]['isForeignKeyComesFrom'];
                        $table = $tableData['name'];
                        $primaryKey = $tableData['primaryKey'];
                        
                        array_push($this->select, "$table.$primaryKey");
                        
                        array_push($this->from, "$table");
                        
                        array_push($this->where, "$table.$primaryKey = '{$value}'");
                        
                        // to increment the select composition statement
                        if (array_key_exists('fields', $tableData) &&
                            is_array($tableData['fields']) &&
                            count($tableData['fields'])) {
                                // array_walk($referToTable, function($value, $key) {
                                
                                // });
                            }
                            
                            if (array_key_exists('associatesWithTable', $tableData)) {
                                $associatedTableData = $tableData['associatesWithTable'];
                                $associatedTable = $associatedTableData['name'];
                                $associatedTablePK = $associatedTableData['primaryKey'];
                                $throughTable = $associatedTableData['troughTable'];
                                $associativeTable = $throughTable['name'];
                                $associativeTableFK = $throughTable['associatedForeignKey'];
                                
                                array_push($this->select, "$associatedTable.$associatedTablePK");
                                
                                array_push($this->from, "$associatedTable");
                                array_push($this->from, "$associativeTable");
                                
                                array_push($this->where, "$associativeTable.$field = $table.$primaryKey");
                                array_push($this->where, "$associativeTable.$associativeTableFK = $associatedTable.$associatedTablePK");
                                
                                if (array_key_exists('fields', $associatedTableData) &&
                                    is_array($associatedTableData['fields']) &&
                                    count($associatedTableData['fields'])) {
                                        // array_walk($referToTable, function($value, $key) {
                                        
                                        // });
                                    }
                            }
                    }
                    
            }
            
        });
            
            $this->map['select'] = implode(", ", $this->select);
            $this->map['from'] = implode(", ", $this->from);
            $this->map['where'] = implode(" AND ", $this->where);
            
            return $this->map;
    }
}

// mapa que deve ser previamente definido
$associationsMap = array(
    'ocorrencia_id' => [
        'isForeignKeyComesFrom' => [
            'name' => 'ocorrencias',
            'primaryKey' => 'id',
            'fields' => [],
            
            'associatesWithTable' => [
                'name' => 'boletins_de_ocorrencias',
                'primaryKey' => 'id',
                'fields' => [],
                
                'troughTable' => [
                    'name' => 'boletins_ocorrencias',
                    'associatedForeignKey' => 'boletim_id'
                ]
            ]
        ]
    ]
);

// array associativo montado automaticamente pelo Angular
$data = [
    'ocorrencia_id' => 2
];

$associativeQueryHelper = new SQLAssociativeTablesHelper();
print_r($associativeQueryHelper->compose($data, $associationsMap));
