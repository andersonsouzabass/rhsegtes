<?php
/**
 * DbCadNomeadoUnicpeConcurso Active Record
 * @author  Anderson Souza
 */
class CadUnicpeConcurso extends TRecord
{
    use SystemChangeLogTrait;
    
    const TABLENAME = 'db_cad_nomeado_unicpe_concurso';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('status');
        parent::addAttribute('aprovados_id');
        parent::addAttribute('cep');
        parent::addAttribute('logradouro');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('anotacao');
        parent::addAttribute('system_user_id');
    }


}
