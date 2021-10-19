<?php

class ViewProcessos extends TRecord
{
    const TABLENAME = 'view_processos';
    const PRIMARYKEY= 'id';
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('assunto');
        parent::addAttribute('dt_entrada');  
        parent::addAttribute('dt_prazo');  
        parent::addAttribute('prazo');      
        parent::addAttribute('interessado');
        parent::addAttribute('servidor');
        parent::addAttribute('matricula');
        parent::addAttribute('cpf');
        parent::addAttribute('observacao');
        parent::addAttribute('status');
    }

}
