<?php
/**
 * Atribuir Fornecedor ao Usuário
 *
 *
 * @author     Anderson Souza
 */
class UsuarioUnidade extends TRecord
{
    const TABLENAME = 'db_censo_usuario_unidade2';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('system_user_id');
        parent::addAttribute('folha_unidade_2_id');
    }
}