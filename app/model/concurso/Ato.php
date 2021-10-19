<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class Ato extends TRecord
{
    use SystemChangeLogTrait;
    
    const TABLENAME = 'ato';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ato');
        parent::addAttribute('justificativa_ato_id');
        parent::addAttribute('dt_publicacao');
        parent::addAttribute('dt_nomeacao');
        parent::addAttribute('obs');

        parent::addAttribute('prazo_posse_ato');
        parent::addAttribute('prazo_posse_exerc');
        parent::addAttribute('prazo_legal_ato');
        parent::addAttribute('prazo_legal_exerc');

    }

    public function get_justificativa()
    {
        return JustificativaAto::find($this->JustificativaAto_id);
    }

}
