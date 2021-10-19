<?php
/**
 * PessoaForm
 *
 *
 * @author     Anderson Souza
 * 
 */
class PessoaForm extends TWindow
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
        $this->form = new BootstrapFormBuilder('form_Pessoa');
        $this->form->setFormTitle('Unidade de Saúde');
        $this->form->setProperty('style', 'margin:0;border:0');
        $this->form->setClientValidation(true);

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome'); 
        $sigla = new TEntry('sigla');
        $observacao = new TText('observacao');
        $cep = new TEntry('cep');
        $logradouro = new TEntry('logradouro');
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');
        
        $cidade = new TEntry('cidade');
        
        $grupo_id = new TDBCombo('grupo_id', 'rh', 'Grupo', 'id', 'nome');
        $tipo_unidade_id = new TDBCombo('tipo_unidade_id', 'rh', 'TipoUnidade', 'id', 'nome');
        $geres_id = new TDBCombo('geres_id', 'rh', 'Geres', 'id', 'nome');

        $estado = new TEntry('estado');

        $ativo = new TEntry('ativo');
        $this->form->addFields( [ new TLabel('Ativo') ], [ $ativo ] );
        $ativo->setValue('sim');
        TQuickForm::hideField('form_Pessoa', 'ativo');
        
        $cep->setExitAction( new TAction([ $this, 'onExitCEP']) );
        
        $nome->forceUpperCase();
        $sigla->forceUpperCase();
        $observacao->forceUpperCase();
        $complemento->forceUpperCase();
        $bairro->forceUpperCase();
        $cidade->forceUpperCase();
        $estado->forceUpperCase();
        $logradouro->forceUpperCase();

        $grupo_id->enableSearch(0);
        
        $observacao->setSize('100%', 60);
        
        // master fields
        $row = $this->form->addFields(  [ new TLabel('Código'), $id ],
                                        [ new TLabel('Nome da Unidade'), $nome ],
                                        [ new TLabel('Sigla'), $sigla ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-8', 'col-sm-2']; 

        $row = $this->form->addFields(  [ new TLabel('Tipo de Unidade'), $grupo_id ],
                                        [ new TLabel('Perfil da Unidade'), $tipo_unidade_id ],
                                        [ new TLabel('GERES'), $geres_id ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4']; 


        $row = $this->form->addFields(  [ new TLabel('Observação'), $observacao ]
                                        );
        $row->layout = ['col-sm-12'];
        
        $row = $this->form->addFields(  [ new TLabel('CEP'), $cep ],
                                        [ new TLabel('Logradouro'), $logradouro ],
                                        [ new TLabel('Numero'), $numero ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-8', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Complemento'), $complemento ],
                                        [ new TLabel('Bairro'), $bairro ],
                                        [ new TLabel('Estado'), $estado ],
                                        [ new TLabel('Cidade'), $cidade ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-4', 'col-sm-2', 'col-sm-3'];
        
        // set sizes
        $id->setSize('100%');

        
        $grupo_id->setSize('100%');
        $geres_id->setSize('100%');
        $tipo_unidade_id->setSize('100%');

        $nome->setSize('100%');
        $sigla->setSize('100%');
        $observacao->setSize('100%');
        $cep->setSize('100%');
        $logradouro->setSize('100%');
        $numero->setSize('100%');
        $complemento->setSize('100%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $estado->setSize('100%');
        
        $cep->setMask('99.999-999');
        
        $id->setEditable(FALSE);
        $nome->addValidation('Nome', new TRequiredValidator);
        
        /*
        $grupo_id->addValidation('Grupo', new TRequiredValidator);
        
        $cidade->addValidation('Cidade', new TRequiredValidator);
        $estado->addValidation('Estado', new TRequiredValidator);
        $cep->addValidation('CEP', new TRequiredValidator);
        $logradouro->addValidation('Logradouro', new TRequiredValidator);
        $numero->addValidation('Número', new TRequiredValidator);
        */
                
        //Botoões do form
        $this->form->addHeaderActionLink( _t('Close'),  new TAction(array('PessoaList','onReload')),  'fa:times red' );
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Limpar',  new TAction([$this, 'onClear']), 'fa:eraser red');
        $this->form->addActionLink('Cancelar', new TAction(array('PessoaList','onReload')),  'fa:times red' );

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
            
            $object = new Pessoa;  // create an empty object
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
                TTransaction::open('rh');
                $object = new Pessoa($key);
                               
                $this->form->setData($object);
                
                // force fire events
                //$data = new stdClass;
                //$data->estado_id = $object->cidade->estado->id;
                //$data->cidade_id = $object->cidade_id;
                //TForm::sendData('form_Pessoa', $data);
                
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
     * Action to be executed when the user changes the state
     * @param $param Action parameters
     */
    public static function onChangeEstado($param)
    {
        try
        {
            TTransaction::open('rh');
            if (!empty($param['estado_id']))
            {
                $criteria = TCriteria::create( ['estado_id' => $param['estado_id'] ] );
                
                // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
                TDBCombo::reloadFromModel('form_Pessoa', 'cidade_id', 'rh', 'Cidade', 'id', '{nome} ({id})', 'nome', $criteria, TRUE);
            }
            else
            {
                TCombo::clearField('form_Pessoa', 'cidade_id');
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Autocompleta outros campos a partir do CNPJ
     */
    
    
    /**
     * Autocompleta outros campos a partir do CEP
     */
    public static function onExitCEP($param)
    {
        session_write_close();
        
        try
        {
            $cep = preg_replace('/[^0-9]/', '', $param['cep']);
            $url = 'https://viacep.com.br/ws/'.$cep.'/json/unicode/';
            
            $content = @file_get_contents($url);
            
            if ($content !== false)
            {
                $cep_data = json_decode($content);
                
                $data = new stdClass;
                if (is_object($cep_data) && empty($cep_data->erro))
                {
                    $data->logradouro  = $cep_data->logradouro;
                    $data->complemento = $cep_data->complemento;
                    $data->bairro      = $cep_data->bairro;
                    $data->estado      = $cep_data->uf;
                    $data->cidade      = $cep_data->localidade;
                    
                    TForm::sendData('form_Pessoa', $data, false, true);
                }
                else
                {
                    $data->logradouro  = '';
                    $data->complemento = '';
                    $data->bairro      = '';
                    $data->estado   = '';
                    $data->cidade   = '';
                    
                    TForm::sendData('form_Pessoa', $data, false, true);
                }
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
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
