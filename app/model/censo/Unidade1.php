<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class Unidade1 extends TRecord
{
    const TABLENAME = 'folha_unidade_1';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }

}