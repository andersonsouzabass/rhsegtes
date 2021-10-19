<?php
/**
 * NomeadosList Listing
 * @author  <your name here>
 */
class NomeadosList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_NomeadosView');
        $this->form->setFormTitle('Lista de Nomeados');
        

        // create the form fields
        $matricula = new TEntry('matricula');
        $cpf = new TEntry('cpf');
        $nome = new TEntry('nome');
        $concurso = new TEntry('concurso');
        $cargo_sis = new TEntry('cargo_sis');
        $especialidade_sis = new TEntry('especialidade_sis');
        $ato = new TDBUniqueSearch('ato', 'rh', 'Ato', 'ato', 'ato');
        $classif = new TEntry('classif');
        $situacao = new TDBCombo('situacao', 'rh', 'SituacaoConcurso', 'nome', 'nome');
        $output_type = new TEntry('output_type');

        // add the fields       
        $row = $this->form->addFields(  [ new TLabel('CPF'), $cpf ],
                                        [ new TLabel('Concurso'), $concurso ],
                                        [ new TLabel('Ato'), $ato ],
                                        [ new TLabel('Classificação'), $classif ],
                                        [ new TLabel('Situação'), $situacao ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Matrícula'), $matricula ],
                                        [ new TLabel('Nome'), $nome ],
                                        [ new TLabel('Cargo'), $cargo_sis ],
                                        [ new TLabel('Especialidade'), $especialidade_sis ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-4', 'col-sm-2', 'col-sm-4'];

        /*
        $row = $this->form->addFields(  [ new TLabel('Selecione o Formato'), $output_type ]
                                        );
        $row->layout = ['col-sm-4'];*/

        $output_type->setValue('xls');

        // set sizes
        $matricula->setSize('100%');
        $cpf->setSize('100%');
        $nome->setSize('100%');
        $concurso->setSize('100%');
        $cargo_sis->setSize('100%');
        $especialidade_sis->setSize('100%');
        $ato->setSize('100%');
        $classif->setSize('100%');
        $situacao->setSize('100%');

        $output_type->setSize('100%');
        //$output_type->addValidation('Output', new TRequiredValidator);

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        //$this->form->addActionLink('Cadastrar', new TAction(['NomeadosForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_aprovados_id = new TDataGridColumn('aprovados_id', 'Aprovados Id', 'right');
        $column_insc = new TDataGridColumn('insc', 'Inscrição', 'center');
        $column_matricula = new TDataGridColumn('matricula', 'Matrícula', 'center');
        $column_cpf = new TDataGridColumn('cpf', 'CPF', 'center');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_concurso = new TDataGridColumn('concurso', 'Concurso', 'center');
        $column_cargo_sis = new TDataGridColumn('cargo_sis', 'Cargo', 'center');
        $column_especialidade_sis = new TDataGridColumn('especialidade_sis', 'Especialidade', 'center');
        $column_classif = new TDataGridColumn('classif', 'Classificação', 'center');
        $column_ato = new TDataGridColumn('ato', 'Ato', 'center');
        $column_dt_nomeacao = new TDataGridColumn('dt_nomeacao', 'Nomeação', 'center');
        $column_dt_publicacao = new TDataGridColumn('dt_publicacao', 'Publicação', 'center');
        $column_email = new TDataGridColumn('email', 'E-mail', 'center');
        
        $column_data_prazo_posse_ato = new TDataGridColumn('data_prazo_posse_ato', 'Prazo Ato Posse', 'center');
        $column_data_prazo_legal_ato = new TDataGridColumn('data_prazo_legal_ato', 'Prazo Legal Posse', 'center');
       
        $column_situacao = new TDataGridColumn('situacao', 'Situação', 'center');

        //prazo_posse_ato
        //prazo_legal_ato


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id)->setVisibility(false);
        $this->datagrid->addColumn($column_aprovados_id)->setVisibility(false);
        $this->datagrid->addColumn($column_insc);
        $this->datagrid->addColumn($column_matricula);
        $this->datagrid->addColumn($column_cpf)->setVisibility(false);  
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_concurso);
        $this->datagrid->addColumn($column_cargo_sis);
        $this->datagrid->addColumn($column_especialidade_sis);
        $this->datagrid->addColumn($column_classif)->setVisibility(false);
        $this->datagrid->addColumn($column_ato);
        $this->datagrid->addColumn($column_dt_nomeacao);
        $this->datagrid->addColumn($column_dt_publicacao)->setVisibility(false);

        $this->datagrid->addColumn($column_data_prazo_posse_ato);
        $this->datagrid->addColumn($column_data_prazo_legal_ato)->setVisibility(false);

        $this->datagrid->addColumn($column_situacao);
        $this->datagrid->addColumn($column_email);

        //Formatar as colunas de datas no padrão
        $column_dt_publicacao->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });
        
        $column_dt_nomeacao->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        /*
        $column_data_prazo_posse_ato->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });
        */

        //Farois na coluna prazo de vencimento do processo
        $column_data_prazo_posse_ato->setTransformer( function($value, $object) {
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

        $column_data_prazo_legal_ato->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });
        
        //Ações do datagrid
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);

        $action1 = new TDataGridAction(['NomeadosForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onEncaminhar'], ['id'=>'{id}']);
        $action3 = new TDataGridAction(['NomeadoIdentifcaForm', 'onEdit'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,'Encaminhar', 'far:envelope green');
        $this->datagrid->addAction($action3 ,'Identificar', 'far:id-card blue');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        //$output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'RTF', 'xls' => 'XLS'));
        //$output_type->setLayout('horizontal');
        //$output_type->setUseButton();
        $output_type->setValue('xls');
        //$output_type->setSize(70);
        
        // add the action button
        $btn2 = $this->form->addAction('Gerar Relatório', new TAction(array($this, 'onGenerate')), 'fa:cog');
        $btn2->class = 'btn btn-success btn-sm';
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    public function somarPrazo($dt_prazo_posse_ato, $object)
    {
        $date = new DateTime($object->dt_nomeacao);
        return $date = date('Y-m-d', strtotime("+{$column_prazo_posse_ato} days",strtotime($data->dt_nomeacao)));
    }

    public static function onEncaminhar($param)
    {
        try
        {               
            $pdf = new TPDFDesigner;
            //Primeira página
            $pdf->fromXml('app/output/concurso/encaminhamento.pdf.xml');
            $key=$param['key']; // get the parameter $key
            
            //Conexões com o banco para carregar os nomes
            TTransaction::open('rh');
            
            //inicio     
            $vCandidato = Nomeados::find($key, FALSE);
            if ($vCandidato instanceof Nomeados)
            {
                $vCandidato_id = $vCandidato->aprovados_id;
                $pessoa_id = $vCandidato->pessoa_id;
                $cargo_id = $vCandidato->cargo_id;
                $especialidade_id = $vCandidato->especialidade_id;
                $ato_id = $vCandidato->ato_id;
                $dt_posse = $vCandidato->dt_posse;
                $dt_encaminhado = $vCandidato->dt_encaminhado;
                $regime_trabalho_id = $vCandidato->regime_trabalho_id;
            }  
            //Fim inicio

            //Aprovados      
            $vCandidato = Aprovados::find($vCandidato_id, FALSE);
            if ($vCandidato instanceof Aprovados)
            {
                $vCandidato_nome = $vCandidato->nome;   
                $vCandidato_insc = $vCandidato->insc;                
            }  
            //Fim Aprovados
            
            //Unidade de Saúde      
            $vUnidade = Pessoa::find($pessoa_id);
            if ($vUnidade instanceof Pessoa)
            {
                $pessoa = $vUnidade->nome;                
            }  
            //Fim Unidade de Saúde   

            //Cargo      
            $vCargo = Cargo::find($cargo_id);
            if ($vCargo instanceof Cargo)
            {
                $cargo = $vCargo->nome;                
            }  
            //Fim Cargo

            //Especialidade      
            $vEspecialidade = Especialidade::find($especialidade_id);
            if ($vEspecialidade instanceof Especialidade)
            {
                $especialidade = $vEspecialidade->nome;                
            }  
            //Fim Especialidade

            //Ato      
            $vAto = Ato::find($ato_id);
            if ($vAto instanceof Ato)
            {
                $ato = $vAto->ato;
                $dt_publicacao = $vAto->dt_publicacao;
            }  
            //Fim Ato

            //Regime      
            $vRegime = Regime::find($regime_trabalho_id);
            if ($vRegime instanceof Regime)
            {
                $texto_regime = $vRegime->tipo;                
            }             
            //Fim Regime

            //CPF      
            $vCpf = Aprovados::find($vCandidato_id);
            if ($vCpf instanceof Aprovados)
            {
                $cpf = $vCpf->cpf;                
            }             
            //Fim CPF
                        
            $data_p = new DateTime($dt_publicacao);
            $dt_publicacao = $data_p->format('d/m/Y');

            $data_ps = new DateTime($dt_posse);
            $dt_posse = $data_ps->format('d/m/Y');

            $dia = new UtilDatas();
            $dia = $dia->dataextenso($dt_encaminhado);

                //Formata o CPF para impressão
                $formataCPF  = substr( $cpf, 0, 3 ) . '.';
                $formataCPF .= substr( $cpf, 3, 3 ) . '.';
                $formataCPF .= substr( $cpf, 6, 3 ) . '-';
                $formataCPF .= substr( $cpf, 9, 2 ) . '';
            
            /**
             * Consulta o valor da remuneração para a impressão
             */
            $remuneracao = Salario::where('cargo_id', '=', $cargo_id)
                                    ->where('regime_trabalho_id', '=', $regime_trabalho_id)
                                    ->sumBy('valor');
            $remuneracao =  round($remuneracao * 1, 3);
            $remuneracao = (string)$remuneracao;
            $remuneracao = 'R$ ' .str_replace(['.'],[','], $remuneracao);            
            //Fim de remuneração

            $pdf->replace('{unidade}',  utf8_decode($pessoa));
            $pdf->replace('{candidato}',  utf8_decode($vCandidato_nome));
            $pdf->replace('{cargo}',  utf8_decode($cargo));
            $pdf->replace('{especialidade}',  utf8_decode($especialidade));
            $pdf->replace('{ato}',  utf8_decode($ato));
            $pdf->replace('{dt_publicacao}',  ($dt_publicacao));
            $pdf->replace('{dt_posse}',  utf8_decode($dt_posse));
            $pdf->replace('{dia}',  $dia);

            $pdf->generate(); //Gera a primeira página
            //Fim da primeira Página

            //Início da segunda página
            $pdf->fromXml('app/output/concurso/encaminhamento_2.pdf.xml');

            $dt_encaminhado = new DateTime($dt_encaminhado);
            $dt_encaminhado = $dt_encaminhado->format('d/m/Y');

            $pdf->replace('{unidade}',  utf8_decode($pessoa));
            $pdf->replace('{candidato}',  utf8_decode($vCandidato_nome));
            $pdf->replace('{cargo}',  utf8_decode($cargo));
            $pdf->replace('{especialidade}',  utf8_decode($especialidade));
            $pdf->replace('{regime}',  utf8_decode($texto_regime));
            $pdf->replace('{hoje}',  utf8_decode($dt_encaminhado));
            $pdf->replace('{ato}',  utf8_decode($ato));
            $pdf->replace('{dt_publicacao}',  ($dt_publicacao));
            $pdf->replace('{dt_posse}',  utf8_decode($dt_posse));
            $pdf->replace('{inscricao}',  $vCandidato_insc);
            
            $pdf->generate(); //Gera a segunda página
            //Fim da segunda página

            //Início da terceira página
            $pdf->fromXml('app/output/concurso/encaminhamento_3.pdf.xml');

            $pdf->replace('{candidato}',  utf8_decode($vCandidato_nome));
            $pdf->replace('{cargo}',  utf8_decode($cargo));
            $pdf->replace('{especialidade}',  utf8_decode($especialidade));
            $pdf->replace('{dia}', $dia);
            $pdf->replace('{cpf}',  utf8_decode($formataCPF));
            $pdf->replace('{dt_posse}',  utf8_decode($dt_posse));
            $pdf->replace('{remuneracao}',  utf8_decode($remuneracao));
            
            $pdf->generate(); //Gera a terceira página
            TTransaction::close();
            //Fim da conexao
            $file = 'app/output/concurso/local_coleta.pdf';

            if (!file_exists($file) OR is_writable($file))
            {
                $pdf->save($file);
                //parent::openFile($file);
                
                $window = TWindow::create('Carta de Apresentação', 0.9, 0.91);
                $object = new TElement('object');
                $object->data  = $file;
                $object->type  = 'application/pdf';
                $object->style = "width: 100%; height:calc(100% - 20px)";
                $window->add($object);
                $window->show();
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $file);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
        }
    }


    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('rh'); // open a transaction with database
            $object = new NomeadosView($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue(__CLASS__.'_filter_matricula',   NULL);
        TSession::setValue(__CLASS__.'_filter_cpf',   NULL);
        TSession::setValue(__CLASS__.'_filter_nome',   NULL);
        TSession::setValue(__CLASS__.'_filter_concurso',   NULL);
        TSession::setValue(__CLASS__.'_filter_cargo_sis',   NULL);
        TSession::setValue(__CLASS__.'_filter_especialidade_sis',   NULL);
        TSession::setValue(__CLASS__.'_filter_ato',   NULL);
        TSession::setValue(__CLASS__.'_filter_classif',   NULL);
        TSession::setValue(__CLASS__.'_filter_situacao',   NULL);

        if (isset($data->matricula) AND ($data->matricula)) {
            $filter = new TFilter('matricula', 'like', "%{$data->matricula}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_matricula',   $filter); // stores the filter in the session
        }
        
        if (isset($data->cpf) AND ($data->cpf)) {
            $filter = new TFilter('cpf', 'like', "%{$data->cpf}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_cpf',   $filter); // stores the filter in the session
        }


        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'like', "%{$data->nome}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_nome',   $filter); // stores the filter in the session
        }


        if (isset($data->concurso) AND ($data->concurso)) {
            $filter = new TFilter('concurso', 'like', "%{$data->concurso}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_concurso',   $filter); // stores the filter in the session
        }


        if (isset($data->cargo_sis) AND ($data->cargo_sis)) {
            $filter = new TFilter('cargo_sis', 'like', "%{$data->cargo_sis}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_cargo_sis',   $filter); // stores the filter in the session
        }

        if (isset($data->especialidade_sis) AND ($data->especialidade_sis)) {
            $filter = new TFilter('especialidade_sis', 'like', "%{$data->especialidade_sis}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_especialidade_sis',   $filter); // stores the filter in the session
        }


        if (isset($data->ato) AND ($data->ato)) {
            $filter = new TFilter('ato', 'like', $data->ato); // create the filter
            TSession::setValue(__CLASS__.'_filter_ato',   $filter); // stores the filter in the session
        }

        if (isset($data->classif) AND ($data->classif)) {
            $filter = new TFilter('classif', '=', $data->classif); // create the filter
            TSession::setValue(__CLASS__.'_filter_classif',   $filter); // stores the filter in the session
        }

        if (isset($data->situacao) AND ($data->situacao)) {
            $filter = new TFilter('situacao', '=', $data->situacao); // create the filter
            TSession::setValue(__CLASS__.'_filter_situacao',   $filter); // stores the filter in the session
        }
        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue(__CLASS__ . '_filter_data', $data);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'rh'
            TTransaction::open('rh');
            
            // creates a repository for NomeadosView
            $repository = new TRepository('NomeadosView');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue(__CLASS__.'_filter_matricula')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_matricula')); // add the session filter
            }
            
            if (TSession::getValue(__CLASS__.'_filter_cpf')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_cpf')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_nome')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_nome')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_concurso')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_concurso')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_cargo_sis')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_cargo_sis')); // add the session filter
            }

            if (TSession::getValue(__CLASS__.'_filter_especialidade_sis')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_especialidade_sis')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_ato')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_ato')); // add the session filter
            }

            if (TSession::getValue(__CLASS__.'_filter_classif')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_classif')); // add the session filter
            }

            if (TSession::getValue(__CLASS__.'_filter_situacao')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_situacao')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('rh'); // open a transaction with database
            $object = new NomeadosView($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

    function onGenerate()
    {
        try
        {
            // open a transaction with database 'rh'
            TTransaction::open('rh');
            
            // get the form data into an active record
            $data = $this->form->getData();
            
            $this->form->validate();
            
            $repository = new TRepository('NomeadosView');
            $criteria   = new TCriteria;
            
            if ($data->concurso)
            {
                $criteria->add(new TFilter('concurso', 'like', "%{$data->concurso}%"));
            }
            if ($data->matricula)
            {
                $criteria->add(new TFilter('matricula', 'like', "%{$data->matricula}%"));
            }
            if ($data->nome)
            {
                $criteria->add(new TFilter('nome', 'like', "%{$data->nome}%"));
            }
            if ($data->cpf)
            {
                $criteria->add(new TFilter('cpf', 'like', "%{$data->cpf}%"));
            }
            if ($data->cargo_sis)
            {
                $criteria->add(new TFilter('cargo_sis', 'like', "%{$data->cargo_sis}%"));
            }
            if ($data->especialidade_sis)
            {
                $criteria->add(new TFilter('especialidade_sis', 'like', "%{$data->especialidade_sis}%"));
            }
            if ($data->ato)
            {
                $criteria->add(new TFilter('ato', '=', "{$data->ato}"));
            }
            if ($data->classif)
            {
                $criteria->add(new TFilter('classif', 'like', "%{$data->classif}%"));
            }
            if ($data->situacao)
            {
                $criteria->add(new TFilter('situacao', 'like', "%{$data->situacao}%"));
            }

           
            $objects = $repository->load($criteria, FALSE);
            $format  = 'xls';
            
            if ($objects)
            {
                $widths = array(100,100,100,100,100,100,100,100,100,100,100,100,50,50,50,50,50,100,100,100, 80);
                
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
                $tr->addStyle('title', 'Calibri', '10', 'B',   '#ffffff', '#008080');
                $tr->addStyle('datap', 'Calibri', '10', '',    '#000000', '#EEEEEE');
                $tr->addStyle('datai', 'Calibri', '10', '',    '#000000', '#ffffff');
                $tr->addStyle('header', 'Calibri', '14', '',   '#ffffff', '#494D90');
                $tr->addStyle('footer', 'Calibri', '10', 'I',  '#000000', '#B1B1EA');
                
                // add a header row
                //$tr->addRow();
                //$tr->addCell('Nomeados', 'center', 'header', 20);
                
                // add titles row
                $tr->addRow();
                $tr->addCell('Concurso', 'left', 'title');
                $tr->addCell('Insc', 'left', 'title');
                $tr->addCell('Matricula', 'left', 'title');
                $tr->addCell('Nome', 'left', 'title');
                $tr->addCell('Cpf', 'left', 'title');
                $tr->addCell('Cargo', 'left', 'title');
                $tr->addCell('Especialidade', 'left', 'title');
                $tr->addCell('Regime', 'left', 'title');
                $tr->addCell('Unidade', 'left', 'title');
                $tr->addCell('Geres', 'left', 'title');
                $tr->addCell('Ato', 'left', 'title');
                $tr->addCell('Justificativa Ato', 'left', 'title');
                $tr->addCell('Data Nomeacao', 'left', 'title');
                $tr->addCell('Data Publicacao', 'left', 'title');
                $tr->addCell('Data Posse', 'left', 'title');
                $tr->addCell('Data Encaminhado', 'left', 'title');
                $tr->addCell('Data Efetivo Exercicio', 'left', 'title');
                $tr->addCell('Classificação', 'left', 'title');
                $tr->addCell('E-mail', 'left', 'title');
                $tr->addCell('Situação', 'left', 'title');
                $tr->addCell('Reconvocado', 'left', 'title');

                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objects as $object)
                {
                    //Converte as datas para o formato brasileiro
                    $object->dt_nomeacao = TDate::date2br($object->dt_nomeacao);
                    $object->dt_publicacao = TDate::date2br($object->dt_publicacao);
                    $object->dt_posse = TDate::date2br($object->dt_posse);
                    $object->dt_encaminhado = TDate::date2br($object->dt_encaminhado);
                    $object->dt_efetivo_exercicio = TDate::date2br($object->dt_efetivo_exercicio);
                    
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($object->concurso, 'left', $style);
                    $tr->addCell($object->insc, 'left', $style);
                    $tr->addCell($object->matricula, 'left', $style);
                    $tr->addCell($object->nome, 'left', $style);
                    $tr->addCell($object->cpf, 'left', $style);
                    $tr->addCell($object->cargo_sis, 'left', $style);
                    $tr->addCell($object->especialidade_sis, 'left', $style);
                    $tr->addCell($object->regime, 'left', $style);
                    $tr->addCell($object->unidade, 'left', $style);
                    $tr->addCell($object->geres, 'left', $style);
                    $tr->addCell($object->ato, 'left', $style);
                    $tr->addCell($object->justificativa_ato, 'left', $style);
                    $tr->addCell($object->dt_nomeacao, 'left', $style);
                    $tr->addCell($object->dt_publicacao, 'left', $style);
                    $tr->addCell($object->dt_posse, 'left', $style);
                    $tr->addCell($object->dt_encaminhado, 'left', $style);
                    $tr->addCell($object->dt_efetivo_exercicio, 'left', $style);
                    $tr->addCell($object->classif, 'left', $style);
                    $tr->addCell($object->email, 'left', $style);
                    $tr->addCell($object->situacao, 'left', $style);
                    $tr->addCell($object->reconvocado, 'left', $style);

                    
                    $colour = !$colour;
                }
                
                                
                // stores the file
                if (!file_exists("app/output/Nomeados.{$format}") OR is_writable("app/output/Nomeados.{$format}"))
                {
                    $tr->save("app/output/Nomeados.{$format}");
                }
                else
                {
                    throw new Exception(_t('Permission denied') . ': ' . "app/output/Nomeados.{$format}");
                }
                
                // open the report file
                parent::openFile("app/output/Nomeados.{$format}");
                
                // shows the success message
                new TMessage('info', 'Relatório gerado com sucesso!');
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
}
