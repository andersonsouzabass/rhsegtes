<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class view_salario extends TRecord
{
    const TABLENAME = 'view_salario_base';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cargo');
        parent::addAttribute('base');
        parent::addAttribute('inicio');
        parent::addAttribute('regime');
    }


}
