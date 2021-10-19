<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class Censo extends TRecord
{
    const TABLENAME = 'db_censo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('status');
        parent::addAttribute('db_folha_id');
        
        parent::addAttribute('pessoa_id');
        parent::addAttribute('cargo_id');
        parent::addAttribute('especialidade_id');
        parent::addAttribute('funcao_id');
        parent::addAttribute('setor_id');
        
        parent::addAttribute('mat_origem');
        parent::addAttribute('orgaocedente_id');
        parent::addAttribute('cargo_origem_id');
        parent::addAttribute('especialidade_origem_id');
        
        parent::addAttribute('cep');
        parent::addAttribute('logradouro');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        
        parent::addAttribute('anotacao');
    }
}
