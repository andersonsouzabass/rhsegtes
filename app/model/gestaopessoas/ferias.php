<?php
/**
 * Faltas Active Record
 * @author  <Anderson Souza - (81) 99703-2438>
 */
class Ferias extends TRecord
{
    const TABLENAME = 'ferias';
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
        parent::addAttribute('servidor_vinculo_id');
        parent::addAttribute('dt_inicio');
        parent::addAttribute('dt_fim');        
        parent::addAttribute('observacao');
        
    }
}
