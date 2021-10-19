<?php
/**
 * ContratoList
 *
 * 
 */
class CargoFolhaList extends TPage
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
        $this->setActiveRecord('CargoFolha');   // defines the active record
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField       
        $this->addFilterField('ativo', 'like', 'ativo'); // filterField, operator, formField
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        //$this->setOrderCommand('nome', '(SELECT nome FROM servidor)');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_cargo_list');
        $this->form->setFormTitle('Lista de Cargos da Folha');
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');        
        $ativo = new TRadioGroup('ativo');
        
        $nome->forceUpperCase();
        
        $nome->setMinLength(0);        
        $ativo->addItems( ['sim' => 'Sim', 'não' => 'Não', '' => 'Todos'] );
        $ativo->setLayout('horizontal');
        //$ativo->setUseButton();
        

        $row = $this->form->addFields(  [ new TLabel('Código'), $id ]
                                        );
        $row->layout = ['col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Nome'), $nome ]
                                        );
        $row->layout = ['col-sm-3'];

        $row = $this->form->addFields(  [ new TLabel('Ativo'), $ativo ]
                                        );
        $row->layout = ['col-sm-3'];


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
       
        //$ativo->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Cadastrar', new TAction(['CargoFolhaForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Código', 'center',  '10%');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');               
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');

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
        
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);        
        $this->datagrid->addColumn($column_ativo);

        // creates the datagrid column actions
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        
        $column_ativo->enableAutoHide(500);
        
        $action1 = new TDataGridAction(['CargoFolhaForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onTurnOnOff'], ['id'=>'{id}']);
        $action3 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Activate/Deactivate'), 'fa:power-off orange');
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
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('rh');
            $srv = CargoFolha::find($param['id']);
            
            if ($srv instanceof CargoFolha)
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
