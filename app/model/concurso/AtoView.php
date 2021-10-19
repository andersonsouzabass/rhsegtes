<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class AtoView extends TRecord
{
    const TABLENAME = 'view_ato';
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
        parent::addAttribute('justificativa');
        parent::addAttribute('dt_publicacao');
        parent::addAttribute('dt_nomeacao');
        parent::addAttribute('obs');
    }

}
