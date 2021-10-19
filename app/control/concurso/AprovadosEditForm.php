<?php
/**
 * AprovadosEditForm Form
 * @author  <your name here>
 */
class AprovadosEditForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize( 0.9, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Aprovados');
        $this->form->setFormTitle('Incluir Aprovado');
        

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
        $geres_id = new TDBCombo('geres_id', 'rh', 'Geres', 'id', 'nome');
        $regime_trabalho_id = new TDBCombo('regime_trabalho_id', 'rh', 'Regime', 'id', 'nome');


        // add the fields
        $row = $this->form->addFields(  [ new TLabel('ID'), $id ],
                                        [ new TLabel('Ano do Concurso'), $ano ],
                                        [ new TLabel('Inscrição'), $insc ],
                                        [ new TLabel('CPF'), $cpf ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2', 'col-sm-2', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Nome'), $nome ],
                                        [ new TLabel('Cargo'), $cargo ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-6'];

        $row = $this->form->addFields(  [ new TLabel('Geres'), $geres_id ],
                                        [ new TLabel('Regime de Trabalho'), $regime_trabalho_id ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-3'];

        /*
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Ano') ], [ $ano ] );
        $this->form->addFields( [ new TLabel('Ato') ], [ $ato ] );
        $this->form->addFields( [ new TLabel('Inscricao') ], [ $inscricao ] );
        $this->form->addFields( [ new TLabel('Insc') ], [ $insc ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Identidade') ], [ $identidade ] );
        $this->form->addFields( [ new TLabel('Cpf') ], [ $cpf ] );
        $this->form->addFields( [ new TLabel('Nascimento') ], [ $nascimento ] );
        $this->form->addFields( [ new TLabel('Cod Cargo') ], [ $cod_cargo ] );
        $this->form->addFields( [ new TLabel('Cargo') ], [ $cargo ] );
        $this->form->addFields( [ new TLabel('Classif') ], [ $classif ] );
        $this->form->addFields( [ new TLabel('Classif Def') ], [ $classif_def ] );
        $this->form->addFields( [ new TLabel('Tipo Def') ], [ $tipo_def ] );
        $this->form->addFields( [ new TLabel('Nota Final') ], [ $nota_final ] );
        $this->form->addFields( [ new TLabel('Resultado') ], [ $resultado ] );
        $this->form->addFields( [ new TLabel('Endereco') ], [ $endereco ] );
        $this->form->addFields( [ new TLabel('Num') ], [ $num ] );
        $this->form->addFields( [ new TLabel('Complemento') ], [ $complemento ] );
        $this->form->addFields( [ new TLabel('Bairro') ], [ $bairro ] );
        $this->form->addFields( [ new TLabel('Cep') ], [ $cep ] );
        $this->form->addFields( [ new TLabel('Cidade') ], [ $cidade ] );
        $this->form->addFields( [ new TLabel('Estado') ], [ $estado ] );
        $this->form->addFields( [ new TLabel('Email') ], [ $email ] );
        $this->form->addFields( [ new TLabel('Fone') ], [ $fone ] );
        $this->form->addFields( [ new TLabel('Celular') ], [ $celular ] );
        $this->form->addFields( [ new TLabel('Formacao Escolaridade') ], [ $formacao_escolaridade ] );
        $this->form->addFields( [ new TLabel('Nome Da Mae') ], [ $nome_da_mae ] );
        $this->form->addFields( [ new TLabel('Geres Id') ], [ $geres_id ] );
        $this->form->addFields( [ new TLabel('Regime Trabalho Id') ], [ $regime_trabalho_id ] );
        */


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
        $geres_id->setSize('100%');
        $regime_trabalho_id->setSize('100%');

        $cpf->setMask('000.000.000-00',true);        

        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
                 
        // create the form actions
        /*
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        */
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );        
        $this->form->addActionLink('Cancelar', new TAction(array('AprovadosList','onReload')),  'fa:times red' );
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['AprovadosList', 'onReload']), 'fa:times red' );

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
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
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Aprovados;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
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
                $object = new Aprovados($key); // instantiates the Active Record
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
