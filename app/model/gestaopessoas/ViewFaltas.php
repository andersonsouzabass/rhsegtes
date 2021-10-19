<?php
/**
 * Faltas Active Record
 * @author  <Anderson Souza - (81) 99703-2438>
 */
class ViewFaltas extends TRecord
{
    const TABLENAME = 'view_faltas';
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
        parent::addAttribute('dt_final');        
        parent::addAttribute('justificada');
        parent::addAttribute('justificativa');
        parent::addAttribute('matricula');
        parent::addAttribute('servidor_id');
        parent::addAttribute('nome');
        parent::addAttribute('unidade');
    }
}
