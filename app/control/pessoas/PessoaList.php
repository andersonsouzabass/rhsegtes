<?php
/**
 * ContratoList
 *
 * 
 */
class PessoaList extends TPage
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
        $this->setActiveRecord('ViewPessoa');   // defines the active record
        $this->setDefaultOrder('id', 'desc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('geres', 'like', 'geres'); // filterField, operator, formField
        //$this->addFilterField('perfil', 'like', 'perfil'); // filterField, operator, formField
        //$this->addFilterField('bairro', 'like', 'bairro'); // filterField, operator, formField
        $this->addFilterField('ativo', 'like', 'ativo'); // filterField, operator, formField
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        //$this->setOrderCommand('nome', '(SELECT nome FROM servidor)');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_pessoa_list');
        $this->form->setFormTitle('Lista de Unidades');
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $geres = new TEntry('geres');
        //$bairro = new TEntry('bairro');
        //$perfil= new TEntry('perfil');
        $ativo = new TRadioGroup('ativo');
        $output_type = new TRadioGroup('output_type');

        $nome->forceUpperCase();
        //$bairro->forceUpperCase();
       // $perfil->forceUpperCase();
        $geres->forceUpperCase();
        
        $nome->setMinLength(0);
        
        $ativo->addItems( ['sim' => 'Sim', 'não' => 'Não', '' => 'Todos'] );
        $ativo->setLayout('horizontal');
        $ativo->setValue('sim');
        $ativo->setUseButton();
        
        
        // add the fields
               
        $row = $this->form->addFields(  [ new TLabel('Código'), $id ],
                                        [ new TLabel('GERES'), $geres ]                                   
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Unidade'), $nome ]                                                                           
                                        );
        $row->layout = ['col-sm-6'];

        $row = $this->form->addFields(  [ new TLabel('Ativo'), $ativo ],
                                        //[ new TLabel('Formato'), $output_type ]
                                        );
        $row->layout = ['col-sm-2'];



        $output_type->addValidation('Output', new TRequiredValidator);



        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $geres->setSize('100%');
        //$bairro->setSize('100%');
        //$ativo->setSize('100%');
        
        $output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'Word', 'xls' => 'Excel'));
        $output_type->setLayout('horizontal');
        $output_type->setUseButton();
        $output_type->setValue('pdf');
        //$output_type->setSize('100%');
        
        $output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'Word', 'xls' => 'Excel'));
        $output_type->setLayout('horizontal');
        $output_type->setUseButton();
        $output_type->setValue('pdf');
        //$output_type->setSize('100%');

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Cadastrar', new TAction(['PessoaForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus green');
        
         // add the action button
         //$btn = $this->form->addAction('Gerar Relatório', new TAction(array($this, 'onGenerate')), 'fa:cog');
         //$btn->class = 'btn btn-success btn-sm';

        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        //$this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Código', 'center',  '10%');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        //$column_perfil = new TDataGridColumn('perfil', 'Perfil', 'left');     
        $column_geres = new TDataGridColumn('geres', 'GERES', 'left');
        //$column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
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
        //$this->datagrid->addColumn($column_perfil);
        $this->datagrid->addColumn($column_geres);
        //$this->datagrid->addColumn($column_bairro);
        $this->datagrid->addColumn($column_ativo);

        // creates the datagrid column actions
        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        
        $column_ativo->enableAutoHide(500);
        
        $action1 = new TDataGridAction(['PessoaForm', 'onEdit'], ['id'=>'{id}']);
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
    
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'gratifica'
            TTransaction::open('rh');
            
            // get the form data into an active record
            $data = $this->form->getData();
            
            $this->form->validate();
            
            $repository = new TRepository('ViewPessoa');
            $criteria   = new TCriteria;
            
            if ($data->id)
            {
                $criteria->add(new TFilter('id', '=', "{$data->id}"));
            }
            if ($data->nome)
            {
                $criteria->add(new TFilter('nome', 'like', "%{$data->nome}%"));
            }
            /*
            if ($data->bairro)
            {
                $criteria->add(new TFilter('bairro', 'like', "%{$data->bairro}%"));
            }
            */
            if ($data->ativo)
            {
                $criteria->add(new TFilter('ativo', 'like', "%{$data->ativo}%"));
            }
            /*
            if ($data->perfil)
            {
                $criteria->add(new TFilter('perfil', 'like', "%{$data->perfil}%"));
            }*/

           
            $objects = $repository->load($criteria, FALSE);
            $format  = $data->output_type;
            
            if ($objects)
            {
                $widths = array(50,400,400,50,400);
                
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'rtf':
                        $tr = new TTableWriterRTF($widths);
                        break;
                }
                
                // create the document styles
                $tr->addStyle('title', 'Arial', '10', 'B',   '#ffffff', '#20B2AA');
                $tr->addStyle('datap', 'Arial', '10', '',    '#000000', '#EEEEEE');
                $tr->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff');
                $tr->addStyle('header', 'Arial', '16', '',   '#ffffff', '#008080');
                $tr->addStyle('footer', 'Times', '10', 'I',  '#000000', '#20B2AA');
                
                // add a header row
                $tr->addRow();
                $tr->addCell('Relação de Unidades', 'center', 'header', 5);
                
                // add titles row
                $tr->addRow();
                $tr->addCell('Código', 'right', 'title');
                $tr->addCell('Nome', 'left', 'title');
                $tr->addCell('Bairro', 'left', 'title');
                $tr->addCell('Ativo', 'left', 'title');
                $tr->addCell('Perfil', 'left', 'title');

                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objects as $object)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($object->id, 'right', $style);
                    $tr->addCell($object->nome, 'left', $style);
                    $tr->addCell($object->bairro, 'left', $style);
                    $tr->addCell($object->ativo, 'left', $style);
                    $tr->addCell($object->perfil, 'left', $style);

                    
                    $colour = !$colour;
                }
                
                // footer row
                //$tr->addRow();
                //$tr->addCell(date('d/m/Y h:i:s'), 'center', 'footer', 5);
                
                // stores the file
                if (!file_exists("app/output/ViewPessoa.{$format}") OR is_writable("app/output/ViewPessoa.{$format}"))
                {
                    $tr->save("app/output/ViewPessoa.{$format}");
                }
                else
                {
                    throw new Exception(_t('Permission denied') . ': ' . "app/output/ViewPessoa.{$format}");
                }
                
                // open the report file
                parent::openFile("app/output/ViewPessoa.{$format}");
                
                // shows the success message
                new TMessage('info', 'Relatório Gerado com Sucesso. Por favor, habilite os popups.');
            }
            else
            {
                new TMessage('error', 'Sem dados');
            }
    
            // fill the form with the active record data
            $this->form->setData($data);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    /**
     * Turn on/off an user
     */
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('rh');
            $srv = Pessoa::find($param['id']);
            
            if ($srv instanceof Pessoa)
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
