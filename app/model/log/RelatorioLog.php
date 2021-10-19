<?php
/**
 * RelatorioLog Active Record
 * @author  <your-name-here>
 */
class RelatorioLog extends TRecord
{
    const TABLENAME = 'system_change_log';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('logdate');
        parent::addAttribute('login');
        parent::addAttribute('tablename');
        parent::addAttribute('primarykey');
        parent::addAttribute('pkvalue');
        parent::addAttribute('operation');
        parent::addAttribute('columnname');
        parent::addAttribute('oldvalue');
        parent::addAttribute('newvalue');
        parent::addAttribute('access_ip');
        parent::addAttribute('transaction_id');
        parent::addAttribute('log_trace');
        parent::addAttribute('session_id');
        parent::addAttribute('class_name');
        parent::addAttribute('php_sapi');
        parent::addAttribute('log_year');
        parent::addAttribute('log_month');
        parent::addAttribute('log_day');
    }


}
