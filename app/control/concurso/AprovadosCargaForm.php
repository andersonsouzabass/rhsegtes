<?php
/**
 * AtoForm Form
 * @author  <your name here>
 */
class AprovadosCargaForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        parent::setSize( 0.6, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Aprovado');
        $this->form->setFormTitle('Criar Lista de Aprovados');
        

        // create the form fields
        
        $file = new TFile('file');
        $file->setAllowedExtensions( ['xls'] );

        // add the fields       
        $row = $this->form->addFields(  [ new TLabel('Escolher Planilha'), $file ]
                                        );
        $row->layout = ['col-sm-8'];

        // set sizes
        $file->setSize('100%');
        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        // create the form actions
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onUpload')), 'far:save' );
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

    public function onUpload($param) {
        // faz o upload do arquivo
        
        // depois do upload, aplica-se o código abaixo

        try
        {
            TTransaction::open('rh'); // open a transaction

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            var_dump($reader);
            $arq = 'tmp/'.$pasta . DIRECTORY_SEPARATOR . $arquivo;
            $spredsheet = $reader->load($arq);
            $i = 2; // pega a partir da 2 linha, geralmente a primeiro é o cabeçalho.
            $nulo = false;
            while(!$nulo){
                $inscricao = $spredsheet->getActiveSheet()->getCellByColumnAndRow(1,$i);
                $insc = $spredsheet->getActiveSheet()->getCellByColumnAndRow(2,$i);
                $nome = $spredsheet->getActiveSheet()->getCellByColumnAndRow(3,$i);
                $identidade = $spredsheet->getActiveSheet()->getCellByColumnAndRow(4,$i);
                $cpf = $spredsheet->getActiveSheet()->getCellByColumnAndRow(5,$i);
                $nascimento = $spredsheet->getActiveSheet()->getCellByColumnAndRow(6,$i);
                $cod_cargo = $spredsheet->getActiveSheet()->getCellByColumnAndRow(7,$i);
                $cargo = $spredsheet->getActiveSheet()->getCellByColumnAndRow(8,$i);
                $classif = $spredsheet->getActiveSheet()->getCellByColumnAndRow(9,$i);
                $classif_def = $spredsheet->getActiveSheet()->getCellByColumnAndRow(10,$i);
                $tipo_def = $spredsheet->getActiveSheet()->getCellByColumnAndRow(11,$i);
                $nota_final = $spredsheet->getActiveSheet()->getCellByColumnAndRow(12,$i);
                $resultado = $spredsheet->getActiveSheet()->getCellByColumnAndRow(13,$i);
                $endereco = $spredsheet->getActiveSheet()->getCellByColumnAndRow(14,$i);
                $num = $spredsheet->getActiveSheet()->getCellByColumnAndRow(15,$i);
                $complemento = $spredsheet->getActiveSheet()->getCellByColumnAndRow(16,$i);
                $bairro = $spredsheet->getActiveSheet()->getCellByColumnAndRow(17,$i);
                $cep = $spredsheet->getActiveSheet()->getCellByColumnAndRow(18,$i);
                $cidade = $spredsheet->getActiveSheet()->getCellByColumnAndRow(19,$i);
                $estado = $spredsheet->getActiveSheet()->getCellByColumnAndRow(20,$i);
                $email = $spredsheet->getActiveSheet()->getCellByColumnAndRow(21,$i);
                $fone = $spredsheet->getActiveSheet()->getCellByColumnAndRow(22,$i);
                $celular = $spredsheet->getActiveSheet()->getCellByColumnAndRow(23,$i);
                $formacao_escolaridade = $spredsheet->getActiveSheet()->getCellByColumnAndRow(24,$i);
                $nome_da_mae = $spredsheet->getActiveSheet()->getCellByColumnAndRow(25,$i);
                $ano = $spredsheet->getActiveSheet()->getCellByColumnAndRow(26,$i);
                $ato = $spredsheet->getActiveSheet()->getCellByColumnAndRow(27,$i);
                
                $vAprovado = new Aprovados();

                $vAprovado->inscricao = $inscricao->getValue();
                $vAprovado->insc = $insc->getValue();
                $vAprovado->nome = $nome->getValue();
                $vAprovado->identidade = $identidade->getValue();
                $vAprovado->cpf = $cpf->getValue();
                $vAprovado->nascimento = $nascimento->getValue();
                $vAprovado->cod_cargo = $cod_cargo->getValue();
                $vAprovado->cargo = $cargo->getValue();
                $vAprovado->classif = $classif->getValue();
                $vAprovado->classif_def = $classif_def->getValue();
                $vAprovado->tipo_def = $tipo_def->getValue();
                $vAprovado->nota_final = $nota_final->getValue();
                $vAprovado->resultado = $resultado->getValue();
                $vAprovado->endereco = $endereco->getValue();
                $vAprovado->num = $num->getValue();
                $vAprovado->complemento = $complemento->getValue();
                $vAprovado->bairro = $bairro->getValue();
                $vAprovado->cep = $cep->getValue();
                $vAprovado->cidade = $cidade->getValue();
                $vAprovado->estado = $estado->getValue();
                $vAprovado->email = $email->getValue();
                $vAprovado->fone = $fone->getValue();
                $vAprovado->celular = $celular->getValue();
                $vAprovado->formacao_escolaridade = $formacao_escolaridade->getValue();
                $vAprovado->nome_da_mae = $nome_da_mae->getValue();
                $vAprovado->ano = $ano->getValue();
                $vAprovado->ato = $ato->getValue();           
                
                $vAprovado->save();
                $i++;
            }
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
            
            $object = new Ato;  // create an empty object
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
                $object = new Ato($key); // instantiates the Active Record
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
