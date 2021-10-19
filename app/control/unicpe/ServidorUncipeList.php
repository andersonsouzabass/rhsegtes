<?php
/**
 * ContratoList
 *
 * 
 */
class ServidorUncipeList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
    use Adianti\base\AdiantiStandardListTrait;
        
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('rh');            // defines the database
        $this->setActiveRecord('ViewFolha');   // defines the active record        
        $this->setLimit(10);
        
        /*
            //Carrega o array que será utilizado para carregar apenas as únidas permitidas por usuário
            TTransaction::open('rh');
            $usuario_logado = TSession::getValue('userid'); //Pega a sessão do usuário logado
            
            $criteria = new TCriteria;
            $criteria->add( new TFilter( 'system_user_id', '=', $usuario_logado ));
            $customers = UsuarioUnidade::getObjects($criteria);
            foreach ($customers as $customer)
            {
                $vUnPermitida[] = $customer->folha_unidade_2_id;
            }
            TTransaction::close();
            $lista_unidade_acesso = new TCriteria;
            $lista_unidade_acesso->add(new TFilter('unidade_2_id', 'IN', $vUnPermitida)); 
            //var_dump($lista_unidade_acesso);
            // // define a standard filter
        */

        $this->addFilterField('matricula', '=', 'matricula'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField       
        $this->addFilterField('cargo', 'like', 'cargo'); // filterField, operator, formField
        $this->addFilterField('funcao', 'like', 'funcao'); // filterField, operator, formField
        $this->addFilterField('unidade_2', 'like', 'unidade_2'); // filterField, operator, formField
        $this->addFilterField('unidade_1', 'like', 'unidade_1'); // filterField, operator, formField
        $this->addFilterField('CPF', '=', 'CPF'); // filterField, operator, formField
        //$this->addFilterField('status_censo', 'like', 'status_censo'); // filterField, operator, formField
        
        $this->setDefaultOrder('nome', 'asc');         // defines the default order
        //->setCriteria($lista_unidade_acesso); //Carrega apenas os servidores das unidades setadas
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_folha_list_unicpe');
        $this->form->setFormTitle('Lista de Servidores');
        
        // create the form fields
        $matricula = new TEntry('matricula');
        $cpf = new TEntry('CPF');
        $nome = new TEntry('nome');        
        $cargo = new TEntry('cargo');
        $funcao = new TEntry('funcao');
        $unidade_1 = new TEntry('unidade_1');
        $unidade_2 = new TEntry('unidade_2');
        
        $nome->setMinLength(3);  

        $nome->forceUpperCase();
        $cargo->forceUpperCase();
        $funcao->forceUpperCase();
        $unidade_1->forceUpperCase();
        $unidade_2->forceUpperCase();

        //Campos do formulário de consulta
        $row = $this->form->addFields(  [ new TLabel('Matrícula'), $matricula ],
                                        [ new TLabel('CPF'), $cpf ],
                                        [ new TLabel('Nome'), $nome ],
                                        //[ new TLabel('Cargo'), $cargo ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-8'];

        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo ],
                                        [ new TLabel('Função'), $funcao ],
                                        [ new TLabel('Lotação'), $unidade_2 ],
                                        [ new TLabel('Secretaria'), $unidade_1 ],
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-4', 'col-sm-4'];

        /*
        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo ]
                                        );
        $row->layout = ['col-sm-3'];
        */


        // set sizes
        $matricula->setSize('100%');
        $cpf->setSize('100%');
        $nome->setSize('100%');       
        $cargo->setSize('100%');
        $funcao->setSize('100%');
        $unidade_2->setSize('100%');
        $unidade_1->setSize('100%');
        

        $cpf->setMask('000.000.000-00', true);
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addActionLink('Cadastrar', new TAction(['FolhaForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Código', 'center',  '10%');
        $column_matricula = new TDataGridColumn('matricula', 'Matrícula', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');               
        $column_cargo = new TDataGridColumn('cargo', 'Cargo', 'left');
        $column_funcao = new TDataGridColumn('funcao', 'Função', 'left');
        $column_admissao = new TDataGridColumn('data_admissao', 'Admissão', 'left');
        $column_unidade = new TDataGridColumn('unidade_2', 'Lotação', 'left');
        $column_unidade_1 = new TDataGridColumn('unidade_1', 'Secretaria', 'left');
        //$column_status = new TDataGridColumn('status_censo', 'Status', 'left');

        /*
        $column_ativo->setTransformer( function ($value) {
            if ($value == 'sim')
            {
                $div = new TElement('span');
                $div->class="label label-success";
                $div->style="text-shadow:none; font-size:12px";
                $div->add('SIM');
                return $div;
            }
            else
            {
                $div = new TElement('span');
                $div->class="label label-danger";
                $div->style="text-shadow:none; font-size:12px";
                $div->add('NÃO');
                return $div;
            }
        });
        
        $column_id->setTransformer( function ($value, $object, $row) {
            if ($object->ativo == 'não')
            {
                $row->style= 'color: silver';
            }
            
            return $value;
        });
        */
        
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id)->setVisibility(false);
        $this->datagrid->addColumn($column_matricula);        
        $this->datagrid->addColumn($column_nome);        
        $this->datagrid->addColumn($column_cargo);
        $this->datagrid->addColumn($column_funcao);
        $this->datagrid->addColumn($column_admissao);
        $this->datagrid->addColumn($column_unidade);
        $this->datagrid->addColumn($column_unidade_1);
        //$this->datagrid->addColumn($column_status);

        // creates the datagrid column actions
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_cargo->setAction(new TAction([$this, 'onReload']), ['order' => 'cargo']);
        $column_funcao->setAction(new TAction([$this, 'onReload']), ['order' => 'funcao']);
        $column_admissao->setAction(new TAction([$this, 'onReload']), ['order' => 'data_admissao']);
        $column_unidade->setAction(new TAction([$this, 'onReload']), ['order' => 'unidade_2']);
        //$column_status->setAction(new TAction([$this, 'onReload']), ['order' => 'status_censo']);
        
        //Formatar as colunas de datas no padrão
        $column_admissao->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        //Farois na status do censo
        /*
        $column_status->setTransformer( function($value, $object) {
            $stt_p = 'PENDENTE';
            $stt_v = 'VALIDADO';
            if ($value == null)
            {
                $div = new TElement('span');
                $div->class="label label-warning";
                $div->style="text-shadow:none; font-size:12px";
                $div->add($stt_p);
                $stt = $stt_p;
                return $div;
            }
            else
            {
                $div = new TElement('span');
                $div->class="label label-success";
                $div->style="text-shadow:none; font-size:12px";
                $div->add($stt_v);
                $stt = $stt_v;
                return $div;
            }
            return $stt;
        });*/
        
        //$column_ativo->enableAutoHide(500);
        
        $action1 = new TDataGridAction(['ServidorUnicpeForm', 'onEdit'], ['id'=>'{id}']);
        //$action2 = new TDataGridAction([$this, 'onTurnOnOff'], ['id'=>'{id}']);
        $action3 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, 'Identificar Servidor',   'far:edit blue');
        //$this->datagrid->addAction($action2 ,_t('Activate/Deactivate'), 'fa:power-off orange');
        //$this->datagrid->addAction($action3 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';        
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
    
    /**
     * Turn on/off an user
     */
    /*
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('rh');
            $srv = FuncaoFolha::find($param['id']);
            
            if ($srv instanceof FuncaoFolha)
            {
                $srv->ativo = $srv->ativo == 'sim' ? 'não' : 'sim';
                $srv->store();
            }
            
            TTransaction::close();
            
            $this->onReload($param);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }*/
}