<?php

class TiposAtendimento extends TRecord {
    const TABLENAME = 'tipos_atendimento';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max';

    public function __construct($id = NULL) {
        parent::__construct($id);
        parent::addAttribute('descricao');
        parent::addAttribute('deleted_at');
    }

    public function store() {
        $repo = new TRepository('TiposAtendimento');

        $obj = $repo->where('deleted_at', 'is', NULL)
                        ->where('descricao', 'like', $this->descricao)
                        ->first();

        if($obj ) {
            if($obj->id != $this->id)
                throw new Exception('Tipo já cadastrado');
        }
            
        
        parent::store();
    }

    public static function findById($id) {
        $repo = new TRepository('TiposAtendimento');
        $obj = $repo->where('deleted_at', 'is', NULL)
                        ->where('id', '=', $id)
                        ->first();
        return $obj;
    }

    public function onDelete($id) {
        $this->id = $id;
        $this->deleted_at = time();
        parent::store();
    }
}