<?php
/**
 * FolhaFuncaoFicha Active Record
 * @author  <your-name-here>
 */
class FolhaFuncaoFicha extends TRecord
{
    const TABLENAME = 'folha_funcao_ficha';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ativo');
    }


}
