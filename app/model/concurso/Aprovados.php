<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class Aprovados extends TRecord
{
    const TABLENAME = 'aprovados';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ano');
        parent::addAttribute('ato');
        
        parent::addAttribute('insc');
        parent::addAttribute('nome');
        parent::addAttribute('identidade');
        parent::addAttribute('cpf');
        parent::addAttribute('nascimento');
        parent::addAttribute('cod_cargo');
        parent::addAttribute('cargo');
        parent::addAttribute('classif');
        parent::addAttribute('classif_def');
        parent::addAttribute('tipo_def');
        parent::addAttribute('nota_final');
        parent::addAttribute('resultado');
        parent::addAttribute('endereco');
        parent::addAttribute('num');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cep');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('email');
        parent::addAttribute('fone');
        parent::addAttribute('celular');
        parent::addAttribute('formacao_escolaridade');
        parent::addAttribute('nome_da_mae');
    }
}
