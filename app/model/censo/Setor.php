<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class Setor extends TRecord
{
    const TABLENAME = 'db_setor_censo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }

}