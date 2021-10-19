<?php
/**
 * EstatutarioForm Master/Detail
 * @author  <your name here>
 */
class CessaoDeForm extends TWindow
{
    protected $form; // form
    protected $detail_list;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setSize(0.9, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Cessao_De');
        $this->form->setFormTitle('Cedido De Outros Orgãos');
        
        // master fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');

       
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');

        //critério para listar apenas as unidades setados para o usuário logado
        TTransaction::open('permission');
            $user = TSession::getValue('userid');
            $id_logado = $user;

            $idServidor = TSession::getValue('id');
        TTransaction::close();
        //fim de pesquisa de id do usuário logado

        $criteria_ativo = TCriteria::create( ['ativo' => 'sim'] );

        // detail fields
        $detail_uniqid = new THidden('detail_uniqid');
        $detail_id = new THidden('detail_id');

        $detail_vinculo_id = new TDBCombo('detail_vinculo_id', 'rh', 'vinculo', 'id', 'nome', null, $criteria_ativo, TRUE);
        $detail_simbolo_id = new TDBCombo('detail_simbolo_id', 'rh', 'simbolo', 'id', 'nome', null, $criteria_ativo, TRUE);
        $detail_cargo_id = new TDBCombo('detail_cargo_id', 'rh', 'Cargo', 'id', 'nome', null, $criteria_ativo, TRUE);
        $detail_funcao_id = new TDBCombo('detail_funcao_id', 'rh', 'Funcao', 'id', 'nome', null, $criteria_ativo, TRUE);

        $detail_especialidade_id = new TCombo('detail_especialidade_id'); //ok 
        $detail_especialidadefolha_id = new TDBUniqueSearch('detail_especialidadefolha_id', 'rh', 'EspecialidadeFolha', 'id', 'nome', null, TCriteria::create( ['ativo' => 'sim'] )); //ok

        $detail_regime_trabalho_id = new TDBCombo('detail_regime_trabalho_id', 'rh', 'Regime', 'id', 'nome', null, $criteria_ativo, TRUE);
        $detail_unidade_id = new TDBUniqueSearch('detail_unidade_id', 'gratifica', 'ViewUnidadeUsuario', 'id', 'nome', null, TCriteria::create( ['system_user_id' => $id_logado] )); //ok
        
        //Listar apenas as especialiades relacionadas com o cargo selecionado
        $detail_cargo_id->setChangeAction(new TAction(array($this, 'onListaEspecialidade')));

        $detail_matricula = new TEntry('detail_matricula');
        $detail_conselho_desc = new TEntry('detail_conselho_desc');
        $detail_conselho_num = new TEntry('detail_conselho_num');
        $detail_ativo = new TCombo('detail_ativo');
        $detail_dt_admissao = new TDate('detail_dt_admissao');
        $detail_anotacao = new TText('detail_anotacao');
        //$detail_dt_doe = new TDate('detail_dt_doe');
        //$detail_dt_limite = new TDate('detail_dt_limite');
        $detail_instrumento_legal = new TEntry('detail_instrumento_legal');

        $detail_vinculo = new TEntry('detail_vinculo');
        $detail_simbolo = new TEntry('detail_simbolo');
        $detail_cargo = new TEntry('detail_cargo');
        $detail_funcao = new TEntry('detail_funcao');
        $detail_especialidade = new TEntry('detail_especialidade');
        $detail_especialidadefolha = new TEntry('detail_especialidadefolha');
        $detail_unidade = new TEntry('detail_unidade');
        $detail_regime_trabalho = new TEntry('detail_regime_trabalho');
        $detail_situacao = new TEntry('detail_situacao');

        //eXCLUSIVO PARA CEDIDOS DE OUTROS ÓRGÃOS
        $detail_matricula_origem = new TEntry('detail_matricula_origem');
        $detail_orgao_cedente_destino_id = new TEntry('detail_orgao_cedente_destino_id');
        $detail_orgao_cedente_destino_id->forceUpperCase();
        $detail_forma_cessao = new TEntry('detail_forma_cessao');
        $detail_forma_cessao->forceUpperCase();
        $detail_tipo_cessao = new TCombo('detail_tipo_cessao');
        $detail_tipo_cessao->addItems ([
            'COM ÔNUS PARA A ORIGEM' => 'COM ÔNUS PARA A ORIGEM',
            'SEM ÔNUS PARA A ORIGEM' => 'SEM ÔNUS PARA A ORIGEM',
            ]);

        //Adicionando o parâmetro ativo para todos os registros
              
        $detail_situacao->setValue('EFETIVO EXERCÍCIO');
       

        $detail_ativo->addItems ([
            'SIM' => 'SIM',
            'NÃO' => 'NÃO'
            ]);

        

        //Campos que ficarão bloqueados
        $id->setEditable(FALSE);
        $nome->setEditable(FALSE);
        $cpf->setEditable(FALSE);
        
        $cpf->setMask('999.999.999-99', true);
        $detail_dt_admissao->setMask('dd/mm/yyyy');    
        //$detail_dt_doe->setMask('dd/mm/yyyy');    
       

        //Parametros
        $id->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');

        $detail_uniqid->setSize('100%');
        $detail_id->setSize('100%');
        $detail_vinculo_id->setSize('100%');
        $detail_simbolo_id->setSize('100%');
        $detail_cargo_id->setSize('100%');
        $detail_funcao_id->setSize('100%');
        $detail_especialidade_id->setSize('100%');
        $detail_especialidadefolha_id->setSize('100%');
        $detail_unidade_id->setSize('100%');
        $detail_regime_trabalho_id->setSize('100%');
        $detail_matricula->setSize('100%');
        $detail_conselho_desc->setSize('100%');
        $detail_conselho_num->setSize('100%');
        $detail_ativo->setSize('100%');
        $detail_dt_admissao->setSize('100%');
        $detail_anotacao->setSize('100%');
        //$detail_dt_doe->setSize('100%');
        //$detail_dt_limite->setSize('100%');
        $detail_instrumento_legal->setSize('100%');

        $detail_vinculo->setSize('100%');
        $detail_simbolo->setSize('100%');
        $detail_cargo->setSize('100%');
        $detail_funcao->setSize('100%');
        $detail_especialidade->setSize('100%');
        $detail_especialidadefolha->setSize('100%');
        $detail_unidade->setSize('100%');
        $detail_regime_trabalho->setSize('100%');

        $detail_vinculo_id->setEditable(FALSE);
        $detail_vinculo_id->setValue('1');

        //CONFIGURAÇÕES DOS CAMPOS EXCLUSIVOS DOS CEDIDOS DE FORA
        $detail_matricula_origem->setSize('100%');
        $detail_tipo_cessao->setSize('100%');
        $detail_orgao_cedente_destino_id->setSize('100%');
        $detail_forma_cessao->setSize('100%');
        
        //adiciona campos ao formulário - Mestre
        $row = $this->form->addFields(  [ new TLabel('Id'), $id ],
                                        [ new TLabel('Nome'), $nome ],
                                        [ new TLabel('CPF'), $cpf ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-8', 'col-sm-2'];
        
        // detail fields
        $this->form->addContent( ['<h4>Vínculos</h4><hr>'] );
        $this->form->addFields( [$detail_uniqid] );
        $this->form->addFields( [$detail_id] );

        //adiciona campos ao formulário - Detalhe do vínculo
        $row = $this->form->addFields(  [ new TLabel('Vinculo'), $detail_vinculo_id ],
                                        [ new TLabel('Matricula'), $detail_matricula ],
                                        [ new TLabel('Simbolo'), $detail_simbolo_id ],
                                        [ new TLabel('Nível'), $detail_cargo_id ],
                                        [ new TLabel('Início de Exercício'), $detail_dt_admissao ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-4', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Função'), $detail_funcao_id ],
                                        [ new TLabel('Cargo'), $detail_especialidade_id ],
                                        [ new TLabel('Cargo Folha'), $detail_especialidadefolha_id ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Unidade'), $detail_unidade_id ],
                                        [ new TLabel('Regime Trabalho'), $detail_regime_trabalho_id ]
                                        
                                        );
        $row->layout = ['col-sm-8', 'col-sm-4'];
        
        $row = $this->form->addFields(  [ new TLabel('Conselho de Categoria'), $detail_conselho_desc ],
                                        [ new TLabel('Número do Conselho'), $detail_conselho_num ],
                                        [ new TLabel('Instrumento Legal'), $detail_instrumento_legal ],                 
                                        [ new TLabel('Ativo'), $detail_ativo ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-6', 'col-sm-2'];

        $row = $this->form->addFields(  [ new TLabel('Matrícula de Origem'), $detail_matricula_origem ],
                                        [ new TLabel('Órgão de Origem'), $detail_orgao_cedente_destino_id ],
                                        );
        $row->layout = ['col-sm-2', 'col-sm-8'];

        $row = $this->form->addFields(  [ new TLabel('Tipo de Cessão'), $detail_tipo_cessao ],
                                        [ new TLabel('Forma de Cessão'), $detail_forma_cessao ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-6'];
               
        $row = $this->form->addFields(  [ new TLabel('Anotação'), $detail_anotacao ]
                                        );
        $row->layout = ['col-sm-12'];
        
        $add = TButton::create('add', [$this, 'onDetailAdd'], 'Adicionar Vínculo', 'fa:plus-circle green');
        $add->getAction()->setParameter('static','1');

        $row = $this->form->addFields(  [ $add ]
                                        );
        $row->layout = ['col-sm-6'];
        //Fim do form detalhe
        
        $this->detail_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->detail_list->setId('ServidorVinculo_list');
        $this->detail_list->generateHiddenFields();
        $this->detail_list->style = "min-width: 700px; width:100%;margin-bottom: 10px";
        
        // items
        $this->detail_list->addColumn( new TDataGridColumn('uniqid', 'Uniqid', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('id', 'Id', 'center') )->setVisibility(false);

        $this->detail_list->addColumn( new TDataGridColumn('vinculo', 'Vinculo', 'left') )->setVisibility(false);

        $this->detail_list->addColumn( new TDataGridColumn('matricula', 'Matricula', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('dt_admissao', 'Início de Exercício', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('cargo', 'Nível', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('especialidade', 'Cargo', 'left', 100) );

        $this->detail_list->addColumn( new TDataGridColumn('matricula_origem', 'Matricula Origem', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('tipo_cessao', 'Tipo Cessão', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('orgao_cedente_destino_id', 'Orgão Origem', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('forma_cessao', 'Forma de Cessão', 'left') )->setVisibility(false);

        $this->detail_list->addColumn( new TDataGridColumn('funcao', 'Função', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('unidade', 'Unidade', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('regime_trabalho', 'Regime Trabalho', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('conselho_desc', 'Conselho', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('conselho_num', 'N. Conselho', 'left', 100) );
        
        $this->detail_list->addColumn( new TDataGridColumn('simbolo', 'Simbolo', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('ativo', 'Ativo', 'left', 100) );

        $this->detail_list->addColumn( new TDataGridColumn('anotacao', 'Anotacao', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('dt_doe', 'Data Publicação', 'left') )->setVisibility(false);
        //$this->detail_list->addColumn( new TDataGridColumn('dt_limite', 'Dt Limite', 'left', 50) );
        $this->detail_list->addColumn( new TDataGridColumn('instrumento_legal', 'Instrumento Legal', 'left') )->setVisibility(false);


        $this->detail_list->addColumn( new TDataGridColumn('vinculo_id', 'Vinculo Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('simbolo_id', 'Simbolo Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('cargo_id', 'Cargo Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('funcao_id', 'Funcao Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('especialidade_id', 'Especialidade Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('especialidadefolha_id', 'Especialidade Folha Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('unidade_id', 'Unidade Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('regime_trabalho_id', 'Regime Trabalho Id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('situacao', 'Situação', 'left') )->setVisibility(false);

        // detail actions
        $action1 = new TDataGridAction([$this, 'onDetailEdit'] );
        $action1->setFields( ['uniqid', '*'] );
        
        $action2 = new TDataGridAction([$this, 'onDetailDelete']);
        $action2->setField('uniqid');
        
        // add the actions to the datagrid
        $this->detail_list->addAction($action1, _t('Edit'), 'fa:edit blue');
        //$this->detail_list->addAction($action2, _t('Delete'), 'far:trash-alt red');
        
        $this->detail_list->createModel();
        
        $panel = new TPanelGroup;
        $panel->add($this->detail_list);
        $panel->getBody()->style = 'overflow-x:auto';
        $this->form->addContent( [$panel] );

        // create the form actions
        $this->form->addHeaderActionLink( _t('Close'),  new TAction(['CessaoDeList', 'onReload']), 'fa:times red' );
        
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
       
        $this->form->addActionLink('Cancelar',  new TAction(['CessaoDeList', 'onReload']),  'fa:times red' );
        
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
            
            
            if (empty($data->detail_matricula))
            {
                throw new Exception('O campo Matrícula é obrigatório');
            }
            
            if (empty($data->detail_cargo_id))
            {
                throw new Exception('O campo Cargo é obrigatório');
            }

            if (empty($data->detail_funcao_id))
            {
                throw new Exception('O campo Função é obrigatório');
            }
            
            if (empty($data->detail_unidade_id))
            {
                throw new Exception('O campo Unidade é obrigatório');
            }

            if (empty($data->detail_simbolo))
            {
                //throw new Exception('O campo Unidade é obrigatório');
            }

            $uniqid = !empty($data->detail_uniqid) ? $data->detail_uniqid : uniqid();
            
            

            $grid_data = [];
            $grid_data['uniqid'] = $uniqid;
            $grid_data['id'] = $data->detail_id;

            $grid_data['matricula'] = $data->detail_matricula;
            $grid_data['conselho_desc'] = $data->detail_conselho_desc;
            $grid_data['conselho_num'] = $data->detail_conselho_num;
            $grid_data['ativo'] = $data->detail_ativo;
            $grid_data['dt_admissao'] = $data->detail_dt_admissao;
            $grid_data['anotacao'] = $data->detail_anotacao;
            //$grid_data['dt_doe'] = $data->detail_dt_doe;
            //$grid_data['dt_limite'] = $data->detail_dt_limite;
            $grid_data['instrumento_legal'] = $data->detail_instrumento_legal;

            $grid_data['vinculo_id'] = $data->detail_vinculo_id;
            $grid_data['simbolo_id'] = $data->detail_simbolo_id;
            $grid_data['cargo_id'] = $data->detail_cargo_id;
            $grid_data['funcao_id'] = $data->detail_funcao_id;
            $grid_data['especialidade_id'] = $data->detail_especialidade_id;
            $grid_data['especialidadefolha_id'] = $data->detail_especialidadefolha_id;
            $grid_data['unidade_id'] = $data->detail_unidade_id;
            $grid_data['regime_trabalho_id'] = $data->detail_regime_trabalho_id;
            $grid_data['situacao'] = 'EFETIVO EXERCÍCIO';
            
            $grid_data['matricula_origem'] = $data->detail_matricula_origem;
            $grid_data['tipo_cessao'] = $data->detail_tipo_cessao;
            $grid_data['orgao_cedente_destino_id'] = $data->detail_orgao_cedente_destino_id;
            $grid_data['forma_cessao'] = $data->detail_forma_cessao;

            TTransaction::open('gratifica');
            // Vinculo
            $cVinculo = Vinculo::find($data->detail_vinculo_id);
            if ($cVinculo instanceof Vinculo)
            {
                $data->detail_vinculo = $cVinculo->nome;                                
            }  
            //Fim vínculo

            // Simbolo
            $cSimbolo = Simbolo::find($data->detail_simbolo_id);
            if ($cSimbolo instanceof Simbolo)
            {
                $data->detail_simbolo = $cSimbolo->nome;                                
            }   

            if (empty($data->detail_simbolo))
            {
                $data->detail_simbolo = 0;
            }

            //Fim Simbolo

            // Funcao
            $cFuncao = Funcao::find($data->detail_funcao_id);
            if ($cFuncao instanceof Funcao)
            {
                $data->detail_funcao = $cFuncao->nome;                                
            }   
            //Fim Funcao
            
            // Cargo
            $cCargo = Cargo::find($data->detail_cargo_id);
            if ($cCargo instanceof Cargo)
            {
                $data->detail_cargo = $cCargo->nome;                                
            }   
            //Fim Simbolo

            // Especialidade
            $cEspecialidade = Especialidade::find($data->detail_especialidade_id);
            if ($cEspecialidade instanceof Especialidade)
            {
                $data->detail_especialidade = $cEspecialidade->nome;                                
            }   
            //Fim Simbolo

            // Unidade
            $cUnidade = Pessoa::find($data->detail_unidade_id);
            if ($cUnidade instanceof Pessoa)
            {
                $data->detail_unidade = $cUnidade->nome;                                
            }

            // Regime
            $cRegime = Regime::find($data->detail_regime_trabalho_id);
            if ($cRegime instanceof Regime)
            {
                $data->detail_regime_trabalho = $cRegime->nome;                                
            }
            //Fim Regime

            TTransaction::close();


            $grid_data['vinculo'] = $data->detail_vinculo;
            $grid_data['simbolo'] = $data->detail_simbolo;
            $grid_data['cargo'] = $data->detail_cargo;
            $grid_data['funcao'] = $data->detail_funcao;
            $grid_data['especialidade'] = $data->detail_especialidade;
            $grid_data['unidade'] = $data->detail_unidade;
            $grid_data['regime_trabalho'] = $data->detail_regime_trabalho;


            // insert row dynamically
            $row = $this->detail_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('ServidorVinculo_list', $uniqid, $row);
            
            // clear detail form fields
            $data->detail_uniqid = '';
            $data->detail_id = '';
            $data->detail_vinculo_id = '1';
            $data->detail_simbolo_id = '';
            $data->detail_cargo_id = '';
            $data->detail_funcao_id = '';
            $data->detail_especialidade_id = '';
            $data->detail_especialidadefolha_id = '';
            $data->detail_unidade_id = '';
            $data->detail_regime_trabalho_id = '';
            $data->detail_matricula = '';
            $data->detail_conselho_desc = '';
            $data->detail_conselho_num = '';
            $data->detail_ativo = '';
            $data->detail_dt_admissao = '';
            $data->detail_anotacao = '';
            //$data->detail_dt_doe = '';
            //$data->detail_dt_limite = '';
            $data->detail_instrumento_legal = '';
            
            $data->detail_matricula_origem = '';
            $data->detail_tipo_cessao = '';
            $data->detail_orgao_cedente_destino_id = '';
            $data->detail_forma_cessao = '';

            // send data, do not fire change/exit events
            TForm::sendData( 'form_Cessao_De', $data, false, false );
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

        $data->detail_vinculo_id = $param['vinculo_id'];

        if (!empty($param['simbolo_id']))
        {
            $data->detail_simbolo_id = $param['simbolo_id'];
        }

        if (!empty($param['especialidade_id']))
        {
            $data->detail_especialidade_id = $param['especialidade_id'];
        }

        if (!empty($param['especialidadefolha_id']))
        {
            $data->detail_especialidadefolha_id = $param['especialidadefolha_id'];
        }

        if (!empty($param['conselho_desc']))
        {
            $data->detail_conselho_desc = $param['conselho_desc'];
        }

        if (!empty($param['conselho_num']))
        {
            $data->detail_conselho_num = $param['conselho_num'];
        }

        if (!empty($param['dt_admissao']))
        {
            $data->detail_dt_admissao = $param['dt_admissao'];
        }

        if (!empty($param['anotacao']))
        {
            $data->detail_anotacao = $param['anotacao'];
        }

        if (!empty($param['instrumento_legal']))
        {
            $data->detail_instrumento_legal = $param['instrumento_legal'];
        }

        if (!empty($param['regime_trabalho_id']))
        {
            $data->detail_regime_trabalho_id = $param['regime_trabalho_id'];
        }

        $data->detail_cargo_id = $param['cargo_id'];
        $data->detail_funcao_id = $param['funcao_id'];
        $data->detail_unidade_id = $param['unidade_id'];
        $data->detail_matricula = $param['matricula'];
        $data->detail_ativo = $param['ativo'];

        if (!empty($param['matricula_origem']))
        {
            $data->detail_matricula_origem = $param['matricula_origem'];
        }

        if (!empty($param['tipo_cessao']))
        {
            $data->detail_tipo_cessao = $param['tipo_cessao'];
        }

        if (!empty($param['orgao_cedente_destino_id']))
        {
            $data->detail_orgao_cedente_destino_id = $param['orgao_cedente_destino_id'];
        }

        if (!empty($param['forma_cessao']))
        {
            $data->detail_forma_cessao = $param['forma_cessao'];
        }
        
        //$data->detail_dt_limite = $param['dt_limite'];

        //Carrega a lista de especialidades de acordo com o cargo do vínculo
        $criteria = TCriteria::create( ['cargo_id' => $param['cargo_id'] ] );
                
            //detail_especialidade_id', 'gratifica', 'Especialidade', 'id', 'nome'); //ok 
            // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
            TDBCombo::reloadFromModel('form_Cessao_De', 'detail_especialidade_id', 'rh', 'ViewEspecialidadeCargo', 'id', 'nome', null, $criteria, TRUE);
            
            if (!empty($param['especialidade_id']))
            {
                $data->detail_especialidade_id = $param['especialidade_id'];
            }

        // send data, do not fire change/exit events
        TForm::sendData( 'form_Cessao_De', $data, false, false );
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
        $data->detail_vinculo_id = '1';
        $data->detail_simbolo_id = '';
        $data->detail_cargo_id = '';
        $data->detail_funcao_id = '';
        $data->detail_especialidade_id = '';
        $data->detail_especialidadefolha_id = '';
        $data->detail_unidade_id = '';
        $data->detail_regime_trabalho_id = '';
        $data->detail_matricula = '';
        $data->detail_conselho_desc = '';
        $data->detail_conselho_num = '';
        $data->detail_ativo = 'SIM';
        $data->detail_dt_admissao = '';
        $data->detail_anotacao = '';
        //$data->detail_dt_doe = '';
        //$data->detail_dt_limite = '';
        $data->detail_instrumento_legal = '';

        $data->detail_matricula_origem = '';
        $data->detail_tipo_cessao = '';
        $data->detail_orgao_cedente_destino_id = '';
        $data->detail_forma_cessao = '';
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_Cessao_De', $data, false, false );
        
        // remove row
        TDataGrid::removeRowById('ServidorVinculo_list', $param['uniqid']);
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
                $object = new Servidor($key);
                $items  = ServidorVinculo::where('servidor_id', '=', $key)
                                            ->where('vinculo_id', '=', '1')
                                            ->load();

                foreach( $items as $item )
                {
                    // Vinculo
                    $cVinculo = Vinculo::find($item->vinculo_id);
                    if ($cVinculo instanceof Vinculo)
                    {
                        $item->vinculo = $cVinculo->nome;                                
                    }
                    //Fim vínculo
                    
                    // Simbolo
                    $cSimbolo = Simbolo::find($item->simbolo_id);
                    if ($cSimbolo instanceof Simbolo)
                    {
                        $item->simbolo = $cSimbolo->nome;                                
                    }  
                    //Fim Simbolo

                    // Funcao
                    $cFuncao = Funcao::find($item->funcao_id);
                    if ($cFuncao instanceof Funcao)
                    {
                        $item->funcao = $cFuncao->nome;                                
                    }   
                    //Fim Funcao
                    
                    // Cargo
                    $cCargo = Cargo::find($item->cargo_id);
                    if ($cCargo instanceof Cargo)
                    {
                        $item->cargo = $cCargo->nome;                                
                    }   
                    //Fim Simbolo

                    // Especialidade
                    $cEspecialidade = Especialidade::find($item->especialidade_id);
                    if ($cEspecialidade instanceof Especialidade)
                    {
                        $item->especialidade = $cEspecialidade->nome;                                
                    }   
                    //Fim Simbolo

                    // Especialidade Folha
                    $cEspecialidadeFolha = EspecialidadeFolha::find($item->especialidadefolha_id);
                    if ($cEspecialidadeFolha instanceof Especialidade)
                    {
                        $item->especialidadefolha_id = $cEspecialidadeFolha->nome;                                
                    }   
                    //Fim Especialidade Folha

                    // Unidade
                    $cUnidade = Pessoa::find($item->unidade_id);
                    if ($cUnidade instanceof Pessoa)
                    {
                        $item->unidade = $cUnidade->nome;                                
                    }

                    // Regime
                    $cRegime = Regime::find($item->regime_trabalho_id);
                    if ($cRegime instanceof Regime)
                    {
                        $item->regime_trabalho = $cRegime->nome;                                
                    }
                    //Fim Regime
                    $item->uniqid = uniqid();

                    $item->dt_admissao = $item->dt_admissao;
                    $item->ativo = $item->ativo;
                   
                    $row = $this->detail_list->addItem( $item );
                    $row->id = $item->uniqid;
                    
                }//Fim de vínculos

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
    function onSave($param)
    {
        try
        {
            // open a transaction with database
            TTransaction::open('rh');
            
            $data = $this->form->getData();
            $this->form->validate();
            
            $master = new Servidor;
            $master->fromArray( (array) $data);
            //$master->store();
            
            //ServidorVinculo::where('servidor_id', '=', $master->id)->delete();
            
            if( isset($param['ServidorVinculo_list_vinculo_id'] ))
            {
                foreach( $param['ServidorVinculo_list_vinculo_id'] as $key => $item_id )
                {
                    $detail = new ServidorVinculo;

                    $sv_id = $param['ServidorVinculo_list_id'][$key];
                    ServidorVinculo::where('servidor_id', '=', $master->id)
                                    ->where('id', '=', $sv_id)
                                    ->delete();

                    $detail->vinculo_id  = $param['ServidorVinculo_list_vinculo_id'][$key];
                    $detail->simbolo_id  = $param['ServidorVinculo_list_simbolo_id'][$key];
                    $detail->cargo_id  = $param['ServidorVinculo_list_cargo_id'][$key];
                    $detail->funcao_id  = $param['ServidorVinculo_list_funcao_id'][$key];
                    $detail->especialidade_id  = $param['ServidorVinculo_list_especialidade_id'][$key];
                    $detail->especialidadefolha_id  = $param['ServidorVinculo_list_especialidadefolha_id'][$key];
                    $detail->unidade_id  = $param['ServidorVinculo_list_unidade_id'][$key];
                    $detail->regime_trabalho_id  = $param['ServidorVinculo_list_regime_trabalho_id'][$key];
                    $detail->matricula  = $param['ServidorVinculo_list_matricula'][$key];
                    $detail->conselho_desc  = $param['ServidorVinculo_list_conselho_desc'][$key];
                    $detail->conselho_num  = $param['ServidorVinculo_list_conselho_num'][$key];
                    $detail->ativo  = $param['ServidorVinculo_list_ativo'][$key];
                    $detail->dt_admissao  = $param['ServidorVinculo_list_dt_admissao'][$key];
                    $detail->anotacao  = $param['ServidorVinculo_list_anotacao'][$key];
                    //$detail->dt_doe  = $param['ServidorVinculo_list_dt_doe'][$key];
                    //$detail->dt_limite  = $param['ServidorVinculo_list_dt_limite'][$key];
                    $detail->instrumento_legal  = $param['ServidorVinculo_list_instrumento_legal'][$key];
                    $detail->servidor_id = $master->id;
                    $detail->situacao = 'EFETIVO EXERCÍCIO';

                    $detail->matricula_origem  = $param['ServidorVinculo_list_matricula_origem'][$key];
                    $detail->tipo_cessao  = $param['ServidorVinculo_list_tipo_cessao'][$key];
                    $detail->orgao_cedente_destino_id  = $param['ServidorVinculo_list_orgao_cedente_destino_id'][$key];
                    $detail->forma_cessao  = $param['ServidorVinculo_list_forma_cessao'][$key];
                    $detail->store();
                }
            }
            TTransaction::close(); // close the transaction
            
            TForm::sendData('form_Cessao_De', (object) ['id' => $master->id]);
            
            new TMessage('info', 'Vínculo Cadastrado com Sucesso');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }

    public static function onListaEspecialidade($param)
    {
        try
        {
            TTransaction::open('rh');
            if (!empty($param['detail_cargo_id']))
            {
                $criteria = TCriteria::create( ['cargo_id' => $param['detail_cargo_id'] ] );
                
                //detail_especialidade_id', 'gratifica', 'Especialidade', 'id', 'nome'); //ok 
                // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
                TDBCombo::reloadFromModel('form_Cessao_De', 'detail_especialidade_id', 'rh', 'ViewEspecialidadeCargo', 'id', 'nome', null, $criteria, TRUE);
            }
            else
            {
                TDBCombo::clearField('form_Cessao_De', 'detail_especialidade_id');
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    // método para fechar janela
    public static function onClose()
    {
       parent::closeWindow();
    }
}
