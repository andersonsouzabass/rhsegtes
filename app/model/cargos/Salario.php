<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class Salario extends TRecord
{
    const TABLENAME = 'salario_base';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cargo_id');
        parent::addAttribute('regime_trabalho_id');
        parent::addAttribute('valor');
        parent::addAttribute('inicio');
    }


}
