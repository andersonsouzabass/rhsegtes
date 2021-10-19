<?php
/**
 * NomeadosView Active Record
 * @author  Anderson Souza
 */
class NomeadosView extends TRecord
{
    const TABLENAME = 'view_nomeados';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('aprovados_id');
        parent::addAttribute('matricula');
        parent::addAttribute('dt_posse');
        parent::addAttribute('dt_encaminhado');
        parent::addAttribute('dt_efetivo_exercicio');
        parent::addAttribute('unidade');
        parent::addAttribute('nome');
        parent::addAttribute('concurso');
        parent::addAttribute('classif');
        parent::addAttribute('cargo');
        parent::addAttribute('cpf');
        parent::addAttribute('insc');
        parent::addAttribute('email');
        parent::addAttribute('ato_id');
        parent::addAttribute('ato');
        parent::addAttribute('dt_nomeacao');
        parent::addAttribute('dt_publicacao');
        parent::addAttribute('prazo_posse_ato');
        parent::addAttribute('prazo_posse_exerc');
        parent::addAttribute('prazo_legal_ato');
        parent::addAttribute('prazo_legal_exerc');
        parent::addAttribute('data_prazo_posse_ato');
        parent::addAttribute('data_prazo_legal_ato');
        parent::addAttribute('data_prazo_posse_exerc');
        parent::addAttribute('data_prazo_legal_exerc');
        parent::addAttribute('cargo_id');
        parent::addAttribute('cargo_sis');
        parent::addAttribute('especialidade_id');
        parent::addAttribute('especialidade_sis');
        parent::addAttribute('situacao_id');
        parent::addAttribute('situacao');
        parent::addAttribute('regime');
        parent::addAttribute('geres');
        parent::addAttribute('justificativa_ato');
        parent::addAttribute('reconvocado');
    }


}
