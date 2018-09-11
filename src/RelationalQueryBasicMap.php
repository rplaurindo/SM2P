<?php

class RelationalQueryBasicMap {
    
    private $map;
    
    private $select;
    
    private $from;
    
    private $where;
    
    private $table;
    
    private $PK;
    
    private $has;
    
    function __construct(array $tableDescription) {
        $this->map = [
            'select' => '',
            'from' => '',
            'where' => ''
        ];
        
        $this->select = new ArrayObject();
        $this->from = new ArrayObject();
        $this->where = new ArrayObject();
        
        if (array_key_exists('name', $tableDescription)) {
            $this->table = $tableDescription['name'];
            $this->from->append($this->table);
        }
        
        if (array_key_exists('primaryKey', $tableDescription)) {
            $this->PK = $tableDescription['primaryKey'];
        }
        
        $this->has = [];

    }
    
    function has($table, $tableDescription) {
        $this->has[$table] = [];
        $this->has[$table]['primaryKey'] = $tableDescription['primaryKey'];
        
        if (array_key_exists('foreignKey', $tableDescription)) {
            $this->has[$table]['foreignKey'] = $tableDescription['foreignKey'];
        } else if (array_key_exists('through', $tableDescription)) {
            $this->has[$table]['through'] = $tableDescription['through'];
        }
    }

    function get($table, array $data) {
                
        $this->append($this->select, "$this->table.$this->PK");
        $this->append($this->from, $table);

        if (array_key_exists($table, $this->has)) {
            $relatedTableDescription = $this->has[$table];
            $relatedTablePK = $relatedTableDescription['primaryKey'];
            $this->append($this->select, "$table.$relatedTablePK");
            
            if (array_key_exists('foreignKey', $relatedTableDescription)) {
                $fk = $relatedTableDescription['foreignKey'];
                $value = $data[$this->PK];
                $this->append($this->where, "$this->table.$this->PK = $value");
                $this->append($this->where, "$table.$fk = $this->table.$this->PK");
            } else if (array_key_exists('through', $relatedTableDescription)) {
                $associativeTableDescription = $relatedTableDescription['through'];
                $associativeTable = $associativeTableDescription['table'];
                $this->append($this->from, $associativeTable);
                
                $associatedTableKey = $associativeTableDescription['keys'][$this->table];
                $associatedRelatedTableKey = $associativeTableDescription['keys'][$table];
                
                $value = $data[$associatedTableKey];
                $this->append($this->where, "$this->table.$this->PK = $value");
                $this->append($this->where, "$associativeTable.$associatedTableKey = $this->table.$this->PK");
                $this->append($this->where, "$associativeTable.$associatedRelatedTableKey = $table.$relatedTablePK");
            }
        }
        
        $this->map['select'] = implode(", ", $this->select->getArrayCopy());
        $this->map['from'] = implode(", ", $this->from->getArrayCopy());
        $this->map['where'] = implode(" AND ", $this->where->getArrayCopy());
        
        return $this->map;
    }
    
    private function append(ArrayObject $list, $statement) {
        
        if (!in_array($statement, $list->getArrayCopy())) {
            $list->append($statement);
        }
        
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
    
    'foreignKey' => 'ocorrencia_id',
    
    'through' => [
        'table' => 'boletins_ocorrencias',
        'keys' => [
            'ocorrencias' => 'ocorrencia_id',
            'boletins_de_ocorrencias' => 'boletim_de_ocorrencia_id'
        ]
    ]
    
];

$relationalQueryBasicMap = new RelationalQueryBasicMap($tableDescription);
$relationalQueryBasicMap->has('boletins_de_ocorrencias', $relatedTableDescription);

print_r($relationalQueryBasicMap->get('boletins_de_ocorrencias', $data));
