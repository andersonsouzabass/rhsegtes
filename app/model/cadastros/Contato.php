<?php
/**
 * Contatos Active Record
 * @author  Anderson Lopes Souza (81) 997032438
 */
class Contato extends TRecord
{
    const TABLENAME = 'contato';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    private $contatos;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo');
        
        parent::addAttribute('contato');
        parent::addAttribute('responsavel');
        parent::addAttribute('observacao');

        parent::addAttribute('principal');
        parent::addAttribute('cpf');
    }
   
    
    public function get_servidor()
    {
        return Servidor::find($this->servidor_id);
    }


}
