<?php
/**
 * Atribuir Fornecedor ao Usuário
 *
 *
 * @author     Anderson Souza
 */
class EspecialidadeCargo extends TRecord
{
    const TABLENAME = 'especialidade_cargo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('especialidade_id');
        parent::addAttribute('cargo_id');
    }
}
