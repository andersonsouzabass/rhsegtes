<?php
/**
 * ContratoList
 *
 * 
 */
class CessaoParaList extends TPage
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
        $this->setActiveRecord('ViewVinculoServidor');   // defines the active record 
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        $this->setLimit(10);

        // $this->setCriteria($criteria) // define a standard filter
        $criteria = new TCriteria;
        $criteria->add(new TFilter('vinculo_id', '=', 3) );
        $this->setCriteria($criteria); // define a standard filter

        //$this->addFilterField('vinculo_id', '=', 3); // filterField, operator, formField
        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('matricula', '=', 'matricula'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('unidade', 'like', 'unidade'); // filterField, operator, formField
        $this->addFilterField('cargo', 'like', 'cargo'); // filterField, operator, formField
        $this->addFilterField('funcao', 'like', 'funcao'); // filterField, operator, formField
        $this->addFilterField('cpf', '=', 'cpf'); // filterField, operator, formField
        $this->addFilterField('ativo', 'like', 'ativo'); // filterField, operator, formField
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        //$this->setOrderCommand('nome', '(SELECT nome FROM servidor)');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_cedido_para_list');
        $this->form->setFormTitle('Cessão para Outros Orgãos - Lista de Pessoas');
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $cpf= new TEntry('cpf');
        $ativo = new TRadioGroup('ativo');
        $matricula = new TEntry('matricula');
        $unidade = new TEntry('unidade');        
        $cargo = new TEntry('cargo');
        $funcao = new TEntry('funcao'); 
        
        $nome->forceUpperCase();       
        $cargo->forceUpperCase();
        $funcao->forceUpperCase();
        $unidade->forceUpperCase();

        $nome->setMinLength(0);
        $cpf->setMinLength(6);
        $ativo->addItems( ['SIM' => 'Sim', 'NÃO' => 'Não', '' => 'Todos'] );
        //$ativo->setUseButton();
        $ativo->setLayout('horizontal');
       
        $row = $this->form->addFields(  [ new TLabel('Matrícula'), $matricula ],
                                        [ new TLabel('CPF'), $cpf ],
                                        [ new TLabel('Nome'), $nome ]                                        
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo ],
                                        [ new TLabel('Função'), $funcao ],
                                        [ new TLabel('Unidade'), $unidade ]                                        
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Ativo'), $ativo ]                                      
                                        );
        $row->layout = ['col-sm-2'];

        // set sizes
        $id->setSize('100%');
        $matricula->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');
        $unidade->setSize('100%');
       // $ativo->setSize('100%');
        $cargo->setSize('100%');
        $funcao->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addActionLink('Cadastrar Pessoa', new TAction(['CessaoParaForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        /*
        $column_id = new TDataGridColumn('id', 'Id', 'center',  '10%');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'left');        
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');
        */

        // creates the datagrid columns
        $column_id = new TDataGridColumn('s_id', 'Id', 'left');    
        $column_matricula = new TDataGridColumn('matricula', 'Matrícula', 'left');    
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'left');
        $column_unidade = new TDataGridColumn('unidade', 'Unidade', 'left');
        $column_cargo = new TDataGridColumn('cargo', 'Cargo', 'left');
        $column_funcao = new TDataGridColumn('funcao', 'Função', 'left');
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');
        $column_vinculo_id = new TDataGridColumn('vinculo_id', 'vinculo_id', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id)->setVisibility(false); 
        $this->datagrid->addColumn($column_vinculo_id)->setVisibility(false); 
        $this->datagrid->addColumn($column_matricula);  
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_unidade);
        $this->datagrid->addColumn($column_cargo);
        $this->datagrid->addColumn($column_funcao);
        $this->datagrid->addColumn($column_ativo);

 //Ordenar as colunas
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 's_id']);
        $column_matricula->setAction(new TAction([$this, 'onReload']), ['order' => 'matricula']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_cpf->setAction(new TAction([$this, 'onReload']), ['order' => 'cpf']);
        $column_unidade->setAction(new TAction([$this, 'onReload']), ['order' => 'unidade']);
        $column_cargo->setAction(new TAction([$this, 'onReload']), ['order' => 'cargo']);
        $column_funcao->setAction(new TAction([$this, 'onReload']), ['order' => 'funcao']);
        $column_ativo->setAction(new TAction([$this, 'onReload']), ['order' => 'ativo']);

        $column_ativo->setTransformer( function ($value) {
            if ($value == 'SIM')
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
            if ($object->ativo == 'NÃO')
            {
                $row->style= 'color: silver';
            }
            
            return $value;
        });
        
        
        /*
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_ativo);
        */

        // creates the datagrid column actions
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        
        $column_ativo->enableAutoHide(500);
        
        $action1 = new TDataGridAction(['CessaoParaForm', 'onEdit'], ['id'=>'{s_id}']);
        $action2 = new TDataGridAction([$this, 'onTurnOnOff'], ['id'=>'{id}']);
        $action3 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
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
        
        // header actions
        /*
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        */
        
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
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('rh');
            $srv = Servidor::find($param['id']);
            
            if ($srv instanceof Servidor)
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
    }
}
