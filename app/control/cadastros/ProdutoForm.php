<?php
/**
 * PessoaForm
 *
 * 
 * @author     Anderson Souza
 */
class ProdutoForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::removePadding();
        parent::removeTitleBar();
        //parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Produto');
        $this->form->setFormTitle('Cadastro de Produto');
        $this->form->setProperty('style', 'margin:0;border:0');
        $this->form->setClientValidation(true);

        // create the form fields
        $id = new TEntry('id');

        $fornecedor = new TDBUniqueSearch('pessoa_id', 'erphouse', 'Pessoa', 'id', 'nome_fantasia');
        $fornecedor->setMinLength(0);
        
        $produto = new TEntry('produto');
        $valor_fixo = new TCombo('valor_fixo');
        $valor = new TEntry('valor');
        $desconto = new TEntry('desconto');
        $unidade_medida_id = new TDBUniqueSearch('unidade_medida_id', 'erphouse', 'Unidade_Medida', 'id', 'unidade_medida');
        $unidade_medida_id->setMinLength(0);
        $valor_fixo->addItems( ['D' => 'Doação', 'v' => 'Venda' ] );
        
        
        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ]);
        $this->form->addFields( [ new TLabel('Produto') ], [ $produto ]);
        $this->form->addFields( [ new TLabel('Fornecedor') ], [ $fornecedor ], [ new TLabel('Tipo de Produto') ], [ $valor_fixo ]);
        $this->form->addFields( [ new TLabel('Valor') ], [ $valor ], [ new TLabel('Desconto') ], [ $desconto ] );
        $this->form->addFields( [ new TLabel('Unidade de Medida') ], [ $unidade_medida_id ] );
        
        
        
        // set sizes
        $id->setSize('17%');

        $fornecedor->setSize('100%');
        $valor_fixo->setSize('100%');

        $valor->setSize('100%');
        $desconto->setSize('100%');        

        $unidade_medida_id->setSize('17%');
        
        //Validações
        $id->setEditable(FALSE);
        $produto->addValidation('Produto', new TRequiredValidator);
        $valor_fixo->addValidation('Tipo de Produto', new TRequiredValidator);
        //$valor->addValidation('Valor', new TRequiredValidator);
        //$desconto->addValidation('Desconto', new TRequiredValidator);
        //$unidade_medida_id->addValidation('UN', new TRequiredValidator);

        // create the form actions
        $this->form->addHeaderActionLink( _t('Close'),  new TAction([__CLASS__, 'onClose'], ['static'=>'1']), 'fa:times red');
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
            TTransaction::open('erphouse'); // open a transaction
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Produto;  // create an empty object
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
                TTransaction::open('erphouse');
                $object = new Produto($key);
                
                $object->produto_id = Produto::where('pessoa_id', '=', $object->id)->getIndexedArray('produto_id');
                
                $this->form->setData($object);
                
                           
                // force fire events
                $data = new stdClass;
                $data->unidade_medida_id = $object->unidade_medida->id;
                $data->unidade_medida_id = $object->unidade_medida_id;
                TForm::sendData('form_Produto', $data);
                
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
    
        /**
     * Closes window
     */
    public static function onClose()
    {   
        parent::closeWindow();        
    }
}
