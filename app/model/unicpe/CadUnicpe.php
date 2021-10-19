<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class CadUnicpe extends TRecord
{
    const TABLENAME = 'db_cad_servidor_gap';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    const CREATEDAT = 'created_at';
    const UPDATEDAT = 'updated_at';
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('status');
        parent::addAttribute('db_folha_id');
        
        parent::addAttribute('pessoa_id');
        parent::addAttribute('cargo_id');
        parent::addAttribute('especialidade_id');
        parent::addAttribute('funcao_comissionado_id');
        parent::addAttribute('situacao_folha_id');
        parent::addAttribute('vinculo_id');
        
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
        
        parent::addAttribute('periodo_gozo_ferias');
        parent::addAttribute('exerc_funcao_grat_com');
        parent::addAttribute('afastado');
        
        parent::addAttribute('system_user_id');
        
    }
}
