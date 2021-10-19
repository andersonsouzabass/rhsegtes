<?php
/**
 * EncaminhadosForm Form
 * @author  <your name here>
 */
class EncaminhadosForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Nomeados');
        $this->form->setFormTitle('Nomeados');
        $this->form->setFieldSizes('100%');

        // create the form fields
        $id = new TEntry('id');
        $aprovados_id = new TDBUniqueSearch('aprovados_id', 'rh', 'Aprovados', 'id', 'ano');
        $pessoa_id = new TDBUniqueSearch('pessoa_id', 'rh', 'Pessoa', 'id', 'nome');
        $geres_id = new TDBUniqueSearch('geres_id', 'rh', 'Geres', 'id', 'nome');
        $ato_id = new TDBUniqueSearch('ato_id', 'rh', 'Ato', 'id', 'ato');
        $dt_posse = new TDate('dt_posse');
        $situacaoconcurso_id = new TEntry('situacaoconcurso_id');
        $obs = new TText('obs');
        $cargo_id = new TDBCombo('cargo_id', 'rh', 'cargo', 'id', 'nome');
        $especialidade_id = new TDBUniqueSearch('especialidade_id', 'rh', 'especialidade', 'id', 'nome');


        // add the fields
        $this->form->addFields( [ new TLabel('Id'), $id ] );
        $this->form->addFields( [ new TLabel('Aprovados Id'), $aprovados_id ] );
        $this->form->addFields( [ new TLabel('Pessoa Id'), $pessoa_id ] );
        $this->form->addFields( [ new TLabel('Geres Id'), $geres_id ] );
        $this->form->addFields( [ new TLabel('Ato Id'), $ato_id ] );
        $this->form->addFields( [ new TLabel('Dt Posse'), $dt_posse ] );
        $this->form->addFields( [ new TLabel('Situacaoconcurso Id'), $situacaoconcurso_id ] );
        $this->form->addFields( [ new TLabel('Obs'), $obs ] );
        $this->form->addFields( [ new TLabel('Cargo Id'), $cargo_id ] );
        $this->form->addFields( [ new TLabel('Especialidade Id'), $especialidade_id ] );



        // set sizes
        $id->setSize('100%');
        $aprovados_id->setSize('100%');
        $pessoa_id->setSize('100%');
        $geres_id->setSize('100%');
        $ato_id->setSize('100%');
        $dt_posse->setSize('100%');
        $situacaoconcurso_id->setSize('100%');
        $obs->setSize('100%');
        $cargo_id->setSize('100%');
        $especialidade_id->setSize('100%');



        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('rh'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Nomeados;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('rh'); // open a transaction
                $object = new Nomeados($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
