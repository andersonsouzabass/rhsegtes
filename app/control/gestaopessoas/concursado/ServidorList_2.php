<?php
/**
 * Clientes Cadastrados
 */
class ServidorList_2 extends TPage
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
        $this->setActiveRecord('Servidor');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(10);

        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField        
        //$this->addFilterField('unidade_medida_id', '=', 'unidade_medida_id'); // filterField, operator, formField
        
        // create the HTML Renderer
        $this->html = new THtmlRenderer('app/resources/cadastros/titulo_servidor.html');

        $replaces = [];
        $replaces['title']  = 'Pessoas';
        $replaces['botao'] = 'Cadastrar Servidor';
        //$replaces['name']   = 'Someone famous';
        
        // replace the main section variables
        $this->html->enableSection('main', $replaces);
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');        
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'left');
        $column_ativo = new TDataGridColumn('ativo', 'Ativo', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);        
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_ativo);
        
        //formata a coluna ativo
        $column_ativo->setTransformer( function($value, $object, $row) {
            $class = ($value=='não') ? 'danger' : 'success';
            $label = ($value=='não') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        //Ordenar as colunas
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_cpf->setAction(new TAction([$this, 'onReload']), ['order' => 'cpf']);
        $column_ativo->setAction(new TAction([$this, 'onReload']), ['order' => 'ativo']);
       
        
        $action1 = new TDataGridAction(['ServidorForm', 'onEdit'], ['id'=>'{id}']);
        $action3 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}', 'register_state' => 'false']);
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        
         // create ONOFF action
         $action_onoff = new TDataGridAction(array($this, 'onTurnOnOff'));
         $action_onoff->setButtonClass('btn btn-default');
         $action_onoff->setLabel(_t('Activate/Deactivate'));
         $action_onoff->setImage('fa:power-off orange');
         $action_onoff->setField('id');
         $this->datagrid->addAction($action_onoff);
         //Fim dos botões

        // create the datagrid model
        $this->datagrid->createModel();

        // Busca do datagrid
        $input_search = new TEntry('input_search');
        $input_search->placeholder = _t('Search');
        $input_search->setSize('100%');
        
        // enable fuse search by column name
        $this->datagrid->enableSearch($input_search, 'id, nome, cpf, ativo');
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        $panel->addHeaderWidget($input_search);
        
        
        // Primeiro panel com o título da página
        $pagina = new TVBox;
        $pagina->style = 'width: 100%';
        $pagina->add($this->form);
        $pagina->add($this->html);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($pagina);
        $container->add($panel);
        
        parent::add($container);
    }

    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('permission');
            $SRV = Servidor::find($param['id']);
            if ($SRV instanceof Servidor)
            {
                $SRV->ativo = $SRV->ativo == 'sim' ? 'não' : 'sim';
                $SRV->store();
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