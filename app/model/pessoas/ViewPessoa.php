<?php
/**
 * Faltas Active Record
 * @author  <Anderson Souza - (81) 99703-2438>
 */
class ViewPessoa extends TRecord
{
    const TABLENAME = 'view_unidades';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
   
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        //parent::addAttribute('bairro');
        parent::addAttribute('ativo');        
        //parent::addAttribute('perfil');
        parent::addAttribute('geres');
    }
}
