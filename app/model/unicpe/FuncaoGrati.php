<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class FuncaoGrati extends TRecord
{
    const TABLENAME = 'db_funcao_comissioado';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }

}
