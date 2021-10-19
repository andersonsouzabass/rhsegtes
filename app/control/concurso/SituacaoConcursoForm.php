<?php
/**
 * SituacaoConcursoForm Form
 * @author  <your name here>
 */
class SituacaoConcursoForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize( 0.6, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_SituacaoConcurso');
        $this->form->setFormTitle('Criar Situação do Candidato do Concurso');
        

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $ativo = new TEntry('ativo');
        $nome->forceUpperCase();

        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Situação') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Ativo') ], [ $ativo ] );
        
        $ativo->setValue('sim');
        $nome->addValidation('Nome', new TRequiredValidator);

        TQuickForm::hideField('form_SituacaoConcurso', 'ativo');

        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $id->setEditable(FALSE);
        
        // create the form actions
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addActionLink('Cancelar', new TAction(array('SituacaoConcursoList','onReload')),  'fa:times red' );
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['SituacaoConcursoList', 'onReload']), 'fa:times red' );

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
            
            $object = new SituacaoConcurso;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            
            $userid = TSession::getValue('userid');
            
            if(!Empty($param['id']))
            {
                $object->system_user_id_updated = $userid;
            }
            else
            {
                $object->system_user_id_created = $userid;
            }

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
                $object = new SituacaoConcurso($key); // instantiates the Active Record
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
