<?php
/**
 * SystemUserForm
 *
 * Anderson Lopes de Souza
 */
class CargoForm extends TWindow
{
    protected $form; // form
    protected $program_list;
    protected $fornecedor_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();

        parent::setSize( 0.5, NULL);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Cargo');
        $this->form->setFormTitle( 'Criar Cargo' );
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $nome->forceUpperCase();
        
        //Todo cadastro novo entra como ativo= sim
        $ativo = new TEntry('ativo');
        
        // define the sizes
        $id->setSize('100%');
        $nome->setSize('100%');
 
        // outros
        $id->setEditable(false);
        
        //Botão Cores
        $botaespecialidade = new TButton("botaespecialidade");

        //Fields se tem Especialidades
        $especialidade = new TRadioGroup('especialidade');
        $especialidade->addItems( ['sim' => 'Sim', 'não' => 'Não'] );
        $especialidade->setLayout('horizontal');
        $especialidade->setUseButton();
        $especialidade->setSize('100%');
        $especialidade->setValue('sim');


        //Links        
        $icone         = '<i class="fa fa-plus-circle green red"></i>';
        $btStyle       = 'margin:-3px;font-size:1em;border:none;background:none;';
        
        //Botão Cores
        $botao_especialidade = new TButton("botao_especialidade");
        TButton::disableField('form_Cargo', 'botao_especialidade');
        
        // master fields
        $row = $this->form->addFields(  [ new TLabel('id'), $id ],
                                        [ new TLabel('Cargo'), $nome ],
                                        [ new TLabel('Especialidade'), $especialidade ]                                        
                                        );
        $row->layout = ['col-sm-2', 'col-sm-7', 'col-sm-2']; 
        
        $row = $this->form->addFields(  
                                        );
        $row->layout = ['col-sm-4'];

        //Adicionando o parâmetro ativo para todos os registros
        $this->form->addFields([new TLabel('')], [$ativo]);        
        $ativo->setValue('sim');
        TQuickForm::hideField('form_Cargo', 'ativo');
                                    
        //$this->form->addFields( [new TFormSeparator('  ')] );
        $this->especialidade_list = new TCheckList('especialidade_list');
        $this->especialidade_list->style = 'width: 100%';
        $this->especialidade_list->setIdColumn('id');
        $this->especialidade_list->addColumn('id',    'ID',    'center',  '10%');        
        $col_nome = $this->especialidade_list->addColumn('nome', 'Especialidade',    'left',   '40%');
        $col_nome->enableAutoHide(500);
        $this->especialidade_list->setHeight(200);
        $this->especialidade_list->makeScrollable();        

        $col_nome->enableSearch(); 
        $search_nome = $col_nome->getInputSearch();
        $search_nome->placeholder = _t('Search');
        $search_nome->style = 'width:50%;margin-left: 4px; border-radius: 4px';
                
        //$this->form->addFields( [new TFormSeparator('Atribuir Especialidades')] );
        $this->form->addFields( [$this->especialidade_list] );
        $this->form->addFields( [new TFormSeparator('')] );

        TTransaction::open('permission');
        $this->especialidade_list->addItems( Especialidade::get() );
        TTransaction::close();
        
        //Botões do footer
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')),  'far:check-circle blue' );
        $this->form->addAction('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addAction( 'Cancelar',  new TAction(['CargoList', 'onReload']), 'fa:times red' );

        //Botões do cabeçalho
        
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['CargoList', 'onReload']), 'fa:times red' );
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        parent::add($container);
    }

    /**
     * Save user data
     */
    public function onSave($param)
    {
        try
        {
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new Cargo;
            $object->fromArray( (array) $data );
            $object->ativo = "sim";
            $object->store();
            //$object->clearParts();
            
            
            if (!empty($data->especialidade_list))
            {
                foreach ($data->especialidade_list as $especialidade_id)
                {
                    $object->addEspecialidadeCargo( new Especialidade( $especialidade_id ) );
                }
            }
                        
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_Cargo', $data);
            
            // close the transaction
            TTransaction::close();
            
            // shows the success message
            new TMessage('info', 'Registro Concluído!');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'permission'
                TTransaction::open('permission');
                
                // instantiates object System_user
                $object = new Cargo($key);
               
                $fornecs = array();
                
                $espec_ids = array();
                foreach ($object->getEspecialidadeCargo() as $espec)
                {
                    $espec_ids[] = $espec->id;
                }

                $object->especialidade_list = $espec_ids;                
                
                // fill the form with the active record data
                $this->form->setData($object);
                                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
