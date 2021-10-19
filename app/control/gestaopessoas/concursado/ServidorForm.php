<?php
/**
 * ServidorForm3 Master/Detail
 * @author  <your name here>
 */
class ServidorForm extends TWindow
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
        $this->form = new BootstrapFormBuilder('form_Servidor');
        $this->form->setFormTitle('<h3><b>Cadastrar Pessoa</b></h3>');
        
        // master fields
        $id = new TEntry('id');
        
        $nome = new TEntry('nome');
        $nome_social = new TEntry('nome_social');
        $dt_nascimento = new TDate('dt_nascimento');        
        $cpf = new TEntry('cpf');
        $tipo_doc = new TCombo('tipo_doc');        
        $doc = new TEntry('doc');
        $emissor = new TEntry('emissor');

        $nome->forceUpperCase();
        $nome_social->forceUpperCase();
        $emissor->forceUpperCase();

        $filter = new TCriteria;
        $filter->add(new TFilter('id', '<', '0'));
        $estado_doc = new TDBCombo('estado_doc', 'rh', 'Estado', 'id', 'nome');
        $estado_doc->enableSearch();

        $estado_civil = new TCombo('estado_civil');
        $sexo = new TCombo('sexo');
                
        $tipo_doc->addItems ([
            1 => 'RG',
            2 => 'CNH'
            ]);
       
        $sexo->addItems([
            'Masculino' => 'Masculino',
            'Feminino' => 'Feminino',
            'Homossexual' => 'Homossexual',
            'Travesti' => 'Travesti',
            'Mulher Transexual' => 'Mulher Transexual',
            'Homem Transexual' => 'Homem Transexual'
            ]);
        
        $estado_civil->addItems ([
            'Solteiro(a)' => 'Solteiro(a)',
            'Casado(a)' => 'Casado(a)',
            'Divorciado(a)' => 'Divorciado(a)',
            'Viúvo(a)' => 'Viúvo(a)',
            'Separado(a)' => 'Separado(a)'
            ]);

        //Todo cadastro novo entra como ativo= sim
        $ativo = new TEntry('ativo');
        
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

        $detail_matricula = new TEntry('detail_matricula'); //ok
        $detail_conselho_desc = new TEntry('detail_conselho_desc');
        $detail_regime_trabalho_id = new TDBCombo('detail_regime_trabalho_id', 'rh', 'Regime', 'id', 'nome', null, $criteria_ativo, TRUE);
        $detail_conselho_num = new TEntry('detail_conselho_num');
        $detail_ativo = new TCombo('detail_ativo');
        $detail_dt_admissao = new TDate('detail_dt_admissao');

        $detail_unidade_id = new TDBCombo('detail_unidade_id', 'rh', 'ViewUnidadeUsuario', 'id', 'nome', null, TCriteria::create( ['system_user_id' => $id_logado] )); //ok
        
        //Listar apenas as especialiades relacionadas com o cargo selecionado
        $detail_cargo_id->setChangeAction(new TAction(array($this, 'onListaEspecialidade')));

        //Rótulos que serão exibidos no datagrid
        $detail_vinculo = new TEntry('detail_vinculo');
        $detail_simbolo = new TEntry('detail_simbolo');
        $detail_cargo = new TEntry('detail_cargo');
        $detail_funcao = new TEntry('detail_funcao');
        $detail_especialidade = new TEntry('detail_especialidade');
        $detail_unidade = new TEntry('detail_unidade');
        $detail_regime_trabalho = new TEntry('detail_regime_trabalho');
        
        $detail_uniqid->setSize('100%');
        $detail_id->setSize('100%');
        $detail_vinculo_id->setSize('100%');
        $detail_simbolo_id->setSize('100%');
        $detail_cargo_id->setSize('100%');
        $detail_funcao_id->setSize('100%');
        $detail_especialidade_id->setSize('100%');
        $detail_unidade_id->setSize('100%');
        $detail_matricula->setSize('100%');
        $detail_conselho_desc->setSize('100%');
        
        $detail_regime_trabalho_id->setSize('100%');
        $detail_conselho_num->setSize('100%');
        $detail_ativo->setSize('100%');
        $detail_dt_admissao->setSize('100%');
        
        $detail_ativo->addItems ([
            'SIM' => 'SIM',
            'NÃO' => 'NÃO'
            ]);
        
        $detail_dt_admissao->setMask('dd/mm/yyyy');    
        $detail_dt_admissao->setDatabaseMask('yyyy-mm-dd');    

        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }

        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $nome_social->setSize('100%');
                                
        $cpf->setMask('999.999.999-99', true);
                
        $cpf->addValidation('doc', new TMinLengthValidator, array(14));
        $cpf->addValidation('doc', new TMaxLengthValidator, array(14));

        $dt_nascimento->setMask('dd/mm/yyyy');   
        $dt_nascimento->setDatabaseMask('yyyy-mm-dd');  

        $cpf->setSize('100%');        
        $tipo_doc->setSize('100%');        
        $doc->setSize('100%');
        $emissor->setSize('100%');
        $estado_doc->setSize('100%');

        $nome->setSize('100%');

        $dt_nascimento->setSize('100%');
        $estado_civil->setSize('100%');
        $sexo->setSize('100%');
               
        $nome->addValidation('Nome', new TRequiredValidator);
        //Fim do formulário
        
        // master fields
        //adiciona campos ao formulário
        $row = $this->form->addFields(  [ new TLabel('Id'), $id ],
                                        [ new TLabel('Nome'), $nome ],
                                        //[ new TLabel('Nome Social'), $nome_social ],
                                        [ new TLabel('CPF'), $cpf ],                                        
                                        [ new TLabel('Data de Nascimento'), $dt_nascimento ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-6', 'col-sm-2', 'col-sm-2'];
        
        $row = $this->form->addFields(  [ new TLabel('Documento'), $tipo_doc ],                                        
                                        [ new TLabel('Número Doc'), $doc ],
                                        [ new TLabel('Orgão Emissor'), $emissor ],
                                        [ new TLabel('UF'), $estado_doc ],
                                        [ new TLabel('Estado Civil'), $estado_civil ],
                                        [ new TLabel('Orientação'), $sexo ]
                                     );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2'];
        
        //Adicionando o parâmetro ativo para todos os registros
        $this->form->addFields([new TLabel('')], [$ativo]);        
        $ativo->setValue('sim');
        TQuickForm::hideField('form_Servidor', 'ativo');      
        
        //Endereço
        $this->form->addContent( [ TElement::tag('h5', '<b>Endereço</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
        
        //Atributos
        $cep = new TEntry('cep');
        $logradouro = new TEntry('logradouro');
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');
        $cidade = new TEntry('cidade');
        $estado = new TEntry('estado');

        $cep->setExitAction( new TAction([ $this, 'onExitCEP']) );

        $cep->setSize('100%');
        $logradouro->setSize('100%');
        $numero->setSize('100%');
        $complemento->setSize('100%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $estado->setSize('100%');
        
        $row = $this->form->addFields(  [ new TLabel('CEP'), $cep ],
                                        [ new TLabel('Logradouro'), $logradouro ],
                                        [ new TLabel('Número'), $numero ]
                                     );
        $row->layout = ['col-sm-3', 'col-sm-6', 'col-sm-3'];

        $row = $this->form->addFields(  [ new TLabel('Complemento'), $complemento ],
                                        [ new TLabel('Bairro'), $bairro ],
                                        [ new TLabel('Cidade'), $cidade ],
                                        [ new TLabel('Estado'), $estado ]
                                     );
        $row->layout = ['col-sm-3', 'col-sm-3', 'col-sm-3', 'col-sm-3'];

        
        // INÍCIO DOS CONTATOS
        $this->fieldlist = new TFieldList;
        $this->fieldlist-> width = '88%';
        $this->fieldlist->enableSorting();

        // add field list to the form
        $detail_wrapper = new TElement('div');
        $detail_wrapper->add($this->fieldlist);

        $this->form->addContent( [ TElement::tag('h5', '<b>Contatos</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
        $this->form->addContent( [ $detail_wrapper ] );

        $tipo = new TCombo('list_tipo[]');
        $contato = new TEntry('list_contato[]');
        $responsavel = new TEntry('list_responsavel[]');
        $principal = new TCombo('list_principal[]');
        $obs = new TEntry('list_obs[]');

        $tipo->addItems([
            'email' => 'E-mail',
            'fone_fixo' => 'Telefone Fixo',
            'celular' => 'Celular'
            ]);

        $principal->addItems([
            'sim' => 'Sim',
            'não' => 'Não'
            ]);

        $tipo->setSize('100%');
        $contato->setSize('100%');
        $responsavel->setSize('100%');
        $principal->setSize('100%');
        $obs->setSize('100%');

        $this->fieldlist->addField( '<b>Tipo</b>', $tipo);
        $this->fieldlist->addField( '<b>Contato</b>', $contato);
        $this->fieldlist->addField( '<b>Responsavel</b>', $responsavel);
        $this->fieldlist->addField( '<b>Principal</b>', $principal);
        $this->fieldlist->addField( '<b>Observação</b>', $obs);
        
        $this->form->addField($tipo);
        $this->form->addField($contato);
        $this->form->addField($responsavel);
        $this->form->addField($principal);
        $this->form->addField($obs);

         //início dos detalhes
        $this->form->addContent( [ TElement::tag('h5', '<b>Vínculos do Servidor</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
        //$this->form->addContent( [ $detail_vinculo ] );
        //----------------------------------------------------

        $this->detail_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->detail_list->setId('ServidorVinculo_list');
        $this->detail_list->generateHiddenFields();
        $this->detail_list->style = "min-width: 400px; width:100%;margin-bottom: 10px";
        
        // items
        $this->detail_list->addColumn( new TDataGridColumn('uniqid', 'Uniqid', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('id', 'Id', 'center') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('matricula', 'Matricula', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_dt_admissao', 'Admissão', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_vinculo', 'Vinculo', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('situacao', 'Situação', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_simbolo', 'Simbolo', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_cargo', 'Cargo', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_funcao', 'Função', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_especialidade', 'Especialidade', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('conselho_desc', 'Conselho de Categoria', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('conselho_num', 'Conselho Num', 'left', 100) );        
        $this->detail_list->addColumn( new TDataGridColumn('detail_regime_trabalho', 'Regime Trabalho', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_unidade', 'Unidade', 'left', 100) );
        $this->detail_list->addColumn( new TDataGridColumn('detail_ativo', 'Ativo', 'left', 100) );
        
        $this->detail_list->addColumn( new TDataGridColumn('vinculo_id', 'Vinculo_id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('simbolo_id', 'Simbolo_id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('cargo_id', 'Cargo_id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('funcao_id', 'Funcao_id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('especialidade_id', 'Especialidade_id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('unidade_id', 'Unidade_id', 'left') )->setVisibility(false);
        $this->detail_list->addColumn( new TDataGridColumn('regime_trabalho_id', 'Regime Trabalho Id', 'left') )->setVisibility(false);
        

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

        
       
        /*
        $this->form->addAction( 'Save',  new TAction([$this, 'onSave'], ['static'=>'1']), 'fa:save green');
        $this->form->addAction( 'Clear', new TAction([$this, 'onClear']), 'fa:eraser red');
        */

        // create the form actions
        $this->form->addHeaderActionLink( _t('Close'),  new TAction(array('ServidorList','onReload')),  'fa:times red' );
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'SalvarPessoa'], ['static'=>'1']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Limpar',  new TAction([$this, 'onClear']), 'fa:eraser red');
        $this->form->addActionLink('Cancelar', new TAction(array('ServidorList','onReload')),  'fa:times red' );


        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        parent::add($container);
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
            $grid_data['uniqid'] = $uniqid;
            $grid_data['id'] = $data->detail_id;
            $grid_data['vinculo_id'] = $data->detail_vinculo_id;
            $grid_data['simbolo_id'] = $data->detail_simbolo_id;
            $grid_data['cargo_id'] = $data->detail_cargo_id;
            $grid_data['funcao_id'] = $data->detail_funcao_id;
            $grid_data['especialidade_id'] = $data->detail_especialidade_id;
            $grid_data['unidade_id'] = $data->detail_unidade_id;
            $grid_data['matricula'] = $data->detail_matricula;
            $grid_data['conselho_desc'] = $data->detail_conselho_desc;

            $grid_data['conselho_num'] = $data->detail_conselho_num;
            $grid_data['regime_trabalho_id'] = $data->detail_regime_trabalho_id;
            $grid_data['detail_dt_admissao'] = $data->detail_dt_admissao;
            $grid_data['detail_ativo'] = $data->detail_ativo;

            
            TTransaction::open('rh');
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
            
            //Adicionando os rótulos ao datagrid
            $grid_data['detail_vinculo'] = $data->detail_vinculo;            
            $grid_data['detail_simbolo'] = $data->detail_simbolo;
            $grid_data['detail_funcao'] = $data->detail_funcao;
            $grid_data['detail_cargo'] = $data->detail_cargo;
            $grid_data['detail_especialidade'] = $data->detail_especialidade;
            $grid_data['detail_unidade'] = $data->detail_unidade;
            $grid_data['detail_regime_trabalho'] = $data->detail_regime_trabalho;

            // insert row dynamically
            $row = $this->detail_list->addItem( (object) $grid_data );
            $row->id = $uniqid;
            
            TDataGrid::replaceRowById('ServidorVinculo_list', $uniqid, $row);
            
            // clear detail form fields
            $data->detail_uniqid = '';
            $data->detail_id = '';
            $data->detail_vinculo_id = '';
            $data->detail_simbolo_id = '';
            $data->detail_cargo_id = '';
            $data->detail_funcao_id = '';
            $data->detail_especialidade_id = '';
            $data->detail_unidade_id = '';
            $data->detail_matricula = '';
            $data->detail_conselho_desc = '';

            $data->detail_conselho_num = '';
            $data->detail_regime_trabalho_id = '';
            $data->detail_dt_admissao = '';
            $data->detail_ativo = 'SIM';

            // send data, do not fire change/exit events
            TForm::sendData( 'form_Servidor', $data, false, false );
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
        $data->detail_simbolo_id = $param['simbolo_id'];
        $data->detail_cargo_id = $param['cargo_id'];
        $data->detail_funcao_id = $param['funcao_id'];
        //$data->detail_especialidade_id = $param['especialidade_id'];
        $data->detail_unidade_id = $param['unidade_id'];
        $data->detail_matricula = $param['matricula'];
        $data->detail_conselho_desc = $param['conselho_desc'];

        $data->detail_conselho_num = $param['conselho_num'];
        $data->detail_regime_trabalho_id = $param['regime_trabalho_id'];
        $data->detail_dt_admissao = $param['detail_dt_admissao'];
        $data->detail_ativo = $param['detail_ativo'];

        //Carrega a lista de especialidades de acordo com o cargo do vínculo
        $criteria = TCriteria::create( ['cargo_id' => $param['cargo_id'] ] );
                
            //detail_especialidade_id', 'rh', 'Especialidade', 'id', 'nome'); //ok 
            // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
            TDBCombo::reloadFromModel('form_Servidor', 'detail_especialidade_id', 'rh', 'ViewEspecialidadeCargo', 'id', 'nome', null, $criteria, TRUE);
            $data->detail_especialidade_id = $param['especialidade_id'];

        // send data, do not fire change/exit events
        TForm::sendData( 'form_Servidor', $data, false, false );
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
        $data->detail_vinculo_id = '';
        $data->detail_simbolo_id = '';
        $data->detail_cargo_id = '';
        $data->detail_funcao_id = '';
        $data->detail_especialidade_id = '';
        $data->detail_unidade_id = '';
        $data->detail_matricula = '';
        $data->detail_conselho_desc = '';

        $data->detail_conselho_num = '';
        $data->detail_regime_trabalho_id = '';
        $data->detail_dt_admissao = '';
        $data->detail_ativo = 'SIM';
        
        // send data, do not fire change/exit events
        TForm::sendData( 'form_Servidor', $data, false, false );
        
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
                $this->form->setData($object);
                
                //Início de vínculos
                $items  = ServidorVinculo::where('servidor_id', '=', $key)
                                            ->load();
                
                foreach( $items as $item )
                {                       
                    // Vinculo
                    $cVinculo = Vinculo::find($item->vinculo_id);
                    if ($cVinculo instanceof Vinculo)
                    {
                        $item->detail_vinculo = $cVinculo->nome;                                
                    }  
                    //Fim vínculo                    
                    
                    // Simbolo
                    $cSimbolo = Simbolo::find($item->simbolo_id);
                    if ($cSimbolo instanceof Simbolo)
                    {
                        $item->detail_simbolo = $cSimbolo->nome;                                
                    }   
                    //Fim Simbolo

                    // Funcao
                    $cFuncao = Funcao::find($item->funcao_id);
                    if ($cFuncao instanceof Funcao)
                    {
                        $item->detail_funcao = $cFuncao->nome;                                
                    }   
                    //Fim Funcao
                    
                    // Cargo
                    $cCargo = Cargo::find($item->cargo_id);
                    if ($cCargo instanceof Cargo)
                    {
                        $item->detail_cargo = $cCargo->nome;                                
                    }   
                    //Fim Simbolo

                    // Especialidade
                    $cEspecialidade = Especialidade::find($item->especialidade_id);
                    if ($cEspecialidade instanceof Especialidade)
                    {
                        $item->detail_especialidade = $cEspecialidade->nome;                                
                    }   
                    //Fim Simbolo

                    // Unidade
                    $cUnidade = Pessoa::find($item->unidade_id);
                    if ($cUnidade instanceof Pessoa)
                    {
                        $item->detail_unidade = $cUnidade->nome;                                
                    }

                    // Regime
                    $cRegime = Regime::find($item->regime_trabalho_id);
                    if ($cRegime instanceof Regime)
                    {
                        $item->detail_regime_trabalho = $cRegime->nome;                                
                    }
                    //Fim Regime
                    $item->uniqid = uniqid();

                    $item->detail_dt_admissao = $item->dt_admissao;
                    $item->detail_ativo = $item->ativo;

                    $row = $this->detail_list->addItem( $item );
                    $row->id = $item->uniqid;
                    
                }//Fim de vínculos

                //carrega os contatos do servidor
                $contatos  = Contato::where('cpf', '=', $item->cpf)->load();
                
                if ($contatos)
                {
                    $this->fieldlist->addHeader();
                    foreach($contatos  as $item )
                    {
                        $detail = new stdClass;
                        $detail->list_tipo = $item->tipo;
                        $detail->list_contato = $item->contato;
                        $detail->list_responsavel = $item->responsavel;
                        $detail->list_principal = $item->principal;
                        $detail->list_obs = $item->observacao;
                        $this->fieldlist->addDetail($detail);
                    }                    
                    $this->fieldlist->addCloneAction();
                }
                else
                {
                    $this->onClear($param);
                }
                //Fim de contatos
                
                TTransaction::close();
            }
            else
            {
                $this->onClear($param);
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
    function SalvarPessoa($param)
    {
        try
        {
            // open a transaction with database
            TTransaction::open('rh');
            
            $data = $this->form->getData();
            $this->form->validate();
            
            $master = new Servidor;
            $master->fromArray( (array) $data);
            $master->store();
            
            // delete details
            Contato::where('servidor_id', '=', $master->id)->delete();
            
            if( !empty($param['list_tipo']) AND is_array($param['list_tipo']) )
            {
                foreach( $param['list_tipo'] as $row => $tipo)
                {
                    if (!empty($tipo))
                    {
                        $cont = new Contato;
                        $cont->servidor_id = $master->id;
                        $cont->tipo = $param['list_tipo'][$row];
                        $cont->contato = $param['list_contato'][$row];
                        $cont->responsavel = $param['list_responsavel'][$row];
                        $cont->principal = $param['list_principal'][$row];
                        $cont->observacao = $param['list_obs'][$row];
                        $cont->store();
                    }
                }
            }

            TTransaction::close(); // close the transaction
            
            TForm::sendData('form_Servidor', (object) ['id' => $master->id]);
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }

    public function onClear($param)
    {
        $this->fieldlist->addHeader();
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addCloneAction();
    }

    public static function onListaEspecialidade($param)
    {
        try
        {
            TTransaction::open('rh');
            if (!empty($param['detail_cargo_id']))
            {
                $criteria = TCriteria::create( ['cargo_id' => $param['detail_cargo_id'] ] );
                
                //detail_especialidade_id', 'rh', 'Especialidade', 'id', 'nome'); //ok 
                // formname, field, database, model, key, value, ordercolumn = NULL, criteria = NULL, startEmpty = FALSE
                TDBCombo::reloadFromModel('form_Servidor', 'detail_especialidade_id', 'rh', 'ViewEspecialidadeCargo', 'id', 'nome', null, $criteria, TRUE);
            }
            else
            {
                TDBCombo::clearField('form_Servidor', 'detail_especialidade_id');
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onExitCEP($param)
    {
        session_write_close();
        
        try
        {
            $cep = preg_replace('/[^0-9]/', '', $param['cep']);
            $url = 'https://viacep.com.br/ws/'.$cep.'/json/unicode/';
            
            $content = @file_get_contents($url);
            
            if ($content !== false)
            {
                $cep_data = json_decode($content);
                
                $data = new stdClass;
                if (is_object($cep_data) && empty($cep_data->erro))
                {
                    $data->logradouro  = $cep_data->logradouro;
                    $data->complemento = $cep_data->complemento;
                    $data->bairro      = $cep_data->bairro;
                    $data->estado      = $cep_data->uf;
                    $data->cidade      = $cep_data->localidade;
                    
                    TForm::sendData('form_Servidor', $data, false, true);
                }
                else
                {
                    $data->logradouro  = '';
                    $data->complemento = '';
                    $data->bairro      = '';
                    $data->estado   = '';
                    $data->cidade   = '';
                    
                    TForm::sendData('form_Servidor', $data, false, true);
                }
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
