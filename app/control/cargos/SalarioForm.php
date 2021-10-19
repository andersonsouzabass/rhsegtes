<?php
/**
 * salarioForm Form
 * @author  <your name here>
 */
class SalarioForm extends TWindow
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
        $this->form = new BootstrapFormBuilder('form_salario');
        $this->form->setFormTitle('Remuneração do Servidor');
        

        // create the form fields
        $id = new TEntry('id');
        $cargo_id = new TDBCombo('cargo_id', 'rh', 'Cargo', 'id', 'nome');
        $regime_trabalho_id = new TDBCombo('regime_trabalho_id', 'rh', 'Regime', 'id', 'nome');
        $valor = new TNumeric('valor', 2, ',', '.');
        $inicio = new TDate('inicio');


        // master fields
        $row = $this->form->addFields(  [ new TLabel('id'), $id ],
                                        [ new TLabel('Cargo'), $cargo_id ],
                                        [ new TLabel('Regime de Trabalho'), $regime_trabalho_id ]                                        
                                        );
        $row->layout = ['col-sm-2', 'col-sm-5', 'col-sm-5']; 

        $row = $this->form->addFields(  [ new TLabel('Remuneração'), $valor ],
                                        [ new TLabel('Início da Vigência'), $inicio ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-4']; 


        $cargo_id->addValidation('Cargo Id', new TRequiredValidator);
        $regime_trabalho_id->addValidation('Regime Trabalho Id', new TRequiredValidator);


        // set sizes
        $id->setSize('100%');
        $cargo_id->setSize('100%');
        $regime_trabalho_id->setSize('100%');
        $valor->setSize('100%');
        $inicio->setSize('100%');

        $inicio->setMask('dd/mm/yyyy');
        $inicio->setDatabaseMask('yyyy-mm-dd');


        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addActionLink('Cancelar', new TAction(array('SalarioList','onReload')),  'fa:times red' );
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['SalarioList', 'onReload']), 'fa:times red' );
        
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
            
            $object = new Salario;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data

            $object->valor = (float) str_replace(['.', ','], ['', '.'],$param['valor']);

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
                $object = new Salario($key); // instantiates the Active Record
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
