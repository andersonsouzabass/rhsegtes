<?php
/**
 * Grupo Active Record
 * @author  <your-name-here>
 */
class Geres extends TRecord
{
    const TABLENAME = 'geres';
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
