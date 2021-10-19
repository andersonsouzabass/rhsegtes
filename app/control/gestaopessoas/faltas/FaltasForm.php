<?php
/**
 * ViewVinculoServidorForm Master/Detail
 * @author  <your name here>
 */
class FaltasForm extends TWindow
{
    protected $form; // form
    protected $detail_list;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        parent::setSize(0.6, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Faltas');
        $this->form->setFormTitle('Apontamento de Faltas');
        
        // master fields
        $id = new TEntry('id');
        $matricula = new TEntry('matricula');
        $ativo = new TEntry('ativo');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');
        $unidade = new TEntry('unidade');
        $funcao = new TEntry('funcao');
        $cargo = new TEntry('cargo');

        // detail fields
        $detail_uniqid = new THidden('detail_uniqid');
        $detail_id = new THidden('detail_id');
        $detail_dt_inicio = new TDate('detail_dt_inicio');
        $detail_dt_final = new TDate('detail_dt_final');
        $detail_justificada = new TRadioGroup('detail_justificada');
        $detail_justificativa = new TText('detail_justificativa');

        $detail_justificada->addItems ([
            'SIM' => 'SIM',
            'NÃO' => 'NÃO'
            ]);

        $detail_justificada->setValue('SIM');


        $data_in_filtro = new TDate('data_in_filtro');
        $data_final_filtro = new TDate('data_final_filtro');
        
        $id->setEditable(FALSE);
        $matricula->setEditable(FALSE);
        $ativo->setEditable(FALSE);
        $nome->setEditable(FALSE);
        $cpf->setEditable(FALSE);
        $unidade->setEditable(FALSE);
        $funcao->setEditable(FALSE);
        $cargo->setEditable(FALSE);

        $id->setSize('100%');
        $matricula->setSize('100%');
        $ativo->setSize('100%');
        $nome->setSize('100%');;
        $cpf->setSize('100%');
        $unidade->setSize('100%');
        $funcao->setSize('100%');
        $cargo->setSize('100%');

        $detail_uniqid->setSize('100%');
        $detail_id->setSize('100%');
        $detail_dt_inicio->setSize('100%');
        $detail_dt_final->setSize('100%');
        //$detail_justificada->setSize('100%');
        $detail_justificativa->setSize('100%');

        $detail_justificada->setUseButton();
        $detail_justificada->setLayout('horizontal');

        $cpf->setMask('999.999.999-99', true);

        $detail_dt_inicio->setMask('dd/mm/yyyy');
        $detail_dt_final->setMask('dd/mm/yyyy');

        $detail_dt_inicio->setDatabaseMask('yyyy-mm-dd');
        $detail_dt_final->setDatabaseMask('yyyy-mm-dd');

        $data_in_filtro->setSize('100%');
        $data_final_filtro->setSize('100%');

        $data_in_filtro->setMask('dd/mm/yyyy');
        $data_final_filtro->setMask('dd/mm/yyyy');

        $data_in_filtro->setDatabaseMask('yyyy-mm-dd');
        $data_final_filtro->setDatabaseMask('yyyy-mm-dd');

        
        //adiciona campos ao formulário
        $row = $this->form->addFields(  [ new TLabel('Id'), $id ],
                                        [ new TLabel('Matricula'), $matricula ],
                                        [ new TLabel('Nome'), $nome ],                                        
                                        [ new TLabel('Cpf'), $cpf ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-6', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Unidade'), $unidade ],
                                        [ new TLabel('Cargo'), $cargo ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-6'];
        
        $row = $this->form->addFields(  [ new TLabel('Função'), $funcao ],
                                        [ new TLabel('Ativo'), $ativo ]
                                        );
        $row->layout = ['col-sm-10', 'col-sm-2'];

        // detail fields
        $this->form->addContent( ['<h4>Apontamento de Faltas</h4><hr>'] );
        $this->form->addFields( [$detail_uniqid] );
        $this->form->addFields( [$detail_id] );
        
        $row = $this->form->addFields(  [ new TLabel('Data Início'), $detail_dt_inicio ],
                                        [ new TLabel('Data Final'), $detail_dt_final ],
                                        [ new TLabel('Justificada'), $detail_justificada ]   
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Justificativa'), $detail_justificativa ]
                                        );
        $row->layout = ['col-sm-12'];

        $add = TButton::create('add', [$this, 'onDetailAdd'], 'Adicionar Falta', 'fa:plus-circle green');
        $add->getAction()->setParameter('static','1');
       
        $row = $this->form->addFields(  [ $add ]
                                        );
        $row->layout = ['col-sm-4'];

        $btn_rel = TButton::create('btn_rel', [$this, 'onGenerate'], 'Gerar XLS', 'fa:arrow-down green');
        $btn_rel->getAction()->setParameter('static','1');

        /*
        $row = $this->form->addFields(  [ new TLabel('Data Início'), $data_in_filtro ],
                                        [ new TLabel('Data Final'), $data_final_filtro ],
                                        [ new TLabel('Gerar Relatório Entre Datas'), $btn_rel ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-4'];
        */
        
        $this->detail_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->detail_list->setId('Faltas_list');
        $this->detail_list->generateHiddenFields();
        $this->detail_list->style = "min-width: 400px; width:100%;margin-bottom: 10px";
        
        // Formatando as datas
        $column_dt_inicio = new TDataGridColumn('dt_inicio', 'Data Início', 'left', 30);
        $column_dt_inicio->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $column_dt_final = new TDataGridColumn('dt_final', 'Data Final', 'left', 30);
        $column_dt_final->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $this->detail_list->addColumn( new TDataGridColumn('uniqid', 'Uniqid', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('id', 'Id', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( $column_dt_inicio );
        $this->detail_list->addColumn( $column_dt_final );
        $this->detail_list->addColumn( new TDataGridColumn('justificada', 'Justificada', 'left', 30) );
        $this->detail_list->addColumn( new TDataGridColumn('justificativa', 'Justificativa', 'left', 150) );

        // detail actions
        $action1 = new TDataGridAction([$this, 'onDetailEdit'] );
        $action1->setFields( ['uniqid', '*'] );
        
        $action2 = new TDataGridAction([$this, 'onDetailDelete']);
        $action2->setField('uniqid');
        
        // add the actions to the datagrid
        $this->detail_list->addAction($action1, _t('Edit'), 'fa:edit blue');
        $this->detail_list->addAction($action2, _t('Delete'), 'far:trash-alt red');
        
        $this->detail_list->createModel();
        
        $panel = new TPanelGroup;
        $panel->add($this->detail_list);
        $panel->getBody()->style = 'overflow-x:auto';
        $this->form->addContent( [$panel] );
        
        // create the form actions
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['FaltasFormList', 'onReload']), 'fa:times red' );
        $btn = $this->form->addAction( _t('Save'), new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Cancelar', new TAction(array('FaltasFormList','onReload')),  'fa:times red' );

        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        parent::add($container);
    }
    
    
    /**
     * Clear form
     * @param $param URL parameters
     */
    public function onClear($param)
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Add detail item
     * @param $param URL parameters
     */
    public function onDetailAdd( $param )
    {
        try
        {
            $this->form->validate();
            $data = $this->form->getData();
            
            //Valida se data término é menos que data inicio
            if (empty($data->detail_dt_inicio))
            {
                throw new Exception('Adicione uma Data Início válida');
            }
            
            if ($data->detail_dt_inicio > $data->detail_dt_final)
            {
                throw new Exception('A Data Final não pode ser anterior a Data Inicial');
            }

            if ($data->detail_justificada == 'SIM')
            {
                if($data->detail_justificativa == null)
                {
                    throw new Exception('Se a falta é justificada, você deve informar uma justificativa válida');
                }
            }
            
            $uniqid = !empty($data->detail_uniqid) ? $data->detail_uniqid : uniqid();           
            
            $grid_data = [];
            $grid_data['uniqid'] = $uniqid;
            $grid_data['id'] = $data->detail_id;
            $grid_data['dt_inicio'] = $data->detail_dt_inicio;
            $grid_data['dt_final'] = $data->detail_dt_final;
            $grid_data['justificada'] = $data->detail_justificada;
            $grid_data['justificativa'] = $data->detail_justificativa;
            
            // insert row dynamically
            $row = $this->detail_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('Faltas_list', $uniqid, $row);
            
            // clear detail form fields
            $data->detail_uniqid = '';
            $data->detail_id = '';
            $data->detail_dt_inicio = '';
            $data->detail_dt_final = '';
            $data->detail_justificada = '';
            $data->detail_justificativa = '';
            
            // send data, do not fire change/exit events
            TForm::sendData( 'form_Faltas', $data, false, false );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Edit detail item
     * @param $param URL parameters
     */
    public static function onDetailEdit( $param )
    {
        $data = new stdClass;

        $data_in = new DateTime($param['dt_inicio']);
        $dt_result = $data_in->format('d/m/Y');
        
        $data_fn = new DateTime($param['dt_final']);
        $dt_result_fin = $data_in->format('d/m/Y');

        $data->detail_uniqid = $param['uniqid'];
        $data->detail_id = $param['id'];
        $data->detail_dt_inicio =  $dt_result;
        $data->detail_dt_final = $dt_result_fin;
        $data->detail_justificada = $param['justificada'];
        $data->detail_justificativa = $param['justificativa'];

        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_Faltas', $data, false, false );
    }
    
    /**
     * Delete detail item
     * @param $param URL parameters
     */
    public static function onDetailDelete( $param )
    {
        // clear detail form fields
        $data = new stdClass;
        $data->detail_uniqid = '';
        $data->detail_id = '';
        $data->detail_dt_inicio = '';
        $data->detail_dt_final = '';
        $data->detail_justificada = '';
        $data->detail_justificativa = '';
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_Faltas', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('Faltas_list', $param['uniqid']);
    }
    
    /**
     * Load Master/Detail data from database to form
     */
    public function onEdit($param)
    {
        try
        {
            TTransaction::open('gratifica');
            
            if (isset($param['key']))
            {
                $key = $param['key'];
                
                $object = new ViewVinculoServidor($key);
                $items  = Faltas::where('servidor_id', '=', $key)->load();
                
                foreach( $items as $item )
                {
                    $item->uniqid = uniqid();
                    $row = $this->detail_list->addItem( $item );
                    $row->id = $item->uniqid;
                }
                $this->form->setData($object);
                TTransaction::close();
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Save the Master/Detail data from form to database
     */
    public function onSave($param)
    {
        try
        {
            // open a transaction with database
            TTransaction::open('gratifica');
            
            $data = $this->form->getData();
            $this->form->validate();
            
            
            $master = new ViewVinculoServidor;
            $master->fromArray( (array) $data);
            //$master->store();
            
            Faltas::where('servidor_id', '=', $master->id)->delete();
            
            if( isset($param['Faltas_list_dt_inicio'] ))
            {
                foreach( $param['Faltas_list_dt_inicio'] as $key => $item_id )
                {
                    $detail = new Faltas;
                    $detail->dt_inicio  = $param['Faltas_list_dt_inicio'][$key];
                    $detail->dt_final  = $param['Faltas_list_dt_final'][$key];
                    $detail->justificada  = $param['Faltas_list_justificada'][$key];
                    $detail->justificativa  = $param['Faltas_list_justificativa'][$key];
                    $detail->servidor_id = $master->id;
                    $detail->system_user_id = TSession::getValue('userid');
                    $detail->store();
                }
            }
            TTransaction::close(); // close the transaction
            
            TForm::sendData('form_Faltas', (object) ['id' => $master->id]);
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }

    public function onGenerate()
    {
        try
        {
            // get the form data into an active record Customer
            $data = $this->form->getData();
            $this->form->validate();
            
            
            $master = new ViewVinculoServidor;
            $master->fromArray( (array) $data);
            //$master->store();
            
            //new TMessage ('error', $master->matricula);
            
            $format = 'xls';

            // open a transaction with database 'samples'
            $source = TTransaction::open('gratifica');
            
            // define the query
            $query = "SELECT ft.matricula as 'matricula',
                             ft.nome as 'nome',
                             ft.unidade as 'unidade',
                             ft.justificada as 'justificada',
                             ft.justificativa as 'justificativa',
                             ft.dt_inicio as 'Data_Inicial',
                             ft.dt_final as 'Data_Fim'
                       FROM  view_faltas ft
                      WHERE  ft.matricula = :matricula";
            
            $rows = TDatabase::getData($source, $query, null, [ 'matricula' => $master->matricula ]);
            
            if ($rows)
            {
                $widths = array(40, 200, 400, 40, 800, 80, 80);
                
                switch ($format)
                {
                    case 'html':
                        $table = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $table = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        $table = new TTableWriterRTF($widths);
                        break;
                    case 'xls':
                        $table = new TTableWriterXLS($widths);
                        break;
                }
                
                if (!empty($table))
                {
                    // create the document styles
                    $table->addStyle('header', 'Calibri', '12', 'B', '#ffffff', '#4B8E57');
                    $table->addStyle('title',  'Calibri', '10', 'B', '#ffffff', '#6CC361');
                    $table->addStyle('datap',  'Calibri', '10', '',  '#000000', '#E3E3E3', 'LR');
                    $table->addStyle('datai',  'Calibri', '10', '',  '#000000', '#ffffff', 'LR');
                    $table->addStyle('footer', 'Calibri', '10', '',  '#2B2B2B', '#B5FFB4');
                    
                    $table->setHeaderCallback( function($table) {
                        $table->addRow();
                        $table->addCell('Relatório de Faltas do Servidor', 'center', 'header', 7);
                        
                        $table->addRow();
                        $table->addCell('Matrícula', 'center', 'title');
                        $table->addCell('Nome',      'left', 'title');
                        $table->addCell('Lotação',   'center', 'title');                        
                        $table->addCell('Justificada', 'center', 'title');
                        $table->addCell('Justificativa', 'center', 'title');
                        $table->addCell('Data Inicial', 'left', 'title');
                        $table->addCell('Data Final', 'center', 'title');
                    });
                    
                    $table->setFooterCallback( function($table) {
                        $table->addRow();
                        $table->addCell(date('d/m/Y h:i:s'), 'center', 'footer', 7);
                    });
                    
                    // controls the background filling
                    $colour= FALSE;
                    
                    // data rows
                    foreach ($rows as $row)
                    {

                        $style = $colour ? 'datap' : 'datai';
                        $table->addRow();
                        $table->addCell($row['matricula'],    'center', $style);
                        $table->addCell($row['nome'],         'center',   $style);
                        $table->addCell($row['unidade'],      'center', $style);
                        $table->addCell($row['justificada'],  'center',   $style);
                        $table->addCell($row['justificativa'],'left', $style);
                        $table->addCell($row['Data_Inicial'], 'center', $style);
                        $table->addCell($row['Data_Fim'],     'center', $style);
                        
                        $colour = !$colour;
                    }
                    
                    $output = "app/output/faltas.{$format}";
                    
                    // stores the file
                    if (!file_exists($output) OR is_writable($output))
                    {
                        $table->save($output);
                        parent::openFile($output);
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . $output);
                    }
                    
                    // shows the success message
                    new TMessage('info', "Relatório gerado. Por favor, habilite os popups do browser. <br> <a href='$output'>Clique aqui para baixar o arquivo</a>");
                }
            }
            else
            {
                new TMessage('error', 'Sem registros');
            }
    
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
