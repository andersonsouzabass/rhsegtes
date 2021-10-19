<?php
/**
 * Cliente Active Record
 * @author  Anderson Lopes Souza (81) 997032438
 */
class Servidor extends TRecord
{
    const TABLENAME = 'servidor';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    const CACHECONTROL = 'TAPCache';
    
    const CREATEDAT = 'created_at';
    const UPDATEDAT = 'updated_at';

    private $contatos;
            
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        
        //Dados pessoais
        
        parent::addAttribute('nome'); //ok
        parent::addAttribute('nome_social'); //ok
        parent::addAttribute('cpf'); //ok
        parent::addAttribute('dt_nascimento'); //ok
        parent::addAttribute('tipo_doc'); //ok        
        parent::addAttribute('doc');   //ok
        parent::addAttribute('emissor');   //ok
        parent::addAttribute('estado_doc');     //ok            
        parent::addAttribute('sexo'); //ok
        parent::addAttribute('estado_civil');  //ok
        parent::addAttribute('ativo'); //ok
        
        //Endereço
        
        parent::addAttribute('cep');
        parent::addAttribute('logradouro');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
    }

    public function addContato(Contato $object)
    {     
        $this->contatos[] = $object;   
    }
   
   
    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        //$this->skills = array();
        $this->contatos = array();
    }
    
    /**
     * Method addContact
     * Add a Contact to the Customer
     * @param $object Instance of Contact
     */
    public function addContact(Contato $object)
    {
        $this->contatos[] = $object;
    }
    
    /**
     * Method getContacts
     * Return the Customer' Contact's
     * @return Collection of Contact
     */
    public function getContacts()
    {
        return $this->contatos;
    }

    public function getContatos()
    {
        return Contato::find($this->servidor_id);
        //return $this->contatos;
    }


    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        $this->contatos = parent::loadComposite('Contato', 'servidor_id', $id);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();    
        parent::saveComposite('Contato', 'servidor_id', $this->id, $this->contatos);
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('Contato', 'servidor_id', $id);
    
        // delete the object itself
        parent::delete($id);
    }

}
