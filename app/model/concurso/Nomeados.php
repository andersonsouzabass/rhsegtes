<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class Nomeados extends TRecord
{
    use SystemChangeLogTrait;
    
    const TABLENAME = 'nomeado';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    const CREATEDAT = 'created_at';
    const UPDATEDAT = 'updated_at';

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('aprovados_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('geres_id');
        parent::addAttribute('ato_id');
        parent::addAttribute('dt_posse');        
        parent::addAttribute('dt_encaminhado');        
        parent::addAttribute('dt_efetivo_exercicio');        
        parent::addAttribute('situacaoconcurso_id');
        parent::addAttribute('prazo_situacaoconcurso');
        parent::addAttribute('obs');
        parent::addAttribute('cargo_id');
        parent::addAttribute('especialidade_id');
        parent::addAttribute('matricula');
        parent::addAttribute('regime_trabalho_id');
        parent::addAttribute('reconvocado');

        parent::addAttribute('system_user_id_created'); 
        parent::addAttribute('system_user_id_updated');
    }

    

}
