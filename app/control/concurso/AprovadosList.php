<?php
/**
 * AprovadosList Listing
 * @author  <your name here>
 */
class AprovadosList extends TPage
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
        $this->setActiveRecord('Aprovados');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('ano', 'like', 'ano'); // filterField, operator, formField
        $this->addFilterField('ato', '=', 'ato'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('cpf', 'like', 'cpf'); // filterField, operator, formField
        $this->addFilterField('cargo', 'like', 'cargo'); // filterField, operator, formField
        $this->addFilterField('classif', '=', 'classif'); // filterField, operator, formField
        $this->addFilterField('classif_def', 'like', 'classif_def'); // filterField, operator, formField
        $this->addFilterField('tipo_def', 'like', 'tipo_def'); // filterField, operator, formField
        $this->addFilterField('insc', 'like', 'insc'); // filterField, operator, formField

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Aprovados');
        $this->form->setFormTitle('Aprovados');
        

        // create the form fields
        $ano = new TEntry('ano');
        $ato = new TDBUniqueSearch('ato', 'rh', 'Ato', 'id', 'ato');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');
        $cargo = new TEntry('cargo');
        $classif = new TEntry('classif');
        $insc = new TEntry('insc');
        

         // add the fields       
        $row = $this->form->addFields(  [ new TLabel('Ano'), $ano ],
                                        [ new TLabel('Ato'), $ato ],
                                        [ new TLabel('Classificação'), $classif ],
                                        [ new TLabel('Inscrição'), $insc ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Nome'), $nome ],
                                        [ new TLabel('Cpf'), $cpf ],
                                        [ new TLabel('Cargo'), $cargo ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-2', 'col-sm-4'];

       

        // set sizes
        $ano->setSize('100%');
        $ato->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');
        $cargo->setSize('100%');
        $classif->setSize('100%');
        $insc->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Incluir Aprovado', new TAction(['AprovadosEditForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_ano = new TDataGridColumn('ano', 'Ano', 'left', 10);
        $column_ato = new TDataGridColumn('ato', 'Ato', 'left', 5);
        $column_insc = new TDataGridColumn('insc', 'Inscrição', 'left', 10);
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left', 300);
        $column_identidade = new TDataGridColumn('identidade', 'RG', 'left', 40);
        $column_cpf = new TDataGridColumn('cpf', 'Cpf', 'left', 40);
        $column_nascimento = new TDataGridColumn('nascimento', 'Nascimento', 'left', 40);
        $column_cod_cargo = new TDataGridColumn('cod_cargo', 'Cod Cargo', 'left', 20);
        $column_cargo = new TDataGridColumn('cargo', 'Cargo', 'left', 500);
        $column_classif = new TDataGridColumn('classif', 'Classif', 'left', 5);
        $column_classif_def = new TDataGridColumn('classif_def', 'Classif Def', 'left');
        $column_tipo_def = new TDataGridColumn('tipo_def', 'Tipo Def', 'left');
        $column_nota_final = new TDataGridColumn('nota_final', 'Nota', 'center',10);
        $column_resultado = new TDataGridColumn('resultado', 'Resultado', 'center');
        $column_endereco = new TDataGridColumn('endereco', 'Endereco', 'left');
        $column_num = new TDataGridColumn('num', 'Num', 'left');
        $column_complemento = new TDataGridColumn('complemento', 'Complemento', 'left');
        $column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
        $column_cep = new TDataGridColumn('cep', 'Cep', 'left');
        $column_cidade = new TDataGridColumn('cidade', 'Cidade', 'left');
        $column_estado = new TDataGridColumn('estado', 'Estado', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_fone = new TDataGridColumn('fone', 'Fone', 'left');
        $column_celular = new TDataGridColumn('celular', 'Celular', 'left');
        $column_formacao_escolaridade = new TDataGridColumn('formacao_escolaridade', 'Formacao Escolaridade', 'left');
        $column_nome_da_mae = new TDataGridColumn('nome_da_mae', 'Nome Da Mae', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_ano);
        $this->datagrid->addColumn($column_ato);
        $this->datagrid->addColumn($column_insc);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_identidade)->setVisibility(false);
        $this->datagrid->addColumn($column_cpf)->setVisibility(false);
        $this->datagrid->addColumn($column_nascimento);
        $this->datagrid->addColumn($column_cod_cargo)->setVisibility(false);
        $this->datagrid->addColumn($column_cargo);
        $this->datagrid->addColumn($column_classif);
        $this->datagrid->addColumn($column_classif_def)->setVisibility(false);
        $this->datagrid->addColumn($column_tipo_def)->setVisibility(false);
        $this->datagrid->addColumn($column_nota_final);
        $this->datagrid->addColumn($column_resultado)->setVisibility(false);
        $this->datagrid->addColumn($column_endereco)->setVisibility(false);
        $this->datagrid->addColumn($column_num)->setVisibility(false);
        $this->datagrid->addColumn($column_complemento)->setVisibility(false);
        $this->datagrid->addColumn($column_bairro)->setVisibility(false);
        $this->datagrid->addColumn($column_cep)->setVisibility(false);
        $this->datagrid->addColumn($column_cidade)->setVisibility(false);
        $this->datagrid->addColumn($column_estado)->setVisibility(false);
        $this->datagrid->addColumn($column_email)->setVisibility(false);
        $this->datagrid->addColumn($column_fone)->setVisibility(false);
        $this->datagrid->addColumn($column_celular)->setVisibility(false);
        $this->datagrid->addColumn($column_formacao_escolaridade)->setVisibility(false);
        $this->datagrid->addColumn($column_nome_da_mae)->setVisibility(false);
                                        
        //Ações do grid
        $action1 = new TDataGridAction(['AprovadosForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        $action3 = new TDataGridAction(['AprovadosEditForm', 'onEdit'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, 'Encaminhar',   'far:edit blue');
        //$this->datagrid->addAction($action3 ,'Incluir Aprovado', 'far:id-card red');
        
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
