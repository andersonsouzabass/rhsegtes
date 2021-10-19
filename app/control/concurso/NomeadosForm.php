<?php
/**
 * NomeadosForm Form
 * @author  <your name here>
 */
class NomeadosForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        parent::setSize( 0.8, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Nomeados');
        $this->form->setFormTitle('Cadastrar Nomeado');
        

        // create the form fields id
        $id = new TEntry('id');
        $pessoa_id = new TDBUniqueSearch('pessoa_id', 'rh', 'Pessoa', 'id', 'nome');
        $geres_id = new TDBUniqueSearch('geres_id', 'rh', 'Geres', 'id', 'nome');
        $situacaoconcurso_id = new TDBCombo('situacaoconcurso_id', 'rh', 'SituacaoConcurso', 'id', 'nome', 'nome'); 
        $aprovados_id = new THidden('aprovados_id', 'rh', 'Aprovados', 'id', 'nome');
        $cargo_id = new THidden('cargo_id', 'rh', 'Cargo', 'id', 'nome');
        $especialidade_id = new THidden('especialidade_id', 'rh', 'Especialidade', 'id', 'nome');
        $ato_id = new THidden('ato_id', 'rh', 'Ato', 'id', 'ato');          
        $regime_trabalho_id = new THidden('regime_trabalho_id', 'rh', 'Regime', 'id', 'nome', 'nome');  
        
        //Fields Descrição
        $aprovados = new TEntry('aprovados');
        $cargo = new TEntry('cargo');
        $especialidade = new TEntry('especialidade');
        $ato = new TEntry('ato');       
        $regime_trabalho = new TEntry('regime_trabalho');
        $obs = new TText('obs');

        $dt_posse = new TDate('dt_posse');
        $dt_encaminhado = new TDate('dt_encaminhado');
        $dt_efetivo_exercicio = new TDate('dt_efetivo_exercicio');
        $matricula = new TEntry('matricula');

        $reconvocado = new TRadioGroup('reconvocado');
        $reconvocado->addItems( [
            'SIM' => 'Sim',
            'NÂO' => 'Não'] );
        $reconvocado->setLayout('horizontal');
        $reconvocado->setValue('NÂO');

        $system_user_id_created = new TEntry('system_user_id_created');
        $created_at = new TDate('created_at');

        $system_user_id_updated = new TEntry('system_user_id_updated');
        $updated_at = new TEntry('updated_at');
            
        // add the fields       
        $row = $this->form->addFields(  [ new TLabel('id'), $id ],
                                        [ new TLabel('Nome'), $aprovados ],
                                        [ new TLabel('Regime de Trabalho'), $regime_trabalho ]
                                        
                                        );
        $row->layout = ['col-sm-2', 'col-sm-6', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Ato'), $ato ],
                                        [ new TLabel('Cargo'), $cargo ],
                                        [ new TLabel('Especialidade'), $especialidade ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-4', 'col-sm-6'];

        $row = $this->form->addFields(  [ new TLabel('GERES'), $geres_id ],
                                        [ new TLabel('Situação'), $situacaoconcurso_id ],
                                        [ new TLabel('Unidade'), $pessoa_id ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-4', 'col-sm-6'];

        $row = $this->form->addFields(  [ new TLabel('Reconvocado'), $reconvocado ],
                                        [ new TLabel('Posse'), $dt_posse ],
                                        [ new TLabel('Encaminhado'), $dt_encaminhado ],
                                        [ new TLabel('Efetivo Exercício'), $dt_efetivo_exercicio ],
                                        [ new TLabel('Matrícula SES'), $matricula ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-3', 'col-sm-3'];

        $row = $this->form->addFields(  [ new TLabel('Anotação'), $obs ]
                                        );
        $row->layout = ['col-sm-12'];

        $row = $this->form->addFields(  [ new TLabel('Registro Criado por:') , $system_user_id_created ],
                                        [ new TLabel('Registro Atualizado por:') , $system_user_id_updated ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-6'];

        /*
        $row = $this->form->addFields(  [ new TLabel('Registro Atualizado por:') , $system_user_id_updated ],
                                        [ new TLabel('No dia:') , $updated_at ]
                                        );
        $row->layout = ['col-sm-8', 'col-sm-4'];
        */
                                
        // set sizes
        $id->setSize('100%');
        $aprovados_id->setSize('100%');
        $pessoa_id->setSize('100%');
        $cargo_id->setSize('100%');
        $especialidade_id->setSize('100%');
        $geres_id->setSize('100%');
        $ato_id->setSize('100%');
        $situacaoconcurso_id->setSize('100%');        
        $obs->setSize('100%');
        $dt_posse->setSize('100%');
        $dt_encaminhado->setSize('100%');
        $dt_efetivo_exercicio->setSize('100%');
        $matricula->setSize('100%');
        $regime_trabalho_id->setSize('100%');

        $aprovados->setSize('100%');
        $cargo->setSize('100%');
        $especialidade->setSize('100%');
        $ato->setSize('100%');     
        $regime_trabalho->setSize('100%');
        $reconvocado->setSize('100%');
        
        $dt_posse->setMask('dd/mm/yyyy');
        $dt_posse->setDatabaseMask('yyyy-mm-dd');

        $dt_encaminhado->setMask('dd/mm/yyyy');
        $dt_encaminhado->setDatabaseMask('yyyy-mm-dd');

        $dt_efetivo_exercicio->setMask('dd/mm/yyyy');
        $dt_efetivo_exercicio->setDatabaseMask('yyyy-mm-dd');

        $system_user_id_created->setSize('100%');
        $system_user_id_updated->setSize('100%');
        $created_at->setSize('100%');
        $updated_at->setSize('100%');

        //Bloqueia os campos que não devem ser editados aqui
        $id->setEditable(FALSE);
        $aprovados->setEditable(FALSE);        
        $cargo->setEditable(FALSE);
        $especialidade->setEditable(FALSE);
        $ato->setEditable(FALSE);
        $regime_trabalho->setEditable(FALSE);

        $system_user_id_created->setEditable(FALSE);
        $system_user_id_updated->setEditable(FALSE);
        $created_at->setEditable(FALSE);
        $updated_at->setEditable(FALSE);
        
        
        // create the form actions
        //Botão de fechar da parte superior do form
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['NomeadosList', 'onReload']), 'fa:times red' );        

        //Botões da parte inferior
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        //$this->form->addActionLink('Gerar Carta de Apresentação', new TAction(array($this, 'onEncaminhar')),  'fa:check-circle green' );
       
        //$this->form->addActionLink('Fechar', new TAction(array('NomeadosList','onReload')),  'fa:times red' );


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    function onEncaminhar($param)
    {
        try
        {   
            $data = $this->form->getData();
            $pdf = new TPDFDesigner;
            $pdf->fromXml('app/output/concurso/encaminhamento.pdf.xml');

            //Conexões com o banco para carregar os nomes
            TTransaction::open('rh');
            
            //Unidade de Saúde      
            $vUnidade = Pessoa::find($data->pessoa_id);
            if ($vUnidade instanceof Pessoa)
            {
                $data->pessoa_id = $vUnidade->nome;                
            }  
            //Fim Unidade de Saúde   


            TTransaction::close();
            //Fim da conexao

            $pdf->replace('{unidade}',  utf8_decode($data->pessoa_id));

            /*
            $pdf->replace('{cliente}', utf8_decode($data->cliente_id));
            $pdf->replace('{pessoa}', utf8_decode($data->fornecedor_id));
            $pdf->replace('{endereco}', utf8_decode($data->local));
            $pdf->replace('{data}', $dt_result);
            $pdf->replace('{total}', $total);
            */

            $pdf->generate();

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
            $object->fromArray( (array) $data); // load the object with data
            
            $userid = TSession::getValue('userid');
            $object->system_user_id_updated = $userid;

            // Recupera id de quem criou o registro               
            $vIdReg = Nomeados::find($param['id']);
            if ($vIdReg instanceof Nomeados)
            {
                $object->system_user_id_created = $vIdReg->system_user_id_created;                                
            } 
            //Fim 

            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
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
                $object = new Nomeados($key); // instantiates the Active Record
               
                // Aprovado
                $cAprovado = Aprovados::find($object->aprovados_id);
                if ($cAprovado instanceof Aprovados)
                {
                    $object->aprovados = $cAprovado->nome;                                
                }  
                //Fim Aprovado

                // Cargo                 
                $cCargo = Cargo::find($object->cargo_id);
                if ($cCargo instanceof Cargo)
                {
                    $object->cargo = $cCargo->nome;                                
                } 
                //Fim Cargo

                // Especialidade                 
                $cEspecialidade = Especialidade::find($object->especialidade_id);
                if ($cEspecialidade instanceof Especialidade)
                {
                    $object->especialidade = $cEspecialidade->nome;                                
                } 
                //Fim Especialidade

                // Ato                 
                $cAto = Ato::find($object->ato_id);
                if ($cAto instanceof Ato)
                {
                    $object->ato = $cAto->ato;                                
                } 
                //Fim Ato
                
                // Regime                 
                $cRegime = Regime::find($object->regime_trabalho_id);
                if ($cRegime instanceof Regime)
                {
                    $object->regime_trabalho = $cRegime->nome;                                
                } 
                //Fim Regime

                // Usuario que criou o registro                 
                $cUser_reg = SystemUser::find($object->system_user_id_created);
                if ($cUser_reg instanceof SystemUser)
                {
                    $object->system_user_id_created = $cUser_reg->name;                                
                    //$object->system_user_id_created = $cUser_reg->name;                                
                } 
                //Fim 

                // Usuario que criou o registro                 
                $cUser_update = SystemUser::find($object->system_user_id_updated);
                if ($cUser_update instanceof SystemUser)
                {
                    $object->system_user_id_updated = $cUser_update->name;                                
                    //$object->system_user_id_created = $cUser_reg->name;                                
                } 
                //Fim 
                
               

                $this->form->setData($object); // fill the form
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
}