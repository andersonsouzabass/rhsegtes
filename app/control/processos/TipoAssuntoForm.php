<?php
/**
 * PessoaForm
 *
 *
 * @author     Anderson Souza
 * 
 */
class TipoAssuntoForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.6, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_TipoAssunto');
        $this->form->setFormTitle('Criar Tipo de Assunto');
        $this->form->setProperty('style', 'margin:0;border:0');
        $this->form->setClientValidation(true);

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');        
        $prazo = new TEntry('prazo');
        
        $nome->forceUpperCase();

        $ativo = new TEntry('ativo');
        $this->form->addFields( [ new TLabel('Ativo') ], [ $ativo ] );
        $ativo->setValue('sim');
        TQuickForm::hideField('form_TipoAssunto', 'ativo');
        
        
        // master fields
        $row = $this->form->addFields(  [ new TLabel('Código'), $id ],
                                        [ new TLabel('Assunto'), $nome ],
                                        [ new TLabel('Prazo em Dias'), $prazo ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-6', 'col-sm-2']; 

        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $prazo->setSize('100%');
        $id->setEditable(FALSE);

        //Validações
        $nome->addValidation('Nome', new TRequiredValidator);
        $prazo->addValidation('Prazo', new TNumericValidator);
                
        //Botoões do form
        $this->form->addHeaderActionLink( _t('Close'),  new TAction(array('TipoAssuntoList','onReload')),  'fa:times red' );
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Limpar',  new TAction([$this, 'onClear']), 'fa:eraser red');
        $this->form->addActionLink('Cancelar', new TAction(array('TipoAssuntoList','onReload')),  'fa:times red' );

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
            TTransaction::open('gratifica'); // open a transaction
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new TipoAssunto;  // create an empty object
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
                $key = $param['key'];
                TTransaction::open('gratifica');
                $object = new TipoAssunto($key);
                               
                $this->form->setData($object);
                
                TTransaction::close();
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
    
    
    public static function onClose()
    {
        parent::closeWindow();
    }
}
