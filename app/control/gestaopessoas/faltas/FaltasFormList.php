<?php
/**
 * ContratoList
 *
 * 
 */
class FaltasFormList extends TPage
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
        
        $this->setDatabase('gratifica');            // defines the database
        $this->setActiveRecord('ViewVinculoServidor');   // defines the active record
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('matricula', '=', 'matricula'); // filterField, operator, formField
        $this->addFilterField('cpf', '=', 'cpf'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('funcao', 'like', 'funcao'); // filterField, operator, formField
        $this->addFilterField('unidade', 'like', 'unidade'); // filterField, operator, formField
        
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        //$this->setOrderCommand('nome', '(SELECT nome FROM servidor)');
        
        // $this->setCriteria($criteria) // define a standard filter
        $criteria = new TCriteria;
        $criteria->add(new TFilter('ativo', '=', 'SIM') );
        $this->setCriteria($criteria); // define a standard filter

        // creates the form
        $this->form = new BootstrapFormBuilder('form_faltas_list');
        $this->form->setFormTitle('Apontamento de Faltas');
        
        // create the form fields
        $id = new TEntry('id');
        $matricula = new TEntry('matricula');
        $cpf= new TEntry('cpf');
        $nome = new TEntry('nome');
        $funcao = new TEntry('funcao');
        $unidade = new TEntry('unidade');
        $ativo = new TRadioGroup('ativo');
        
        $nome->setMinLength(0);        
        $ativo->addItems( ['sim' => 'Sim', 'não' => 'Não', '' => 'Todos'] );
        $ativo->setLayout('horizontal');
        //$ativo->setUseButton();
        
       
        $row = $this->form->addFields(  [ new TLabel('Matrícula'), $matricula ],
                                        [ new TLabel('CPF'), $cpf ],
                                        [ new TLabel('Nome'), $nome ]                                        
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Função'), $funcao ],
                                        [ new TLabel('Unidade'), $unidade ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Ativo'), $ativo ]                                        
                                        );
        $row->layout = ['col-sm-2'];

        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');
        //$ativo->setSize('100%');
        $matricula->setSize('100%');
        $funcao->setSize('100%');
        $unidade->setSize('100%');

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addActionLink('Cadastrar Pessoa', new TAction(['ServidorForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center',  '10%');
        $column_matricula = new TDataGridColumn('matricula', 'Matrícula', 'left');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'left');
        $column_funcao = new TDataGridColumn('funcao', 'Função', 'left');
        $column_unidade = new TDataGridColumn('unidade', 'Unidade', 'left');        
        //$column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');

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
        });*/
        
        $column_id->setTransformer( function ($value, $object, $row) {
            if ($object->ativo == 'não')
            {
                $row->style= 'color: silver';
            }
            
            return $value;
        });
        
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id)->setVisibility(false); 
        $this->datagrid->addColumn($column_matricula);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_funcao);
        $this->datagrid->addColumn($column_unidade);
        //$this->datagrid->addColumn($column_ativo);

        // creates the datagrid column actions
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_funcao->setAction(new TAction([$this, 'onReload']), ['order' => 'funcao']);
        $column_unidade->setAction(new TAction([$this, 'onReload']), ['order' => 'unidade']);
        

        $action1 = new TDataGridAction(['FaltasForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onTurnOnOff'], ['id'=>'{id}']);
        $action3 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, 'Apontar Falta',   'far:edit blue');
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
            TTransaction::open('gratifica');
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
