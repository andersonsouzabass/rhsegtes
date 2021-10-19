<?php
/**
 * Grupo Active Record
 * @author  <your-name-here>
 */
class Processos extends TRecord
{
    const TABLENAME = 'bd_processos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    const CREATEDAT = 'created_at';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo_assunto_id');
        parent::addAttribute('dt_entrada');   
        parent::addAttribute('dt_prazo');        
        parent::addAttribute('interessado');
        parent::addAttribute('servidor');
        parent::addAttribute('matricula');
        parent::addAttribute('cpf');
        parent::addAttribute('observacao');
        parent::addAttribute('status');
    }
}
