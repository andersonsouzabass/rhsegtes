<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class Simbolo extends TRecord
{
    const TABLENAME = 'simbolo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }


}
