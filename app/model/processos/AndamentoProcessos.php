<?php

class AndamentoProcessos extends TRecord
{
    const TABLENAME = 'bd_processos_andamento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    const CREATEDAT = 'created_at';
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('bd_processos_id');
        parent::addAttribute('dt_registro');   
        parent::addAttribute('despacho');        
        parent::addAttribute('status');
        parent::addAttribute('system_user_id');
    }
}