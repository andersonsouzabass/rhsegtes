<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class CargoFolha extends TRecord
{
    const TABLENAME = 'cargo_folha';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }

}
