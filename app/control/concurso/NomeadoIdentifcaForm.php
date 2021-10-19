<?php
/**
 * Identificação de Nomeado
 * @author  Anderson Souza
 */
class NomeadoIdentifcaForm extends TWindow
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
        
        parent::setSize( 0.7, NULL);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Identifica_unicpe_concurso');
        $this->form->setFormTitle('Identificação de Nomeado em Concurso Público');
        
        // master fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');
        //$data_admissao = new TDate('data_admissao');
        $insc = new TEntry('insc');
        $cargo = new TEntry('cargo');
        $funcao = new TEntry('funcao');
        $unidade_1 = new TEntry('unidade_1');
        $unidade_2 = new TEntry('unidade_2');
        $unidade_folha = new TEntry('unidade_folha');
        //$status_censo = new TEntry('status_censo');
        // sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');
        //$data_admissao->setSize('50%');
        $insc->setSize('100%');
        $cargo->setSize('100%');
        $funcao->setSize('100%');
        $unidade_1->setSize('100%');
        $unidade_2->setSize('100%');
        $unidade_folha->setSize('100%');
        //$status_censo->setSize('100%');

        // Fields que devem ser preenchidos para a identificação do servidor
        $pessoa_id = new TEntry('unidade'); //Unidade de lotação do servidor
        $cargo_id = new TEntry('cargo_sis'); //Definição de cargo da saúde
        $especialidade_id = new TEntry('especialidade_sis'); //Definição de especialidade resumida
        $funcao_comissionado_id = new TDBUniqueSearch('funcao_comissionado_id','rh','FuncaoGrati', 'id', 'nome', 'nome'); //Função Gratificada ou cargo comissionado
        $situacao_folha_id = new TEntry('situacao'); //Situação na folha de pagamento do m^}es corrente
        $vinculo_id = new TDBCombo('vinculo_id','rh','vinculo', 'id', 'nome', 'nome'); //Situação na folha de pagamento do m^}es corrente
        
        //Fields que devem ser informados com informações complementares para a identificação do servidor
        $anotacao = new TText('anotacao');
        $periodo_gozo_ferias = new TEntry('periodo_gozo_ferias');
        $exerc_funcao_grat_com = new TEntry('exerc_funcao_grat_com');
        $afastado = new TEntry('afastado');
        
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
        

        $cep->setExitAction( new TAction([ $this, 'onExitCEP']) );
        
        $this->form->addContent( [ TElement::tag('h5', '<b>Dados da Ficha</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
        // master fields
        $row = $this->form->addFields(  [ new TLabel('ID'), $id ],
                                        [ new TLabel('Inscrição'), $insc ],
                                        [ new TLabel('CPF'), $cpf ],
                                        [ new TLabel('Nome'), $nome ],
                                        //[ new TLabel('Admissão'), $data_admissao ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-2',  'col-sm-2', 'col-sm-6']; 
                
        $row = $this->form->addFields(  [ new TLabel('Lotação'), $pessoa_id ]
                                        );
        $row->layout = ['col-sm-12']; 
        
        $row = $this->form->addFields(  [ new TLabel('Cargo'), $cargo_id ],
                                        [ new TLabel('Função / Especialidade'), $especialidade_id ],
                                        [ new TLabel('Situação'), $situacao_folha_id ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4', 'col-sm-4']; 
        
        $this->form->addContent( [''] ); // Uma linha de espaço
        $this->form->addContent( [ TElement::tag('h5', '<b>Endereço</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
        
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
        $cpf->setSize('100%');
        //$data_admissao->setSize('100%');
        $insc->setSize('100%');
        $cargo->setSize('100%');
        $funcao->setSize('100%');
        $unidade_1->setSize('100%');
        $unidade_2->setSize('100%');
        $unidade_folha->setSize('100%');
        //$status_censo->setSize('100%');
        
        $nome->forceUpperCase();

        $cpf->setMask('000.000.000-00',true);
        //$data_admissao->setMask('dd/mm/yyyy');

        // Set Sizes do censo
        $pessoa_id->setSize('100%');
        $cargo_id->setSize('100%');
        $especialidade_id->setSize('100%');
        $funcao_comissionado_id->setSize('100%');
        $situacao_folha_id->setSize('100%');
        $vinculo_id->setSize('100%');
        
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
        
        $logradouro->forceUpperCase();
        $complemento->forceUpperCase();
        $bairro->forceUpperCase();
        $cidade->forceUpperCase();
        $estado->forceUpperCase();
             
        $cep->setMask('99.999-999');

        
        //Set Sizes de Anotações
        $anotacao->setSize('100%');
        $periodo_gozo_ferias->setSize('100%');
        $exerc_funcao_grat_com->setSize('100%');
        $afastado->setSize('100%');
        
        $anotacao->forceUpperCase();
        $periodo_gozo_ferias->forceUpperCase();
        $exerc_funcao_grat_com->forceUpperCase();
        $afastado->forceUpperCase();
        
        if (!empty($id))
        {
            $id->setEditable(FALSE);
            $nome->setEditable(FALSE);
            $cpf->setEditable(FALSE);
            $insc->setEditable(FALSE);
            $cargo->setEditable(FALSE);
            $funcao->setEditable(FALSE);
            $unidade_1->setEditable(FALSE);
            $unidade_2->setEditable(FALSE);
            $unidade_folha->setEditable(FALSE);

            $pessoa_id->setEditable(FALSE);
            $cargo_id->setEditable(FALSE);
            $especialidade_id->setEditable(FALSE);
            $situacao_folha_id->setEditable(FALSE);
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
        $this->fieldlist->addField( '<b>Observação</b>', $observacao);

        $this->form->addField($tipo);
        $this->form->addField($contato);
        $this->form->addField($responsavel);
        $this->form->addField($principal);
        $this->form->addField($observacao);
        
        $this->form->addContent( [''] );
        $this->form->addContent( [ TElement::tag('h5', '<b>Contatos</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );

        $this->form->addFields( [$this->fieldlist] );
        
        // Separador para a área que será da identificação do servidor
        $this->form->addContent( [''] );
        $this->form->addContent( [ TElement::tag('h5', '<b>Informações Complementares</b>', [ 'style'=>'background: whitesmoke; padding: 5px; border-radius: 5px; margin-top: 5px'] ) ] );
       
        //Anotação
        $row = $this->form->addFields(  [ new TLabel(''), $anotacao ]
                                        );
        $row->layout = ['col-sm-12']; 
        //-----------------------------------------------------------------------------------------------------

        
        //Botões do footer
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')),  'far:check-circle blue' );
        $this->form->addAction('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addAction( 'Cancelar',  new TAction(['NomeadosList', 'onReload']), 'fa:times red' );
        
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
                $object = new NomeadosView($key);
                $object->id = $key;
                $this->form->setData($object);
                //var_dump($object);

                // Censo Consulta
                
                $criteria = new TCriteria; 
                $criteria->add(new TFilter('aprovados_id', '=', $key)); 
                
                // load using repository
                $repository = new TRepository('CadUnicpeConcurso'); 
                $customers = $repository->load($criteria); 
                
                $vCenso = new stdClass;
                foreach ($customers as $customer) 
                { 
                    $vCenso->pessoa_id = $customer->pessoa_id;
                    $vCenso->cargo_id = $customer->cargo_id;
                    $vCenso->especialidade_id = $customer->especialidade_id;
                    $vCenso->funcao_comissionado_id = $customer->funcao_comissionado_id;
                    $vCenso->situacao_folha_id = $customer->situacao_folha_id;
                    $vCenso->vinculo_id = $customer->vinculo_id;
                    
                    
                    $vCenso->anotacao = $customer->anotacao;
                    $vCenso->periodo_gozo_ferias = $customer->periodo_gozo_ferias;
                    $vCenso->exerc_funcao_grat_com = $customer->exerc_funcao_grat_com;
                    $vCenso->afastado = $customer->afastado;
                    
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
                TForm::sendData( 'form_Identifica_unicpe_concurso', $vCenso, false, false );
                //var_dump($object);
               
                $items  = Contato::where('cpf', '=', $object->cpf)->load();
                
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
            $master = new CadUnicpe;
            $master->fromArray( $param);
            
            $master->db_folha_id = $id;
             
            $master->status = 'VALIDADO';
            CadUnicpe::where('db_folha_id', '=', $id)->delete();
            
            $master->system_user_id = TSession::getValue('userid'); //Pega a id do usuário logado para registrar no banco de dados
            $master->store(); // save master object
            
            //var_dump($id);
            // delete details            
            Contato::where('cpf', '=', str_replace(['.', '-'], ['', ''], $param['cpf']))->delete();
            
            if( !empty($param['list_tipo']) AND is_array($param['list_tipo']) )
            {
                foreach( $param['list_tipo'] as $row => $tipo)
                {
                    if (!empty($tipo))
                    {
                        $detail = new Contato;
                        $detail->cpf = str_replace(['.', '-'], ['', ''], $param['cpf']);
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
            TForm::sendData('form_Identifica_unicpe_concurso', $data);
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
                    
                    TForm::sendData('form_Identifica_unicpe_concurso', $data, false, true);
                }
                else
                {
                    $data->logradouro  = '';
                    $data->complemento = '';
                    $data->bairro      = '';
                    $data->estado   = '';
                    $data->cidade   = '';
                    
                    TForm::sendData('form_Identifica_unicpe_concurso', $data, false, true);
                }
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public static function onIdentifica($param)
    {
        try
        {               
            $pdf = new TPDFDesigner;
            //Primeira página
            $pdf->fromXml('app/output/concurso/identifica.pdf.xml');
            $key=$param['key']; // get the parameter $key
            
            //Conexões com o banco para carregar os nomes
            TTransaction::open('rh');
            
            
                        
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
            $pdf->fromXml('app/output/concurso/identifica.pdf.xml');

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
            $file = 'app/output/unicpe/local_coleta.pdf';

            if (!file_exists($file) OR is_writable($file))
            {
                $pdf->save($file);
                //parent::openFile($file);
                
                $window = TWindow::create('Identificação Funcional', 0.9, 0.91);
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
}