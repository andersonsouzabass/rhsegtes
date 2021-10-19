<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class Vinculo extends TRecord
{
    const TABLENAME = 'vinculo';
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
