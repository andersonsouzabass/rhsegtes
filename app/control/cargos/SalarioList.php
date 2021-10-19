<?php
/**
 * ContratoList
 *
 * 
 */
class SalarioList extends TPage
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
        $this->setActiveRecord('view_salario');   // defines the active record
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('cargo', 'like', 'cargo'); // filterField, operator, formField       
        $this->addFilterField('regime', 'like', 'regime'); // filterField, operator, formField
        $this->setDefaultOrder('cargo', 'asc');         // defines the default order
        //$this->setOrderCommand('nome', '(SELECT nome FROM servidor)');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_salario_list');
        $this->form->setFormTitle('Remuneração do Servidor');
        
        // create the form fields
        $id = new TEntry('id');
        $cargo = new TEntry('cargo');        
        $regime = new TDBCombo('regime', 'rh', 'Regime', 'nome', 'nome');
        
        $cargo->forceUpperCase();
        
        $cargo->setMinLength(0);        

        $row = $this->form->addFields(  [ new TLabel('Código'), $id ]
                                        );
        $row->layout = ['col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo ],
                                        [ new TLabel('Regime'), $regime ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-3'];


        // set sizes
        $id->setSize('100%');
        $cargo->setSize('100%');
        $regime->setSize('100%');
       
        //$ativo->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Cadastrar', new TAction(['SalarioForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Código', 'center',  '10%');
        $column_cargo = new TDataGridColumn('cargo', 'Cargo', 'left');               
        $column_regime = new TDataGridColumn('regime', 'Regime', 'left');
        $column_base = new TDataGridColumn('base', 'Remuneração', 'left');
        $column_data = new TDataGridColumn('inicio', 'Vigência', 'left');

        $column_data->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_cargo);        
        $this->datagrid->addColumn($column_regime);
        $this->datagrid->addColumn($column_base);
        $this->datagrid->addColumn($column_data);

        // creates the datagrid column actions
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_cargo->setAction(new TAction([$this, 'onReload']), ['order' => 'cargo']);
                
        $action1 = new TDataGridAction(['SalarioForm', 'onEdit'], ['id'=>'{id}']);
        //$action2 = new TDataGridAction([$this, 'onTurnOnOff'], ['id'=>'{id}']);
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
        
        $format_value = function($value) {
            if (is_numeric($value)) {
                return 'R$ ' .number_format($value, 2, ',', '.');
            }
            return $value;
        };
        
        $column_base->setTransformer( $format_value );

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
    
}
