<?php
/**
 * ProcessosForm Form
 * @author  <your name here>
 */
class ProcessosForm extends TWindow
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
        $this->form = new BootstrapFormBuilder('form_Processos');
        $this->form->setFormTitle('Entrada de Processos e Documentos');
        
        // create the form fields
        $id = new TEntry('id');
        $tipo_assunto_id = new TDBUniqueSearch('tipo_assunto_id', 'gratifica', 'TipoAssunto', 'id', 'nome');
        $dt_entrada = new TDate('dt_entrada');
        $dt_prazo = new TDate('dt_prazo');
        //$n_documento = new TEntry('n_documento');
        $interessado = new TEntry('interessado');
        $servidor = new TCombo('servidor');
        $matricula = new TEntry('matricula');
        $cpf = new TEntry('cpf');
        $observacao = new TText('observacao');

        $servidor->addItems ([
            'sim' => 'SIM',
            'não' => 'NÃO'
            ]);
        

        // add the fields
        $row = $this->form->addFields(  [ new TLabel('Código'), $id ],
                                        [ new TLabel('Assunto'), $tipo_assunto_id ],
                                        [ new TLabel('Entrada'), $dt_entrada ],
                                        [ new TLabel('Servidor'), $servidor ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-6', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Interessado'), $interessado ],
                                        [ new TLabel('Prazo'), $dt_prazo ]
                                        );
        $row->layout = ['col-sm-8', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Matrícula'), $matricula ],
                                        [ new TLabel('CPF'), $cpf ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Observação'), $observacao ],
                                        );
        $row->layout = ['col-sm-8'];
        //Final dos campos
                                    
        //Validações
        $servidor->addValidation('Servidor', new TRequiredValidator);
        $interessado->forceUpperCase();
        
        $cpf->addValidation('CPF', new TCPFValidator);
                
        // set sizes
        $id->setSize('100%');
        $tipo_assunto_id->setSize('100%');
        $dt_entrada->setSize('100%');        
        $interessado->setSize('100%');
        $servidor->setSize('100%');
        $matricula->setSize('100%');
        $cpf->setSize('100%');
        $observacao->setSize('100%');

        $cpf->setMask('999.999.999-99', true);
        $id->setEditable(FALSE);
       
        $dt_entrada->setMask('dd/mm/yyyy');
        $dt_entrada->setDatabaseMask('yyyy-mm-dd');
        $dt_entrada->setEditable(FALSE);
       

        $dt_prazo->setMask('dd/mm/yyyy');
        $dt_prazo->setDatabaseMask('yyyy-mm-dd');
        $dt_prazo->setEditable(FALSE);

        
        // create the form actions
        $this->form->addHeaderActionLink( _t('Close'),  new TAction(array('ProcessosList','onReload')),  'fa:times red' );
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        //$btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Limpar',  new TAction([$this, 'onClear']), 'fa:eraser red');
        $this->form->addActionLink('Cancelar', new TAction(array('ProcessosList','onReload')),  'fa:times red' );

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
            
            if($param['servidor']=='sim')
            {
                if(empty($param['matricula']))
                {
                    throw new Exception('O campo Matrícula é obrigatório se o interessado é um servidor');
                }
            }
                    
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            
            $data_in = new DateTime($data->dt_entrada);
            $data->dt_entrada = $data_in->format('Y-m-d');
            
            $object = new Processos;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            
            //Calcula a data prazo do processo
            $cPrazo = TipoAssunto::find($param['tipo_assunto_id']);
            if ($cPrazo instanceof TipoAssunto)
            {
                $dias = $cPrazo->prazo;                                
            } 
            $object->dt_prazo = date('Y-m-d', strtotime("+{$dias} days",strtotime($data->dt_entrada)));
            // fim do cálculo da data prazo

            $object->status = "ABERTO";
            
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $data_fn = new DateTime($object->dt_entrada);
            $data->dt_entrada = $data_fn->format('d/m/Y');

            $data_pz = new DateTime($object->dt_prazo);
            $data->dt_prazo = $data_pz->format('d/m/Y');
            
            
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
                TTransaction::open('gratifica'); // open a transaction
                $object = new Processos($key); // instantiates the Active Record

                $data_fn = new DateTime($object->dt_entrada);
                $object->dt_entrada = $data_fn->format('d/m/Y');

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
