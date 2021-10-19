<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class Cargo extends TRecord
{
    const TABLENAME = 'cargo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('especialidade');
        parent::addAttribute('ativo');
    }

 /**
     * Add um fornecedor ao usuário
     * @param $object Instance of Pessoa
     */
    public function addEspecialidadeCargo(Especialidade $especialidade)
    {
        $object = new EspecialidadeCargo;
        $object->especialidade_id = $especialidade->id;
        $object->cargo_id = $this->id;
        $object->store();
    }
    
    /**
     * Return the user' fornecedores
     * @return Collection of Pessoa
     */
    public function getEspecialidadeCargo()
    {
        return parent::loadAggregate('Especialidade', 'EspecialidadeCargo', 'cargo_id', 'especialidade_id', $this->id);
    }
}
