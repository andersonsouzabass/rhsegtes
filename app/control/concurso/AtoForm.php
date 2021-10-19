<?php
/**
 * AtoForm Form
 * @author  <your name here>
 */
class AtoForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        parent::setSize( 0.7, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Ato');
        $this->form->setFormTitle('Criar Ato de Nomeação');
        

        // create the form fields
        $id = new TEntry('id');
        $ato = new TEntry('ato');
        $justificativa_ato_id = new TDBCombo('justificativa_ato_id', 'rh', 'JustificativaAto', 'id', 'nome');
        $dt_publicacao = new TDate('dt_publicacao');
        $dt_nomeacao = new TDate('dt_nomeacao');
        $obs = new TText('obs');

        $prazo_posse_ato = new TEntry('prazo_posse_ato');
        $prazo_posse_exerc = new TEntry('prazo_posse_exerc');
        $prazo_legal_ato = new TEntry('prazo_legal_ato');
        $prazo_legal_exerc = new TEntry('prazo_legal_exerc');

        /*
        $prazo_legal_ato->setValue('30');
        $prazo_legal_exerc->setValue('30');
        $prazo_posse_ato->setValue('30');
        $prazo_posse_exerc->setValue('30');
        */

        // add the fields       
        $row = $this->form->addFields(  [ new TLabel('Código'), $id ],
                                        [ new TLabel('Ato'), $ato ],
                                        [ new TLabel('Justificativa do Ato'), $justificativa_ato_id ],
                                        [ new TLabel('Nomeação'), $dt_nomeacao ],
                                        [ new TLabel('Publicação'), $dt_publicacao ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-4', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Prazo Para Posse'), $prazo_posse_ato ],
                                        [ new TLabel('Prazo Para Assumir'), $prazo_posse_exerc ],
                                        [ new TLabel('Prazo Legal Para Posse'), $prazo_legal_ato ],
                                        [ new TLabel('Prazo Legal Para Assumir'), $prazo_legal_exerc ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-3', 'col-sm-3', 'col-sm-3'];


        $row = $this->form->addFields(  [ new TLabel('Observação'), $obs ]
                                        );
        $row->layout = ['col-sm-12'];

        // set sizes
        $id->setSize('100%');
        $ato->setSize('100%');
        $justificativa_ato_id->setSize('100%');
        $dt_publicacao->setSize('100%');
        $dt_nomeacao->setSize('100%');
        $obs->setSize('100%');

        $prazo_posse_ato->setSize('100%');
        $prazo_posse_exerc->setSize('100%');
        $prazo_legal_ato->setSize('100%');
        $prazo_legal_exerc->setSize('100%');

        //Validada se é um número válido
        $prazo_posse_ato->addValidation('prazo_posse_ato', new TNumericValidator); 
        $prazo_posse_exerc->addValidation('prazo_posse_ato', new TNumericValidator); 
        $prazo_legal_ato->addValidation('prazo_posse_ato', new TNumericValidator); 
        $prazo_legal_exerc->addValidation('prazo_posse_ato', new TNumericValidator); 

        $dt_publicacao->setMask('dd/mm/yyyy');
        $dt_publicacao->setDatabaseMask('yyyy-mm-dd');

        $dt_nomeacao->setMask('dd/mm/yyyy');
        $dt_nomeacao->setDatabaseMask('yyyy-mm-dd');

        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        // create the form actions
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addActionLink('Cancelar', new TAction(array('AtoList','onReload')),  'fa:times red' );
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['AtoList', 'onReload']), 'fa:times red' );

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
            
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Ato;  // create an empty object
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
                $object = new Ato($key); // instantiates the Active Record
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
