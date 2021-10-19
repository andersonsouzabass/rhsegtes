<?php
/**
 * AprovadosForm Form
 * @author  <your name here>
 */
class AprovadosForm_temp extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        parent::setTargetContainer('adianti_right_panel');

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Aprovados');
        $this->form->setFormTitle('Nomear Candidato');
        //$this->form->setFieldSizes('100%');

        // create the form fields
        $id = new TEntry('id');
        $ano = new TEntry('ano');
        $ato = new TEntry('ato');
        $inscricao = new TEntry('inscricao');
        $insc = new TEntry('insc');
        $nome = new TEntry('nome');
        $identidade = new TEntry('identidade');
        $cpf = new TEntry('cpf');
        $nascimento = new TEntry('nascimento');
        $cod_cargo = new TEntry('cod_cargo');
        $cargo = new TEntry('cargo');
        $classif = new TEntry('classif');
        $classif_def = new TEntry('classif_def');
        $tipo_def = new TEntry('tipo_def');
        $nota_final = new TEntry('nota_final');
        $resultado = new TEntry('resultado');
        $endereco = new TEntry('endereco');
        $num = new TEntry('num');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');
        $cep = new TEntry('cep');
        $cidade = new TEntry('cidade');
        $estado = new TEntry('estado');
        $email = new TEntry('email');
        $fone = new TEntry('fone');
        $celular = new TEntry('celular');
        $formacao_escolaridade = new TEntry('formacao_escolaridade');
        $nome_da_mae = new TEntry('nome_da_mae');

        $this->form->addFields( [new TFormSeparator('Dados da Inscrição do Candidato')] );

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
        $ato->setSize('100%');
        $inscricao->setSize('100%');
        $insc->setSize('100%');
        $nome->setSize('100%');
        $identidade->setSize('100%');
        $cpf->setSize('100%');
        $nascimento->setSize('100%');
        $cod_cargo->setSize('100%');
        $cargo->setSize('100%');
        $classif->setSize('100%');
        $classif_def->setSize('100%');
        $tipo_def->setSize('100%');
        $nota_final->setSize('100%');
        $resultado->setSize('100%');
        $endereco->setSize('100%');
        $num->setSize('100%');
        $complemento->setSize('100%');
        $bairro->setSize('100%');
        $cep->setSize('100%');
        $cidade->setSize('100%');
        $estado->setSize('100%');
        $email->setSize('100%');
        $fone->setSize('100%');
        $celular->setSize('100%');
        $formacao_escolaridade->setSize('100%');
        $nome_da_mae->setSize('100%');

        $cpf->setMask('999.999.999-99', true);

        
        $id->setEditable(FALSE);
        $ano->setEditable(FALSE);
        $ato->setEditable(FALSE);
        $inscricao->setEditable(FALSE);
        $insc->setEditable(FALSE);
        $nome->setEditable(FALSE);
        $identidade->setEditable(FALSE);
        $cpf->setEditable(FALSE);
        $nascimento->setEditable(FALSE);
        $cod_cargo->setEditable(FALSE);
        $cargo->setEditable(FALSE);
        $classif->setEditable(FALSE);
        $classif_def->setEditable(FALSE);
        $tipo_def->setEditable(FALSE);
        $nota_final->setEditable(FALSE);
        $resultado->setEditable(FALSE);
        $endereco->setEditable(FALSE);
        $num->setEditable(FALSE);
        $complemento->setEditable(FALSE);
        $bairro->setEditable(FALSE);
        $cep->setEditable(FALSE);
        $cidade->setEditable(FALSE);
        $estado->setEditable(FALSE);
        $email->setEditable(FALSE);
        $fone->setEditable(FALSE);
        $celular->setEditable(FALSE);
        $formacao_escolaridade->setEditable(FALSE);
        $nome_da_mae->setEditable(FALSE);
        
        //-------------------------------------------------------------------------------------------------------------------
        //Espaço para Nomear os Candidatos
        $this->form->addFields( [new TFormSeparator('')] );
        $this->form->addFields( [new TFormSeparator('Nomear Candidato')] );

        // create the form fields da nomeação
        //$id = new TEntry('id');
        //$aprovados_id = new TDBUniqueSearch('aprovados_id', 'rh', 'Aprovados', 'id', 'ano');
        //$pessoa_id = new TDBUniqueSearch('pessoa_id', 'rh', 'Pessoa', 'id', 'nome');
        $nomeado_id=new TEntry('nomeado_id');
        $geres_id = new TDBCombo('geres_id', 'rh', 'Geres', 'id', 'nome');
        $ato_id = new TDBCombo('ato_id', 'rh', 'Ato', 'id', 'ato', 'dt_nomeacao');        
        $situacaoconcurso_id = new TEntry('situacaoconcurso_id');
        $obs = new TText('obs');
        $cargo_id = new TDBCombo('cargo_id', 'rh', 'cargo', 'id', 'nome');
        $especialidade_id = new TDBUniqueSearch('especialidade_id', 'rh', 'especialidade', 'id', 'nome');
        $regime_trabalho_id = new TDBCombo('regime_trabalho_id', 'rh', 'regime', 'id', 'nome', 'nome');

        $situacaoconcurso_id->setValue('2');

        $row = $this->form->addFields(  [ new TLabel('GERES'), $geres_id ],                                                                             
                                        [ new TLabel('Ato'), $ato_id ],
                                        [ new TLabel('Regime de Trabalho'), $regime_trabalho_id ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-3', 'col-sm-6'];

        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo_id ],
                                        [ new TLabel('Especialidade'), $especialidade_id ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $row = $this->form->addFields(  [ new TLabel('Observação'), $obs ]
                                        );
        $row->layout = ['col-sm-12'];

        // set sizes
        //$id->setSize('100%');
        //$aprovados_id->setSize('100%');
        //$pessoa_id->setSize('100%');
        $geres_id->setSize('100%');
        $ato_id->setSize('100%');        
        $situacaoconcurso_id->setSize('100%');
        $obs->setSize('100%');
        $cargo_id->setSize('100%');
        $especialidade_id->setSize('100%');
        $regime_trabalho_id->setSize('100%');

        //Fim da nomeação

        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Fechar',  new TAction([$this, 'onClose']), 'fa:times red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('rh'); // open a transaction
            
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Nomeados;  // create an empty object

              // Cargo
              $id_nm = Nomeados::find($item->cargo_id);
              if ($cCargo instanceof Cargo)
              {
                  $item->cargo = $cCargo->nome;                                
              }   
              //Fim Simbolo

            $object->aprovados_id = $data->id;
            $object->geres_id = $data->geres_id;
            $object->ato_id = $data->ato_id;
            $object->regime_trabalho_id = $data->regime_trabalho_id;
            $object->cargo_id = $data->cargo_id;
            $object->especialidade_id = $data->especialidade_id;
            $object->obs = $data->obs;

            var_dump($object);
            //$object->fromArray( (array) $data); // load the object with data
           //$object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Candidato Nomeado com Sucesso!'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('rh'); // open a transaction
                $object = new Aprovados($key); // instantiates the Active Record
                
                //$aprovados_id = $key;                
                //$vNomeado = Nomeados::find($aprovados_id);
                $vNomeado = Nomeados::where('aprovados_id', '=', $key)->load();
                $vNomeado instanceof Nomeados;
                var_dump($vNomeado['id']);
               
                $this->form->setData($object); // fill the form
                $this->form->setData($vNomeado); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
               $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
