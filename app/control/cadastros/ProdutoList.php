<?php
/**
 * Produtos Cadastrados
 */
class ProdutoList extends TPage
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
        
        $this->setDatabase('erphouse');            // defines the database
        $this->setActiveRecord('Produto');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(10);

        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('produto', 'like', 'produto'); // filterField, operator, formField        
        $this->addFilterField('unidade_medida_id', '=', 'unidade_medida_id'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Produto');
        $this->form->setFormTitle('Lista de Produtos');
        

        // create the form fields
        $id = new TEntry('id');
        $produto = new TEntry('produto');        
        $unidade_medida_id = new TDBUniqueSearch('unidade_medida_id', 'erphouse', 'Unidade_Medida', 'id', 'unidade_medida');
        $unidade_medida_id->setMinLength(0);

        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Produto') ], [ $produto ] );        
        $this->form->addFields( [ new TLabel('Unidade de Medida') ], [ $unidade_medida_id ] );


        // set sizes
        $id->setSize('100%');
        $produto->setSize('100%');
        $unidade_medida_id->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['ProdutoForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_produto = new TDataGridColumn('produto', 'Produto', 'left');
        $column_valor = new TDataGridColumn('valor', 'Preço de Venda', 'left');
        $column_desconto = new TDataGridColumn('desconto', 'Desconto', 'left');
        //$column_unidade_medida_id = new TDataGridColumn('unidade_medida->unidade_medida', 'UN', 'left');
        $column_fornecedor_id = new TDataGridColumn('pessoa->nome', 'Fornecedor', 'left');
        
        //$column_valor = 'R$' .number_format($column_valor, 2, ',', '.');

        $column_valor->enableAutoHide(500);
        $column_desconto->enableAutoHide(500);
        $column_fornecedor_id->enableAutoHide(500);        
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_produto);
        $this->datagrid->addColumn($column_valor);
        $this->datagrid->addColumn($column_desconto);
        $this->datagrid->addColumn($column_fornecedor_id);

         // declara uma função de transformação
        $formatar_moeda = function($value)
        {            
            if (is_numeric($value))
            {                
                return 'R$ '.number_format($value, 2, ',', '.');            
            }          
            return $value;      
        }; 

        //Formatações das colunas do datagrid
        $column_valor->setTransformer( $formatar_moeda );
        //------------------------------------------------------

        // declara uma função de transformação
        $formatar_percent = function($value)
        {            
            if (is_numeric($value))
            {                
                return number_format($value, 2, ',', '.') ."%";            
            }          
            return $value;      
        }; 

        //Formatações das colunas do datagrid
        $column_desconto->setTransformer( $formatar_percent );
        //------------------------------------------------------

        
        
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_produto->setAction(new TAction([$this, 'onReload']), ['order' => 'produto']);

        
        $action1 = new TDataGridAction(['ProdutoFormView', 'onEdit'], ['id'=>'{id}', 'register_state' => 'false']);
        $action2 = new TDataGridAction(['ProdutoForm', 'onEdit'], ['id'=>'{id}']);
        $action3 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}', 'register_state' => 'false']);
        
        $this->datagrid->addAction($action1, _t('View'),   'fa:search gray');
        $this->datagrid->addAction($action2, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action3 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }

    
}