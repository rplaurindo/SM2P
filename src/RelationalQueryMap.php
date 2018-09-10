<?php

class RelationalQueryMap {
    
    private $map;
    
    private $select;
    
    private $from;
    
    private $where;
    
    private $table;
    
    private $PK;
    
    private $hasMany;
    
    function __construct(array $tableDescription) {
        $this->map = [
            'select' => '',
            'from' => '',
            'where' => ''
        ];
        
        $this->hasMany = [];
        
        $this->select = [];
        $this->from = [];
        $this->where = [];
        
        if (array_key_exists('name', $tableDescription)) {
            $this->table = $tableDescription['name'];
            array_push($this->from, $this->table);
        }
        
        if (array_key_exists('primaryKey', $tableDescription)) {
            $this->PK = $tableDescription['primaryKey'];
        }

    }
    
    function hasMany($table, $tableDescription) {
        
        $this->hasMany[$table] = [];
        $this->hasMany[$table]['primaryKey'] = $tableDescription['primaryKey'];
        
        if (array_key_exists('foreignKey', $tableDescription)) {
            $this->hasMany[$table]['foreignKey'] = $tableDescription['foreignKey'];
        } else if (array_key_exists('through', $tableDescription)) {
            $this->hasMany[$table]['through'] = $tableDescription['through'];
        }

    }

    function get($table, array $data) {
        
        array_push($this->select, "$this->table.$this->PK");
        array_push($this->from, $table);
        
        if (array_key_exists($table, $this->hasMany)) {
            $relatedTableDescription = $this->hasMany[$table];
            $relatedTablePK = $relatedTableDescription['primaryKey'];
            array_push($this->select, "$table.$relatedTablePK");
            
            if (array_key_exists('foreignKey', $relatedTableDescription)) {
                $fk = $relatedTableDescription['foreignKey'];
                $value = $data[$this->PK];
                array_push($this->where, "$this->table.$this->PK = $value");
                array_push($this->where, "$table.$fk = $this->table.$this->PK");
            } else if (array_key_exists('through', $relatedTableDescription)) {
                $associativeTableDescription = $relatedTableDescription['through'];
                $associativeTable = $associativeTableDescription['table'];
                array_push($this->from, $associativeTable);
                
                $associatedTableKey = $associativeTableDescription['keys'][$this->table];
                $associatedRelatedTableKey = $associativeTableDescription['keys'][$table];
                
                $value = $data[$associatedTableKey];
                array_push($this->where, "$this->table.$this->PK = $value");
                array_push($this->where, "$associativeTable.$associatedTableKey = $this->table.$this->PK");
                array_push($this->where, "$associativeTable.$associatedRelatedTableKey = $table.$relatedTablePK");
            }
            
            $this->map['where'] = implode(" AND ", $this->where);
        }
        
        $this->map['select'] = implode(", ", $this->select);
        $this->map['from'] = implode(", ", $this->from);
        
        return $this->map;
    }
}

// array associativo montado automaticamente pelo Angular
$data = [
    'ocorrencia_id' => 2,
    'id' => 2
];

$tableDescription = [
    'name' => 'ocorrencias',
    'primaryKey' => 'id'
];

$relatedTableDescription = [
    'primaryKey' => 'id',
    
//     'foreignKey' => 'ocorrencia_id',
    
    'through' => [
        'table' => 'boletins_ocorrencias',
        'keys' => [
            'ocorrencias' => 'ocorrencia_id',
            'boletins_de_ocorrencias' => 'boletim_de_ocorrencia_id'
        ]
    ]
    
];

$relationalQueryHelper = new RelationalQueryMap($tableDescription);
$relationalQueryHelper->hasMany('boletins_de_ocorrencias', $relatedTableDescription);

print_r($relationalQueryHelper->get('boletins_de_ocorrencias', $data));
