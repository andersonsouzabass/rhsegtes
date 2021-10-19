<?php
/**
 * ContratoList
 *
 * 
 */
class ProcessosList extends TPage
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
        $this->setActiveRecord('ViewProcessos');   // defines the active record
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('interessado', 'like', 'interessado'); // filterField, operator, formField
        $this->addFilterField('assunto', 'like', 'assunto'); // filterField, operator, formField
        $this->addFilterField('cpf', '=', 'cpf'); // filterField, operator, formField
        $this->addFilterField('matricula', '=', 'matricula'); // filterField, operator, formField        
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        //$this->setOrderCommand('nome', '(SELECT nome FROM servidor)');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_processos_list');
        $this->form->setFormTitle('Processos e Documentos - Lista de Processos');
        
        // create the form fields
        $id = new TEntry('id');
        $interessado = new TEntry('interessado');
        $assunto = new TEntry('assunto');
        $cpf= new TEntry('cpf');
        $matricula = new TEntry('matricula');

        $interessado->setMinLength(0);
        $cpf->setMinLength(11);
        $cpf->setMaxLength(11);
        
        // add the fields

        // add the fields
        $row = $this->form->addFields(  [ new TLabel('Código'), $id ],
                                        [ new TLabel('CPF'), $cpf ],
                                        [ new TLabel('Matrícula'), $matricula ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Assunto'), $assunto ],
                                        [ new TLabel('Interessado'), $interessado ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4'];

        
        // set sizes
        $id->setSize('100%');
        $assunto->setSize('100%');
        $interessado->setSize('100%');
        $cpf->setSize('100%');
        $matricula->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Novo Processo', new TAction(['ProcessosForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center',  '10%');
        $column_interessado = new TDataGridColumn('interessado', 'Interessado', 'left');
        $column_assunto = new TDataGridColumn('assunto', 'Assunto', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'left');        
        $column_matrícula = new TDataGridColumn('matricula', 'Matrícula', 'left');
        $column_dt_entrada = new TDataGridColumn('dt_entrada', 'Entrada', 'left');
        $column_dt_prazo = new TDataGridColumn( 'dt_prazo', 'Prazo', 'left');
        
        //$column_dt_prazo($column_dt_prazo, date_interval_create_from_date_string('10 days'));
        //$this->column_dt_prazo('yyyy-mm-dd', strtotime("+".'prazo'." month", $column_dt_entrada));
        //$column_dt_prazo->add(new DateInterval('P10D'));

        $column_dt_entrada->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        //Farois na coluna prazo de vencimento do processo
        $column_dt_prazo->setTransformer( function($value, $object) {
            $today = new DateTime(date('Y-m-d'));
            $end   = new DateTime($value);
            //new TMessage('error', $end);

            $dTempo = $end->diff($today);
            

            $data = TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
            //(!empty($value) && $today >= $end)
            if (!empty($value) && $today >= $end)
            {
                $div = new TElement('span');
                $div->class="label label-warning";
                $div->style="text-shadow:none; font-size:12px";
                $div->add($data);
                return $div;
            }            
            return $data;
        });

        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_interessado);
        $this->datagrid->addColumn($column_assunto);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_matrícula);
        $this->datagrid->addColumn($column_dt_entrada);
        $this->datagrid->addColumn($column_dt_prazo); //->add(New DataInterval('P7D'));
        


        // creates the datagrid column actions
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_interessado->setAction(new TAction([$this, 'onReload']), ['order' => 'interessado']);
        $column_dt_entrada->setAction(new TAction([$this, 'onReload']), ['order' => 'dt_entrada']);
        
        $action1 = new TDataGridAction(['ProcessosForm', 'onEdit'], ['id'=>'{id}']);
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
        
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        
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
