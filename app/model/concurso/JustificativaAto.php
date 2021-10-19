<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class JustificativaAto extends TRecord
{
    use SystemChangeLogTrait;
    
    const TABLENAME = 'justificativa_ato';
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
