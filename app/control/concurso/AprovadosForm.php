<?php
/**
 * AprovadosForm Master/Detail
 * @author  <your name here>
 */
class AprovadosForm extends TPage
{
    protected $form; // form
    protected $detail_list;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Aprovados');
        $this->form->setFormTitle('Candidato Aprovados');
        
        // master fields
        $id = new TEntry('id');
        $ano = new TEntry('ano');
        $insc = new TEntry('insc');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');
        $cargo = new TEntry('cargo');
        $classif = new TEntry('classif');
        $email = new TEntry('email');
        $fone = new TEntry('fone');
        $celular = new TEntry('celular');

        // detail fields id
        $detail_uniqid = new THidden('detail_uniqid');
        $detail_id = new THidden('detail_id');
        $detail_geres_id = new TDBCombo('detail_geres_id', 'rh', 'Geres', 'id', 'nome', 'nome');
        $detail_ato_id = new TDBUniqueSearch('detail_ato_id', 'rh', 'Ato', 'id', 'ato', 'dt_nomeacao');
        $detail_situacaoconcurso_id = new TEntry('detail_situacaoconcurso_id');
        $detail_obs = new TText('detail_obs');
        $detail_cargo_id = new TDBCombo('detail_cargo_id', 'rh', 'Cargo', 'id', 'nome', 'nome');
        $detail_especialidade_id = new TDBUniqueSearch('detail_especialidade_id', 'rh', 'Especialidade', 'id', 'nome');
        $detail_regime_trabalho_id = new TDBCombo('detail_regime_trabalho_id', 'rh', 'Regime', 'id', 'nome');
        $detail_reconvocacao = new TRadioGroup('detail_reconvocacao');

        $detail_reconvocacao->addItems( [
            'SIM' => 'Sim',
            'NÂO' => 'Não'] );
        $detail_reconvocacao->setLayout('horizontal');
        $detail_reconvocacao->setValue('NÂO');


        //detail fields descrição
        $detail_geres_desc = new TEntry('detail_geres_desc');
        $detail_ato_desc = new TEntry('detail_ato_desc');
        $detail_situacaoconcurso_desc = new TEntry('detail_situacaoconcurso_desc');
        $detail_cargo_desc = new TEntry('detail_cargo_desc');
        $detail_especialidade_desc = new TEntry('detail_especialidade_desc');
        $detail_regime_trabalho_desc = new TEntry('detail_regime_trabalho_desc');

        //Campos do formulário
        $row = $this->form->addFields(  [ new TLabel('Código'), $id ],
                                        [ new TLabel('Concurso'), $ano ],                                        
                                        [ new TLabel('Inscricao'), $insc ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Nome'), $nome ],
                                        [ new TLabel('CPF'), $cpf ],
                                        [ new TLabel('Classificaçao'), $classif ]                                        
                                        );
        $row->layout = ['col-sm-7', 'col-sm-3', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo ]
                                        );
        $row->layout = ['col-sm-12'];

        $row = $this->form->addFields(  [ new TLabel('E-mail'), $email ],
                                        [ new TLabel('Fone'), $fone ],
                                        [ new TLabel('Celular'), $celular ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3'];

          // set sizes
          $id->setSize('100%');
          $ano->setSize('100%');
          $insc->setSize('100%');
          $nome->setSize('100%');
          $cpf->setSize('100%');
          $cargo->setSize('100%');
          $classif->setSize('100%');
          $email->setSize('100%');
          $fone->setSize('100%');
          $celular->setSize('100%');
          
          $cpf->setMask('999.999.999-99', true);
  
          
          $id->setEditable(FALSE);
          $ano->setEditable(FALSE);
          $insc->setEditable(FALSE);
          //$nome->setEditable(FALSE);
          $cpf->setEditable(FALSE);
          $cargo->setEditable(FALSE);
          $classif->setEditable(FALSE);
          //$email->setEditable(FALSE);
          //$fone->setEditable(FALSE);
          //$celular->setEditable(FALSE);
          

        // detail fields
        $this->form->addContent( [''] );
        $this->form->addContent( ['<h4>Nomear Canditato</h4><hr>'] );
        $this->form->addFields( [$detail_uniqid] );
        $this->form->addFields( [$detail_id] );
        
        $detail_situacaoconcurso_id->setValue('2');

        $row = $this->form->addFields(  [ new TLabel('GERES'), $detail_geres_id ],                                                                             
                                        [ new TLabel('Ato'), $detail_ato_id ],
                                        [ new TLabel('Regime'), $detail_regime_trabalho_id ],
                                        [ new TLabel('Reconvocação?'), $detail_reconvocacao ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-3', 'col-sm-3', 'col-sm-3'];

        $row = $this->form->addFields(  [ new TLabel('Cargo'), $detail_cargo_id ],
                                        [ new TLabel('Especialidade'), $detail_especialidade_id ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $row = $this->form->addFields(  [ new TLabel('Observação'), $detail_obs ]
                                        );
        $row->layout = ['col-sm-12'];

        $add = TButton::create('add', [$this, 'onDetailAdd'], 'Incluir Nomeado', 'fa:plus-circle green');
        $add->getAction()->setParameter('static','1');
        //$this->form->addFields( [], [$add] );

        $row = $this->form->addFields(  [ $add ]
                                     );
        $row->layout = ['col-sm-2'];

        $detail_geres_id->setSize('100%');
        $detail_ato_id->setSize('100%');        
        $detail_situacaoconcurso_id->setSize('100%');
        $detail_obs->setSize('100%');
        $detail_cargo_id->setSize('100%');
        $detail_especialidade_id->setSize('100%');
        $detail_regime_trabalho_id->setSize('100%');
        
        $detail_ato_id->setMinLength(1);
        $detail_especialidade_id->setMinLength(2);

        
        
        $this->detail_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->detail_list->setId('Nomeados_list');
        $this->detail_list->generateHiddenFields();
        $this->detail_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";
        
        // items
        $this->detail_list->addColumn( new TDataGridColumn('uniqid', 'Uniqid', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('id', 'Id', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('geres_id', 'Geres Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('ato_id', 'Ato Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('situacaoconcurso_id', 'Situacaoconcurso Id', 'left') )->setVisibility(false);
        //$this->detail_list->addColumn( new TDataGridColumn('obs', 'Obs', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('cargo_id', 'Cargo Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('especialidade_id', 'Especialidade Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('regime_trabalho_id', 'Regime Trabalho Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('detail_obs', 'Observação', 'left') )->setVisibility(false);

        $this->detail_list->addColumn( new TDataGridColumn('detail_geres_desc', 'GERES', 'center', 20) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_ato_desc', 'Ato', 'left', 20) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_situacaoconcurso_desc', 'Situação', 'center', 100) );        
        $this->detail_list->addColumn( new TDataGridColumn('detail_cargo_desc', 'Cargo', 'center', 80) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_especialidade_desc', 'Especialidade', 'center', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_regime_trabalho_desc', 'Regime', 'left', 80) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_reconvocacao', 'Reconvocado', 'left', 20) );

       

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
        
        $this->form->addAction( 'Nomear',  new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save green');
        $this->form->addActionLink('Fechar',  new TAction([$this, 'onClose']), 'fa:times red');
        
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
            
            /** validation sample
            if (empty($data->fieldX))
            {
                throw new Exception('The field fieldX is required');
            }
            **/
            
            $uniqid = !empty($data->detail_uniqid) ? $data->detail_uniqid : uniqid();
            
            $grid_data = [];
            $grid_data['uniqid'] = $uniqid;
            $grid_data['id'] = $data->detail_id;
            $grid_data['geres_id'] = $data->detail_geres_id;
            $grid_data['ato_id'] = $data->detail_ato_id;
            $grid_data['situacaoconcurso_id'] = 2;
            $grid_data['detail_obs'] = $data->detail_obs;
            $grid_data['cargo_id'] = $data->detail_cargo_id;
            $grid_data['especialidade_id'] = $data->detail_especialidade_id;
            $grid_data['regime_trabalho_id'] = $data->detail_regime_trabalho_id;

            TTransaction::open('rh');
                // GERES
                $cGeres = Geres::find($data->detail_geres_id);
                if ($cGeres instanceof Geres)
                {
                    $data->detail_geres_desc = $cGeres->nome;                                
                }  
                //Fim GERES

                // Ato
                $cAto = Ato::find($data->detail_ato_id);
                if ($cAto instanceof Ato)
                {
                    $data->detail_ato_desc = $cAto->ato;                                
                }  
                //Fim Ato

                // Situação
                $cSituacao = SituacaoConcurso::find(2);
                if ($cSituacao instanceof SituacaoConcurso)
                {
                    $data->detail_situacaoconcurso_desc = $cSituacao->nome;                                
                }  
                //Fim Situação

                // Cargo
                $cCargo = cargo::find($data->detail_cargo_id);
                if ($cCargo instanceof cargo)
                {
                    $data->detail_cargo_desc = $cCargo->nome;                                
                }  
                //Fim Cargo

                // Especialidade
                $cEspecialidade = Especialidade::find($data->detail_especialidade_id);
                if ($cEspecialidade instanceof Especialidade)
                {
                    $data->detail_especialidade_desc = $cEspecialidade->nome;                                
                }  
                //Fim Especialidade

                // Regime
                $cRegime = Regime::find($data->detail_regime_trabalho_id);
                if ($cRegime instanceof Regime)
                {
                    $data->detail_regime_trabalho_desc = $cRegime->nome;                                
                }  
                //Fim Regime

            TTransaction::close();

            $grid_data['detail_geres_desc'] = $data->detail_geres_desc;            
            $grid_data['detail_ato_desc'] =$data->detail_ato_desc;
            $grid_data['detail_situacaoconcurso_desc'] = $data->detail_situacaoconcurso_desc;
            $grid_data['detail_cargo_desc'] = $data->detail_cargo_desc;
            $grid_data['detail_especialidade_desc'] = $data->detail_especialidade_desc;
            $grid_data['detail_regime_trabalho_desc'] = $data->detail_regime_trabalho_desc;
            $grid_data['detail_reconvocacao'] = $data->detail_reconvocacao;
            
            // insert row dynamically
            $row = $this->detail_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('Nomeados_list', $uniqid, $row);
            
            // clear detail form fields
            $data->detail_uniqid = '';
            $data->detail_id = '';
            $data->detail_geres_id = '';
            $data->detail_ato_id = '';
            $data->detail_situacaoconcurso_id = '';
            $data->detail_obs = '';
            $data->detail_cargo_id = '';
            $data->detail_especialidade_id = '';
            $data->detail_regime_trabalho_id = '';
            $data->detail_reconvocacao = '';
            $detail_geres_desc = '';
            $detail_ato_desc = '';
            $detail_situacaoconcurso_desc = '';
            $detail_cargo_desc = '';
            $detail_especialidade_desc = '';
            $detail_regime_trabalho_desc = '';
            
            
            // send data, do not fire change/exit events
            TForm::sendData( 'form_Aprovados', $data, false, false );
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
        $data->detail_geres_id = $param['geres_id'];
        $data->detail_ato_id = $param['ato_id'];
        $data->detail_situacaoconcurso_id = 2;
        //$data->detail_obs = $param['detail_obs'];
        $data->detail_cargo_id = $param['cargo_id'];
        $data->detail_especialidade_id = $param['especialidade_id'];
        
        if (!empty($param['detail_obs']))
        {
            $data->detail_obs = $param['detail_obs'];
        }
        
        if (!empty($param['regime_trabalho_id']))
        {
            $data->detail_regime_trabalho_id = $param['regime_trabalho_id'];
        }

        if (!empty($param['reconvocado']))
        {
            $data->detail_reconvocacao = $param['reconvocado'];
        }

        // send data, do not fire change/exit events
        TForm::sendData( 'form_Aprovados', $data, false, false );
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
        $data->detail_geres_id = '';
        $data->detail_ato_id = '';
        $data->detail_situacaoconcurso_id = '';
        $data->detail_obs = '';
        $data->detail_cargo_id = '';
        $data->detail_especialidade_id = '';
        $data->detail_regime_trabalho_id = '';
        $data->detail_reconvocacao = '';
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_Aprovados', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('Nomeados_list', $param['uniqid']);
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
                
                $object = new Aprovados($key);
                $items  = Nomeados::where('aprovados_id', '=', $key)->load();
                
                foreach( $items as $item )
                {
                    
                    // GERES
                    $cGeres = Geres::find($item->geres_id);
                    if ($cGeres instanceof Geres)
                    {
                        $item->detail_geres_desc = $cGeres->nome;                                
                    }  
                    //Fim GERES

                    // Ato
                    $cAto = Ato::find($item->ato_id);
                    if ($cAto instanceof Ato)
                    {
                        $item->detail_ato_desc = $cAto->ato;                                
                    }  
                    //Fim Ato

                    // Situação
                    $cSituacao = SituacaoConcurso::find($item->situacaoconcurso_id);
                    if ($cSituacao instanceof SituacaoConcurso)
                    {
                        $item->detail_situacaoconcurso_desc = $cSituacao->nome;                                
                    }  
                    //Fim Situação

                    // Cargo
                    $cCargo = cargo::find($item->cargo_id);
                    if ($cCargo instanceof cargo)
                    {
                        $item->detail_cargo_desc = $cCargo->nome;                                
                    }  
                    //Fim Cargo

                    // Especialidade
                    $cEspecialidade = Especialidade::find($item->especialidade_id);
                    if ($cEspecialidade instanceof Especialidade)
                    {
                        $item->detail_especialidade_desc = $cEspecialidade->nome;                                
                    }  
                    //Fim Especialidade

                    // Regime
                    $cRegime = Regime::find($item->regime_trabalho_id);
                    if ($cRegime instanceof Regime)
                    {
                        $item->detail_regime_trabalho_desc = $cRegime->nome;                                
                    }  
                    //Fim Regime

                    // Reconvocado
                    $cReconvoc = Nomeados::find($item->id);
                    if ($cReconvoc instanceof Nomeados)
                    {
                        $item->detail_reconvocacao = $cReconvoc->reconvocado;
                        $item->detail_obs = $cReconvoc->obs;
                    }  
                    //Fim Reconvocado

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
            
            $master = new Aprovados;
            $master->fromArray( (array) $data);
            $master->store();
            
            Nomeados::where('aprovados_id', '=', $master->id)->delete();
            
            if( $param['Nomeados_list_geres_id'] )
            {
                foreach( $param['Nomeados_list_geres_id'] as $key => $item_id )
                {
                    $detail = new Nomeados;
                    $detail->geres_id  = $param['Nomeados_list_geres_id'][$key];
                    $detail->ato_id  = $param['Nomeados_list_ato_id'][$key];
                    $detail->situacaoconcurso_id  = $param['Nomeados_list_situacaoconcurso_id'][$key];
                    //$detail->obs  = $param['Nomeados_list_obs'][$key];
                    $detail->cargo_id  = $param['Nomeados_list_cargo_id'][$key];
                    $detail->especialidade_id  = $param['Nomeados_list_especialidade_id'][$key];
                    $detail->regime_trabalho_id  = $param['Nomeados_list_regime_trabalho_id'][$key];
                    $detail->reconvocado  = $param['Nomeados_list_detail_reconvocacao'][$key];

                    if (isset( $param['Nomeados_list_detail_obs'][$key]))
                    {
                        $detail->obs  = $param['Nomeados_list_detail_obs'][$key];
                    }

                    $userid = TSession::getValue('userid');
                    $detail->system_user_id_created = $userid;

                    $detail->aprovados_id = $master->id;
                    $detail->store();
                }
            }
            TTransaction::close(); // close the transaction
            
            TForm::sendData('form_Aprovados', (object) ['id' => $master->id]);
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
