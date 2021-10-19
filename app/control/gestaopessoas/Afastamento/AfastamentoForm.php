<?php
/**
 * AfastamentoForm Master/Detail
 * @author  <your name here>
 */
class AfastamentoForm extends TWindow
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
        $this->form = new BootstrapFormBuilder('form_afastamento');
        $this->form->setFormTitle('Apontamento de Afastamento');
        
        // master fields
        $id = new TEntry('id');
        $matricula = new TEntry('matricula');
        $ativo = new TEntry('ativo');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');
        $unidade = new TEntry('unidade');
        $funcao = new TEntry('funcao');
        $cargo = new TEntry('cargo');

        $cpf->setMask('999.999.999-99', true);
        
        $id->setSize('100%');
        $matricula->setSize('100%');
        $ativo->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');
        $unidade->setSize('100%');
        $funcao->setSize('100%');
        $cargo->setSize('100%');

        $id->setEditable(FALSE);
        $matricula->setEditable(FALSE);
        $ativo->setEditable(FALSE);
        $nome->setEditable(FALSE);
        $cpf->setEditable(FALSE);
        $unidade->setEditable(FALSE);
        $funcao->setEditable(FALSE);
        $cargo->setEditable(FALSE);

        // detail fields
        $detail_uniqid = new THidden('detail_uniqid');
        $detail_id = new THidden('detail_id');
        $detail_tipo_afastamento_id = new TDBUniqueSearch('detail_tipo_afastamento_id', 'rh', 'TipoAfastamento', 'id', 'nome');
        $detail_observacao = new TText('detail_observacao');
        $detail_dt_inicio = new TDate('detail_dt_inicio');
        $detail_dt_fim = new TDate('detail_dt_fim');

        $detail_tipo_afastamento = new TEntry('detail_tipo_afastamento');
       
        $detail_uniqid->setSize('100%');
        $detail_id->setSize('100%');
        $detail_tipo_afastamento_id->setSize('100%');
        $detail_observacao->setSize('100%');
        $detail_dt_inicio->setSize('100%');
        $detail_dt_fim->setSize('100%');

        $detail_dt_inicio->setMask('dd/mm/yyyy');
        $detail_dt_fim->setMask('dd/mm/yyyy');

        $detail_dt_inicio->setDatabaseMask('yyyy-mm-dd');
        $detail_dt_fim->setDatabaseMask('yyyy-mm-dd');
        
        
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
        // master fields
        
        // detail fields
        $this->form->addContent( ['<h4>Período Afastado e Anotação</h4><hr>'] );
        $this->form->addFields( [$detail_uniqid] );
        $this->form->addFields( [$detail_id] );

        $row = $this->form->addFields(  [ new TLabel('Tipo Afastamento'), $detail_tipo_afastamento_id ]
                                        );
        $row->layout = ['col-sm-8'];

        $row = $this->form->addFields(  [ new TLabel('Data Início'), $detail_dt_inicio ],
                                        [ new TLabel('Data Término'), $detail_dt_fim ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-3'];

        $row = $this->form->addFields(  [ new TLabel('Anotação'), $detail_observacao ]
                                        );
        $row->layout = ['col-sm-8'];
        
        $add = TButton::create('add', [$this, 'onDetailAdd'], 'Adicionar Afastamento', 'fa:plus-circle green');
        $add->getAction()->setParameter('static','1');

        $row = $this->form->addFields(  [ $add ]
                                        );
        $row->layout = ['col-sm-4'];

        
        $this->detail_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->detail_list->setId('Afastamento_list');
        $this->detail_list->generateHiddenFields();
        $this->detail_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";
        
        // Formatando as datas
        $column_dt_inicio = new TDataGridColumn('dt_inicio', 'Data Início', 'left', 30);
        $column_dt_inicio->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $column_dt_final = new TDataGridColumn('dt_fim', 'Data Final', 'left', 30);
        $column_dt_final->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        // items
        $this->detail_list->addColumn( new TDataGridColumn('uniqid', 'Uniqid', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('id', 'Id', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('tipo_afastamento_id', 'Tipo Afastamento', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('tipo_afastamento', 'Tipo Afastamento', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('observacao', 'Anotação', 'left', 100) );
        $this->detail_list->addColumn( $column_dt_inicio );
        $this->detail_list->addColumn( $column_dt_final );
        
        //$this->detail_list->addColumn( new TDataGridColumn('dt_inicio', 'Início', 'left', 50) );
        //$this->detail_list->addColumn( new TDataGridColumn('dt_fim', 'Término', 'left', 50) );

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

        //Botões de Salvar e Fechar
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['AfastamentoList', 'onReload']), 'fa:times red' );
        // create the form actions
        $btn = $this->form->addAction( _t('Save'), new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';

        $this->form->addActionLink('Cancelar', new TAction(array('AfastamentoList','onReload')),  'fa:times red' );
        
        /*
        $this->form->addAction( 'Save',  new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save green');
        $this->form->addAction( 'Clear', new TAction([$this, 'onClear']), 'fa:eraser red');
        */

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
            
            if ($data->detail_dt_inicio > $data->detail_dt_fim)
            {
                throw new Exception('A Data Final não pode ser anterior a Data Inicial');
            }
            

            $uniqid = !empty($data->detail_uniqid) ? $data->detail_uniqid : uniqid();
            
            $grid_data = [];
            $grid_data['uniqid'] = $uniqid;
            $grid_data['id'] = $data->detail_id;
            $grid_data['tipo_afastamento_id'] = $data->detail_tipo_afastamento_id;

            if (!empty($data->detail_observacao))
            {
                $grid_data['observacao'] = $data->detail_observacao;
            }

            $grid_data['dt_inicio'] = $data->detail_dt_inicio;
            $grid_data['dt_fim'] = $data->detail_dt_fim;
            
            TTransaction::open('rh');
            // Vinculo
            $cAfast = TipoAfastamento::find($data->detail_tipo_afastamento_id);
            if ($cAfast instanceof TipoAfastamento)
            {
                $data->detail_tipo_afastamento = $cAfast->nome;                                
            }  
            TTransaction::close();

            $grid_data['tipo_afastamento'] = $data->detail_tipo_afastamento;

            // insert row dynamically
            $row = $this->detail_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('Afastamento_list', $uniqid, $row);
            
            // clear detail form fields
            $data->detail_uniqid = '';
            $data->detail_id = '';
            $data->detail_tipo_afastamento_id = '';
            $data->detail_tipo_afastamento = '';
            $data->detail_observacao = '';
            $data->detail_dt_inicio = '';
            $data->detail_dt_fim = '';
            
            // send data, do not fire change/exit events
            TForm::sendData( 'form_afastamento', $data, false, false );
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
        
        $data_fn = new DateTime($param['dt_fim']);
        $dt_result_fin = $data_in->format('d/m/Y');

        $data->detail_uniqid = $param['uniqid'];
        $data->detail_id = $param['id'];
        $data->detail_tipo_afastamento_id = $param['tipo_afastamento_id'];
        $data->detail_observacao = $param['observacao'];
        $data->detail_dt_inicio = $dt_result;
        $data->detail_dt_fim = $dt_result_fin;
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_afastamento', $data, false, false );
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
        $data->detail_tipo_afastamento_id = '';
        $data->detail_observacao = '';
        $data->detail_dt_inicio = '';
        $data->detail_dt_fim = '';
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_ViewVinculoServidor', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('Afastamento_list', $param['uniqid']);
    }
    
    /**
     * Load Master/Detail data from database to form
     */
    public function onEdit($param)
    {
        try
        {
            TTransaction::open('rh');
            
            if (isset($param['key']))
            {
                $key = $param['key'];
                
                $object = new ViewVinculoServidor($key);
                $items  = Afastamento::where('servidor_vinculo_id', '=', $key)->load();
                
                foreach( $items as $item )
                {
                    // Vinculo
                    $cAfast = TipoAfastamento::find($item->tipo_afastamento_id);
                    if ($cAfast instanceof TipoAfastamento)
                    {
                        $item->tipo_afastamento = $cAfast->nome;                                
                    }

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
            TTransaction::open('rh');
            
            $data = $this->form->getData();
            $this->form->validate();
            
            $master = new ViewVinculoServidor;
            $master->fromArray( (array) $data);
            //$master->store();
            
            Afastamento::where('servidor_vinculo_id', '=', $master->id)->delete();
            
            if( isset($param['Afastamento_list_tipo_afastamento_id'] ))
            {
                foreach( $param['Afastamento_list_tipo_afastamento_id'] as $key => $item_id )
                {
                    $detail = new Afastamento;
                    $detail->tipo_afastamento_id  = $param['Afastamento_list_tipo_afastamento_id'][$key];
                    $detail->observacao  = $param['Afastamento_list_observacao'][$key];
                    $detail->dt_inicio  = $param['Afastamento_list_dt_inicio'][$key];
                    $detail->dt_fim  = $param['Afastamento_list_dt_fim'][$key];
                    $detail->servidor_vinculo_id = $master->id;
                    $detail->system_user_id = TSession::getValue('userid');
                    $detail->store();
                }
            }
            TTransaction::close(); // close the transaction
            
            TForm::sendData('form_ViewVinculoServidor', (object) ['id' => $master->id]);
            
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
