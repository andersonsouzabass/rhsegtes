<?php
/**
 * ViewFolhaForm Master/Detail
 * @author  <your name here>
 */
class CensoForm extends TWindow
{
    protected $form; // form
    protected $fieldlist;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct($param);
        
        parent::setSize( 0.8, NULL);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_ViewFolha');
        $this->form->setFormTitle('VALIDA CENSO');
        
        // master fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $CPF = new TEntry('CPF');
        //$data_admissao = new TDate('data_admissao');
        $matricula = new TEntry('matricula');
        $cargo = new TEntry('cargo');
        $funcao = new TEntry('funcao');
        $unidade_1 = new TEntry('unidade_1');
        $unidade_2 = new TEntry('unidade_2');
        $unidade_folha = new TEntry('unidade_folha');
        //$status_censo = new TEntry('status_censo');
        // sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $CPF->setSize('100%');
        //$data_admissao->setSize('50%');
        $matricula->setSize('100%');
        $cargo->setSize('100%');
        $funcao->setSize('100%');
        $unidade_1->setSize('100%');
        $unidade_2->setSize('100%');
        $unidade_folha->setSize('100%');
        //$status_censo->setSize('100%');

        // Fields que devem ser preenchidos pelo censo
        $pessoa_id = new TDBUniqueSearch('pessoa_id', 'rh','Pessoa', 'id', 'nome', 'nome'); //Unidade de lotação do servidor
        $cargo_id = new TDBCombo('cargo_id', 'rh','Cargo', 'id', 'nome', 'nome'); //Definição de cargo da saúde
        $especialidade_id = new TDBUniqueSearch('especialidade_id','rh','Especialidade', 'id', 'nome', 'nome'); //Definição de especialidade resumida
        $funcao_id = new TDBCombo('funcao_id','rh','Funcao', 'id', 'nome', 'nome'); //Função desempenhada na unidade
        $setor_id = new TDBUniqueSearch('setor_id','rh','Setor', 'id', 'nome', 'nome'); //Função desempenhada na unidade
        
        // Fields que devem ser preenchidos pelo censo / Área dos cedidos
        $mat_origem = new TEntry('mat_origem'); //Matrícula de origem
        $orgaocedente_id = new TDBUniqueSearch('orgaocedente_id', 'rh','OrgaoCedente', 'id', 'nome', 'nome'); //Definição de órgão cedente
        $cargo_origem_id = new TDBCombo('cargo_origem_id', 'rh','Cargo', 'id', 'nome', 'nome'); //Definição de cargo da saúde
        $especialidade_origem_id = new TDBUniqueSearch('especialidade_origem_id','rh','Especialidade', 'id', 'nome', 'nome'); //Definição de especialidade resumida
        
        $cep = new TEntry('cep');
        $logradouro = new TEntry('logradouro');
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $bairro = new TEntry('bairro');       
        $cidade = new TEntry('cidade');
        $estado = new TEntry('estado');
        
        //Field de anotações
        $anotacao = new TText('anotacao');

        $cep->setExitAction( new TAction([ $this, 'onExitCEP']) );

        // master fields
        $row = $this->form->addFields(  [ new TLabel('ID'), $id ],
                                        [ new TLabel('Matrícula'), $matricula ],
                                        [ new TLabel('CPF'), $CPF ],
                                        [ new TLabel('Nome'), $nome ],
                                        //[ new TLabel('Admissão'), $data_admissao ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2',  'col-sm-2', 'col-sm-6']; 
        
        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo ],
                                        [ new TLabel('Função'), $funcao ],
                                        [ new TLabel('Lotação Folha'), $unidade_folha ]
                                        );
        $row->layout = ['col-sm-3', 'col-sm-3', 'col-sm-6']; 
         
        $row = $this->form->addFields(  [ new TLabel('Lotação 2'), $unidade_2 ],
                                        [ new TLabel('Executiva'), $unidade_1 ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-6']; 


        // Separador para a área que será do censo
        $this->form->addContent( [''] );
        $this->form->addContent( [ TElement::tag('h5', '<b>Dados do Censo</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
        
        $row = $this->form->addFields(  [ new TLabel('Lotação'), $pessoa_id ],
                                        [ new TLabel('Cargo'), $cargo_id ],
                                        [ new TLabel('Especialidade'), $especialidade_id ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-3', 'col-sm-3']; 
        
        $row = $this->form->addFields(  [ new TLabel('Função Desempenhada na Unidade'), $funcao_id ],
                                        [ new TLabel('Área de Atuação'), $setor_id ]
                                        );
        $row->layout = ['col-sm-6', 'col-sm-6']; 
        
        // Separador para a área que será para os cedidos
        $this->form->addContent( [''] );
        $this->form->addContent( [ TElement::tag('h5', '<b>Dados de Cedidos</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
        
        $row = $this->form->addFields(  [ new TLabel('Matrícula de Origem'), $mat_origem ],
                                        [ new TLabel('Órgão Cedente'), $orgaocedente_id ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-8']; 
        
        $row = $this->form->addFields(  [ new TLabel('Cargo na Origem'), $cargo_origem_id ],
                                        [ new TLabel('Função na Origem'), $especialidade_origem_id ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-6']; 
        
        $this->form->addContent( [''] ); // Uma linha de espaço
        
        //Endereço dos cedidos
        $row = $this->form->addFields(  [ new TLabel('CEP'), $cep ],
                                        [ new TLabel('Logradouro'), $logradouro ],
                                        [ new TLabel('Número'), $numero ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-8', 'col-sm-2'];
        
        $row = $this->form->addFields(  [ new TLabel('Complemento'), $complemento ],
                                        [ new TLabel('Bairro'), $bairro ],
                                        [ new TLabel('Cidade'), $cidade ],
                                        [ new TLabel('Estado'), $estado ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-3', 'col-sm-3', 'col-sm-2'];


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $CPF->setSize('100%');
        //$data_admissao->setSize('100%');
        $matricula->setSize('100%');
        $cargo->setSize('100%');
        $funcao->setSize('100%');
        $unidade_1->setSize('100%');
        $unidade_2->setSize('100%');
        $unidade_folha->setSize('100%');
        //$status_censo->setSize('100%');

        $CPF->setMask('000.000.000-00',true);
        //$data_admissao->setMask('dd/mm/yyyy');

        // Set Sizes do censo
        $pessoa_id->setSize('100%');
        $cargo_id->setSize('100%');
        $especialidade_id->setSize('100%');
        $funcao_id->setSize('100%');
        $setor_id->setSize('100%');
        
        // Set Sizes do cedido
        $mat_origem->setSize('100%');
        $orgaocedente_id->setSize('100%');
        $cargo_origem_id->setSize('100%');
        $especialidade_origem_id->setSize('100%');
        
        
        $cep->setSize('100%');
        $logradouro->setSize('100%');
        $numero->setSize('100%');
        $complemento->setSize('100%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $estado->setSize('100%');
        
        $cep->setMask('99.999-999');

        
        //Set Sizes de Anotações
        $anotacao->setSize('100%');
        
        if (!empty($id))
        {
            $id->setEditable(FALSE);
            $nome->setEditable(FALSE);
            $CPF->setEditable(FALSE);
            $matricula->setEditable(FALSE);
            $cargo->setEditable(FALSE);
            $funcao->setEditable(FALSE);
            $unidade_1->setEditable(FALSE);
            $unidade_2->setEditable(FALSE);
            $unidade_folha->setEditable(FALSE);
        }
        
        
        // detail fields
        $this->fieldlist = new TFieldList;
        $this->fieldlist-> width = '100%';
        $this->fieldlist->enableSorting();

        $tipo = new TCombo('list_tipo[]');
        $contato = new TEntry('list_contato[]');
        $responsavel = new TEntry('list_responsavel[]');
        $principal = new TCombo('list_principal[]');
        $observacao = new TEntry('list_observacao[]');

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
        $observacao->setSize('100%');

        $this->fieldlist->addField( '<b>Tipo</b>', $tipo);
        $this->fieldlist->addField( '<b>Contato</b>', $contato);
        $this->fieldlist->addField( '<b>Responsavel</b>', $responsavel);
        $this->fieldlist->addField( '<b>Principal</b>', $principal);
        $this->fieldlist->addField( '<b>Observacao</b>', $observacao);

        $this->form->addField($tipo);
        $this->form->addField($contato);
        $this->form->addField($responsavel);
        $this->form->addField($principal);
        $this->form->addField($observacao);
        
        $this->form->addContent( [''] );
        $this->form->addContent( [ TElement::tag('h5', '<b>Contatos</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );

        //$this->form->addFields( [new TFormSeparator('Contato') ] );
        $this->form->addFields( [$this->fieldlist] );
        
        $row = $this->form->addFields(  [ new TLabel('Anotação'), $anotacao ]
                                        );
        $row->layout = ['col-sm-12']; 

        
        //Botões do footer
        $this->form->addAction('Validar Servidor', new TAction(array($this, 'onSave')),  'far:check-circle blue' );
        $this->form->addAction('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addAction( 'Cancelar',  new TAction(['FolhaList', 'onReload']), 'fa:times red' );
        
        // create the page container
        $container = new TVBox;
        $container->style = 'width: 100%';
        //$container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        parent::add($container);
    }
    
    /**
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            TTransaction::open('rh');
            if (isset($param['key']))
            {
                $key = $param['key'];
                $object = new ViewFolha($key);
                $object->id = $key;
                $this->form->setData($object);

                // Censo Consulta
                
                $criteria = new TCriteria; 
                $criteria->add(new TFilter('db_folha_id', '=', $key)); 
                
                // load using repository
                $repository = new TRepository('Censo'); 
                $customers = $repository->load($criteria); 
                
                $vCenso = new stdClass;
                foreach ($customers as $customer) 
                { 
                    $vCenso->pessoa_id = $customer->pessoa_id;
                    $vCenso->cargo_id = $customer->cargo_id;
                    $vCenso->especialidade_id = $customer->especialidade_id;
                    $vCenso->funcao_id = $customer->funcao_id;
                    $vCenso->setor_id = $customer->setor_id;
                    
                    $vCenso->mat_origem = $customer->mat_origem;
                    $vCenso->orgaocedente_id = $customer->orgaocedente_id;
                    $vCenso->cargo_origem_id = $customer->cargo_origem_id;
                    $vCenso->especialidade_origem_id = $customer->especialidade_origem_id;
                    
                    $vCenso->cep = $customer->cep;
                    $vCenso->logradouro = $customer->logradouro;
                    $vCenso->numero = $customer->numero;
                    $vCenso->complemento = $customer->complemento;
                    $vCenso->bairro = $customer->bairro;
                    $vCenso->cidade = $customer->cidade;
                    $vCenso->estado = $customer->estado;
                    $vCenso->anotacao = $customer->anotacao;
                }
                TForm::sendData( 'form_ViewFolha', $vCenso, false, false );
                //var_dump($object);
               
                $items  = Contato::where('cpf', '=', $object->CPF)->load();
                
                if ($items)
                {
                    $this->fieldlist->addHeader();
                    foreach($items  as $item )
                    {
                        $detail = new stdClass;                        
                        $detail->list_tipo = $item->tipo;
                        $detail->list_contato = $item->contato;
                        $detail->list_responsavel = $item->responsavel;
                        $detail->list_principal = $item->principal;
                        $detail->list_observacao = $item->observacao;
                        $this->fieldlist->addDetail($detail);
                    }
                    
                    $this->fieldlist->addCloneAction();
                }
                else
                {
                    $this->onClear($param);
                }
                
                TTransaction::close(); // close transaction
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
     * Clear form
     */
    public function onClear($param)
    {
        $this->fieldlist->addHeader();
        $this->fieldlist->addDetail( new stdClass );
        $this->fieldlist->addCloneAction();
    }
    
    /**
     * Save the ViewFolha and the Contato's
     */
    public static function onSave($param)
    {
        try
        {
            TTransaction::open('rh');
                        
            $id = $param['id'];
            $master = new Censo;
            $master->fromArray( $param);
            
            $master->db_folha_id = $id;
             
            $master->status = 'VALIDADO';
            Censo::where('db_folha_id', '=', $id)->delete();
            $master->store(); // save master object
            
            //var_dump($id);
            // delete details            
            Contato::where('cpf', '=', str_replace(['.', '-'], ['', ''], $param['CPF']))->delete();
            
            if( !empty($param['list_tipo']) AND is_array($param['list_tipo']) )
            {
                foreach( $param['list_tipo'] as $row => $tipo)
                {
                    if (!empty($tipo))
                    {
                        $detail = new Contato;
                        $detail->cpf = str_replace(['.', '-'], ['', ''], $param['CPF']);
                        $detail->tipo = $param['list_tipo'][$row];
                        $detail->contato = $param['list_contato'][$row];
                        $detail->responsavel = $param['list_responsavel'][$row];
                        $detail->principal = $param['list_principal'][$row];
                        $detail->observacao = $param['list_observacao'][$row];
                        $detail->store();
                    }
                }
            }
            
            $data = new stdClass;
            $data->id = $master->id;
            TForm::sendData('form_ViewFolha', $data);
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
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
                    
                    TForm::sendData('form_ViewFolha', $data, false, true);
                }
                else
                {
                    $data->logradouro  = '';
                    $data->complemento = '';
                    $data->bairro      = '';
                    $data->estado   = '';
                    $data->cidade   = '';
                    
                    TForm::sendData('form_ViewFolha', $data, false, true);
                }
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}