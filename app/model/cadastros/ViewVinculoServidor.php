<?php

class ViewVinculoServidor extends TRecord
{
    const TABLENAME = 'view_vinculo_servidor';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);       
        parent::addAttribute('matricula');
        parent::addAttribute('s_id');
        parent::addAttribute('vinculo_id');
        parent::addAttribute('cpf');
        parent::addAttribute('nome');
        parent::addAttribute('unidade');
        parent::addAttribute('funcao');
        parent::addAttribute('vinculo');
        parent::addAttribute('cargo');
        parent::addAttribute('especialidade');
        parent::addAttribute('dt_admissao');
        parent::addAttribute('ativo');
        parent::addAttribute('situacao');
    }
}
