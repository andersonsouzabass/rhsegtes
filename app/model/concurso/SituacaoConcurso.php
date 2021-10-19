<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class SituacaoConcurso extends TRecord
{
    use SystemChangeLogTrait;
    
    const TABLENAME = 'situacaoconcurso';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    const CREATEDAT = 'created_at';
    const UPDATEDAT = 'updated_at';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo'); 
        parent::addAttribute('system_user_id_created'); 
        parent::addAttribute('system_user_id_updated'); 
    }
}
