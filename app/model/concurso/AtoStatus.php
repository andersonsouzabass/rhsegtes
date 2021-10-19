<?php
/**
 * Grupo Active Record
 * @author  Anderson Lopes de SOuza
 */
class AtoStatus extends TRecord
{
    const TABLENAME = 'view_conta_nomeados_status';
    //const PRIMARYKEY= 'id';
   // const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('ato');
        parent::addAttribute('cargo');
        parent::addAttribute('situacao');
        parent::addAttribute('total_sit');
    }
}
