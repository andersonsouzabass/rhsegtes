<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class Unidade2 extends TRecord
{
    const TABLENAME = 'folha_unidade_folha';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }

}