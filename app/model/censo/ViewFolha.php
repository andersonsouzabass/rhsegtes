<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class ViewFolha extends TRecord
{
    const TABLENAME = 'view_servidor_folha';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('CPF');
        parent::addAttribute('data_admissao');
        parent::addAttribute('matricula');
        parent::addAttribute('cargo');
        parent::addAttribute('funcao');
        parent::addAttribute('unidade_1');
        parent::addAttribute('unidade_2_id');
        parent::addAttribute('unidade_2');
        parent::addAttribute('unidade_folha');
        parent::addAttribute('situacao_folha_id');
        parent::addAttribute('status_censo');
    }

}
