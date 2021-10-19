<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de Souza
 */
class OrgaoCedente extends TRecord
{
    const TABLENAME = 'orgao_cedente';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }

}
