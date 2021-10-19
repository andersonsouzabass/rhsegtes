<?php

class ViewEspecialidadeCargo extends TRecord
{
    const TABLENAME = 'view_especialidade_cargo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);       
        parent::addAttribute('nome');
        parent::addAttribute('cargo_id');
    }
}
