<?php
/**
 * ViewProcessosForm Master/Detail
 * @author  <your name here>
 */
class AdamentoProcessosForm extends TPage
{
    protected $form; // form
    protected $detail_list;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        /*
        parent::setSize(0.6, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        */

        // creates the form
        $this->form = new BootstrapFormBuilder('form_ViewProcessos');
        $this->form->setFormTitle('Atendimento do Processo');
        
        // master fields
        $id = new TEntry('id');
        $assunto = new TEntry('assunto');
        $prazo = new TEntry('prazo');
        $dt_entrada = new TDate('dt_entrada');
        $dt_prazo = new TDate('dt_prazo');
        $interessado = new TEntry('interessado');
        $servidor = new TCombo('servidor');
        $matricula = new TEntry('matricula');
        $cpf = new TEntry('cpf');
        $observacao = new TText('observacao');
        $status = new TEntry('status');

        $servidor->addItems ([
            'sim' => 'SIM',
            'não' => 'NÃO'
            ]);

        // detail fields
        $detail_uniqid = new THidden('detail_uniqid');
        $detail_id = new THidden('detail_id');
        $detail_despacho = new TText('detail_despacho');
        $detail_status = new TCombo('detail_status');
        $detail_data = new TCombo('detail_data');

        $detail_status->addItems ([
            'EM ATENDIMENTO' => 'EM ATENDIMENTO',
            'FINALIZADO' => 'FINALIZADO'
            ]);

        $detail_status->setValue('EM ATENDIMENTO');

        $id->setEditable(FALSE);
        $assunto->setEditable(FALSE);
        $dt_entrada->setEditable(FALSE);  
        $dt_prazo->setEditable(FALSE);  
        $interessado->setEditable(FALSE);
        $servidor->setEditable(FALSE);
        $matricula->setEditable(FALSE);
        $cpf->setEditable(FALSE);
        $observacao->setEditable(FALSE);
        $status->setEditable(FALSE);
        
        // master fields
        $row = $this->form->addFields(  [ new TLabel('Processo'), $id ],
                                        [ new TLabel('Assunto'), $assunto ],
                                        [ new TLabel('Entrada'), $dt_entrada ],
                                        [ new TLabel('Servidor'), $servidor ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-6', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Interessado'), $interessado ],
                                        [ new TLabel('Prazo'), $dt_prazo ],
                                        [ new TLabel('Situação'), $status ]
                                        );
        $row->layout = ['col-sm-8', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Matrícula'), $matricula ],
                                        [ new TLabel('CPF'), $cpf ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Observação'), $observacao ],
                                        );
        $row->layout = ['col-sm-8'];
        //Final dos campos

        $id->setSize('100%');
        $assunto->setSize('100%');
        $dt_entrada->setSize('100%'); 
        $dt_prazo->setSize('100%'); 
        $interessado->setSize('100%');
        $servidor->setSize('100%');
        $matricula->setSize('100%');
        $cpf->setSize('100%');
        $observacao->setSize('100%');
        $status->setSize('100%');

        $cpf->setMask('999.999.999-99', true);
        $dt_entrada->setMask('dd/mm/yyyy');
        $dt_prazo->setMask('dd/mm/yyyy');

        $dt_entrada->setDatabaseMask('yyyy-mm-dd');
        $dt_prazo->setDatabaseMask('yyyy-mm-dd');


        // detail fields
        $this->form->addContent( ['<h4>Andamento do Documento</h4><hr>'] );
        $this->form->addFields( [$detail_uniqid] );
        $this->form->addFields( [$detail_id] );
        
        $row = $this->form->addFields(  [ new TLabel('Situação'), $detail_status ]
                                        );
        $row->layout = ['col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Despacho'), $detail_despacho ]
                                        
                                        );
        $row->layout = ['col-sm-8'];

        $detail_despacho->setSize('100%');
        $detail_status->setSize('100%');
        
        /*
        $this->form->addFields( [new TLabel('Despacho')], [$detail_despacho] );
        $this->form->addFields( [new TLabel('Status')], [$detail_status] );
        */

        $add = TButton::create('add', [$this, 'onDetailAdd'], 'Adicionar Despacho', 'fa:plus-circle green');
        $add->getAction()->setParameter('static','1');
        //$this->form->addFields( [], [$add] );

        $row = $this->form->addFields( [ $add ]
                                        );
        $row->layout = ['col-sm-4'];
        
        // Formatando as datas
        $column_dt_registro = new TDataGridColumn('dt_registro', 'Data Registro', 'left', 30);
        $column_dt_registro->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $this->detail_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->detail_list->setId('AndamentoProcessos_list');
        $this->detail_list->generateHiddenFields();
        $this->detail_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";
        
        // items
        $this->detail_list->addColumn( new TDataGridColumn('uniqid', 'Uniqid', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('id', 'Id', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('despacho', 'Despacho', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('status', 'Status', 'left', 100) );
        $this->detail_list->addColumn( $column_dt_registro );

        // detail actions
        $action1 = new TDataGridAction([$this, 'onDetailEdit'] );
        $action1->setFields( ['uniqid', '*'] );
        
        $action2 = new TDataGridAction([$this, 'onDetailDelete']);
        $action2->setField('uniqid');
        
        // add the actions to the datagrid
        //$this->detail_list->addAction($action1, _t('Edit'), 'fa:edit blue');
        //$this->detail_list->addAction($action2, _t('Delete'), 'far:trash-alt red');
        
        $this->detail_list->createModel();
        
        $panel = new TPanelGroup;
        $panel->add($this->detail_list);
        $panel->getBody()->style = 'overflow-x:auto';
        $this->form->addContent( [$panel] );
        
        $this->form->addAction( 'Save',  new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save green');
        $this->form->addAction( 'Clear', new TAction([$this, 'onClear']), 'fa:eraser red');
        
        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
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
            
            $uniqid = !empty($data->detail_uniqid) ? $data->detail_uniqid : uniqid();
            
            $grid_data = [];
            
            //Data entrada
            $data_in_tb = new DateTime($data->dt_entrada);
            $data->dt_entrada = $data_in_tb->format('d/m/Y');

            $grid_data['uniqid'] = $uniqid;
            $grid_data['id'] = $data->detail_id;
            $grid_data['despacho'] = $data->detail_despacho;
            $grid_data['status'] = $data->detail_status;
            $grid_data['dt_registro'] = $data->dt_entrada;
            
            //Registra no banco de dados
            try
            {
                // open a transaction with database
                TTransaction::open('gratifica');
                
                $data = $this->form->getData();
                $this->form->validate();
                
                $master = new ViewProcessos;
                $master->fromArray( (array) $data);
                //$master->store();
                
                //AndamentoProcessos::where('bd_processos_id', '=', $master->id)->delete();                
                
                //Registra o atendimento do processo
                $detail = new AndamentoProcessos;
                $detail->despacho  = $data->detail_despacho;
                $detail->status  = $data->detail_status;
                $detail->dt_registro = date('Y-m-d');
                $detail->bd_processos_id = $master->id;
                $detail->system_user_id = TSession::getValue('userid');
                $detail->store();
                //Fim do registro do atendimento

                //Atualiza o status da tabela principal do processo
                //Apenas para manter a data original
                $st_data = New Processos;
                $st_data->fromArray( (array) $data);

                //Data entrada
                $data_in = new DateTime($data->dt_entrada);
                $st_data->dt_entrada = $data_in->format('Y-m-d');

                //Data entrada
                $data_fim = new DateTime($data->dt_prazo);
                $st_data->dt_prazo = $data_fim->format('Y-m-d');

                $st_data->status = $data->detail_status;
                $st_data->store();
                
                //TTransaction::close(); // close the transaction
                
                //TForm::sendData('form_ViewProcessos', (object) ['id' => $master->id]);

                $key = $master->id;
                    
                //$object = new ViewProcessos($key);
                $items  = AndamentoProcessos::where('bd_processos_id', '=', $key)->load();
                
                foreach( $items as $item )
                {
                    $item->uniqid = uniqid();
                    $row = $this->detail_list->addItem( $item );
                    $row->id = $item->uniqid;
                }

                $data_fn = new DateTime($master->dt_entrada);
                $master->dt_entrada = $data_fn->format('d/m/Y');
    
                $data_pz = new DateTime($master->dt_prazo);
                $master->dt_prazo = $data_pz->format('d/m/Y');

                $this->form->setData($master);
                TTransaction::close();
                
                
                new TMessage('info', 'Atendimento Registrado com Sucesso');
            }
            catch (Exception $e) // in case of exception
            {
                new TMessage('error', $e->getMessage());
                $this->form->setData( $this->form->getData() ); // keep form data
                TTransaction::rollback();
            }

            // insert row dynamically
            
            $row = $this->detail_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('AndamentoProcessos_list', $uniqid, $row);
            
            // clear detail form fields
            $data->detail_uniqid = '';
            $data->detail_id = '';
            $data->detail_despacho = '';
            $data->detail_status = '';
            
            // send data, do not fire change/exit events
            TForm::sendData( 'form_ViewProcessos', $data, false, false );
            
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
        $data->detail_uniqid = $param['uniqid'];
        $data->detail_id = $param['id'];
        $data->detail_despacho = $param['despacho'];
        $data->detail_status = $param['status'];
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_ViewProcessos', $data, false, false );
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
        $data->detail_despacho = '';
        $data->detail_status = '';
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_ViewProcessos', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('AndamentoProcessos_list', $param['uniqid']);
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
                
                $object = new ViewProcessos($key);
                $items  = AndamentoProcessos::where('bd_processos_id', '=', $key)->load();
                
                foreach( $items as $item )
                {
                    $item->uniqid = uniqid();
                    $row = $this->detail_list->addItem( $item );
                    $row->id = $item->uniqid;
                }

                $data_fn = new DateTime($object->dt_entrada);
                $object->dt_entrada = $data_fn->format('d/m/Y');
    
                $data_pz = new DateTime($object->dt_prazo);
                $object->dt_prazo = $data_pz->format('d/m/Y');

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
            
            $master = new ViewProcessos;
            $master->fromArray( (array) $data);
            //$master->store();
            
            AndamentoProcessos::where('bd_processos_id', '=', $master->id)->delete();
            
            if( $param['AndamentoProcessos_list_despacho'] )
            {
                foreach( $param['AndamentoProcessos_list_despacho'] as $key => $item_id )
                {
                    $detail = new AndamentoProcessos;
                    $detail->despacho  = $param['AndamentoProcessos_list_despacho'][$key];
                    $detail->status  = $param['AndamentoProcessos_list_status'][$key];
                    $detail->dt_registro = date('Y-m-d');
                    $detail->bd_processos_id = $master->id;
                    $detail->store();

                    $st_data = New Processos;
                    $st_data->fromArray( (array) $data);

                    //Data entrada
                    $data_in = new DateTime($data->dt_entrada);
                    $st_data->dt_entrada = $data_in->format('Y-m-d');

                    //Data entrada
                    $data_fim = new DateTime($data->dt_prazo);
                    $st_data->dt_prazo = $data_fim->format('Y-m-d');

                    $st_data->status = $param['AndamentoProcessos_list_status'][$key];
                    $st_data->store();
                }
            }
            TTransaction::close(); // close the transaction
            
            TForm::sendData('form_ViewProcessos', (object) ['id' => $master->id]);
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }
}
