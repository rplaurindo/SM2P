<?php

class RelationalQueryHelper {
    
    private $map;
    
    private $select;
    
    private $from;
    
    private $where;
    
    private $table;
    
    private $PK;
    
    private $relatedTable;
    
    private $relatedTablePK;
    
    function __construct(array $tableDescription, array $relatedTableDescription) {
        $this->map = [
            'select' => '',
            'from' => '',
            'where' => ''
        ];
        
        $this->select = [];
        $this->from = [];
        $this->where = [];
        
        if (array_key_exists('name', $tableDescription)) {
            $this->table = $tableDescription['name'];
        }
        
        if (array_key_exists('primaryKey', $tableDescription)) {
            $this->PK = $tableDescription['primaryKey'];
        }
        
        if (array_key_exists('name', $relatedTableDescription)) {
            $this->relatedTable = $relatedTableDescription['name'];
        }
        
        if (array_key_exists('primaryKey', $relatedTableDescription)) {
            $this->relatedTablePK = $relatedTableDescription['primaryKey'];
        }

    }

    function getAssociativeMap(array $associativeTableDescription, array $data) {
        
        if (array_key_exists('name', $associativeTableDescription)) {
            $associativeTable = $associativeTableDescription['name'];
        }
        
        if (array_key_exists($this->table, $associativeTableDescription)) {
            $associativeTableKey = $associativeTableDescription[$this->table];
        }
        
        if (array_key_exists($this->relatedTable, $associativeTableDescription)) {
            $associativeRelatedTableKey = $associativeTableDescription[$this->relatedTable];
        }
        
        array_push($this->from, "$associativeTable");
                
        array_push($this->select, "$this->table.$this->PK");
        array_push($this->from, "$this->table");
        
        array_push($this->select, "$this->relatedTable.$this->relatedTablePK");
        array_push($this->from, "$this->relatedTable");
        
        $value = $data[$associativeTableKey];
        array_push($this->where, "$this->table.$this->PK = '$value'");
        array_push($this->where, "$associativeTable.$associativeTableKey = $this->table.$this->PK");
        array_push($this->where, "$associativeTable.$associativeRelatedTableKey = $this->relatedTable.$this->relatedTablePK");
            
        $this->map['select'] = implode(", ", $this->select);
        $this->map['from'] = implode(", ", $this->from);
        $this->map['where'] = implode(" AND ", $this->where);
        
        return $this->map;
    }
}

$tableDescription = [
    'name' => 'ocorrencias',
    'primaryKey' => 'id'
];

$relatedTableDescription = [
    'name' => 'boletins_de_ocorrencias',
    'primaryKey' => 'id'
];

// array associativo montado automaticamente pelo Angular
$data = [
    'ocorrencia_id' => 2
];

$relationalQueryHelper = new RelationalQueryHelper($tableDescription, $relatedTableDescription);

$associativeTableDescription = [
    'name' => 'boletins_ocorrencias',
    'ocorrencias' => 'ocorrencia_id',
    'boletins_de_ocorrencias' => 'boletim_de_ocorrencia_id'
];

print_r($relationalQueryHelper->getAssociativeMap($associativeTableDescription, $data));
