<?php
/**
 * PessoaFormView
 *
 * @version    1.0
 * @package    erphouse
 * @subpackage control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ServidorFormView extends TPage
{
    protected $form; // form
    protected $detail_list_contratos;
    protected $detail_list_contas;
    
    /**
     * Page constructor
     */
    public function __construct($param)
    {
        parent::__construct();
       
        parent::setTargetContainer('adianti_right_panel');

        $this->form = new BootstrapFormBuilder('ServidorFormView');
        $this->form->setFormTitle('Identificação de Pessoa');
        $this->form->setColumnClasses(2, ['col-sm-3', 'col-sm-9']);
        
        $dropdown = new TDropDown('Opções', 'fa:th');
        //$dropdown->addAction(
        $dropdown->addAction( 'Imprimir', new TAction([$this, 'onPrint'], ['key'=>$param['key'], 'static' => '1']), 'far:file-pdf red');
        $dropdown->addAction( 'Gerar etiqueta', new TAction([$this, 'onGeraEtiqueta'], ['key'=>$param['key'], 'static' => '1']), 'far:envelope purple');
        $dropdown->addAction( 'Editar', new TAction(['PessoaForm', 'onEdit'],['key'=>$param['key']]), 'far:edit blue');
        $dropdown->addAction( 'Fechar', new TAction([$this, 'onClose']), 'fa:times red');
        
        $this->form->addHeaderWidget($dropdown);
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%'; 
        // $container->add(new TXMLBreadCrumb('menu.xml', 'PessoaList'));
        $container->add($this->form);

        parent::add($container);
    }
    
    /**
     * onEdit
     */
    public function onEdit($param)
    {
        try
        {
            TTransaction::open('gratifica');
            $master_object = new Servidor($param['key']);
            
            $label_id = new TLabel('Código:', '#333333', '12px', '');
            $label_nome = new TLabel('Nome:', '#333333', '12px', '');
            $label_cpf = new TLabel('CPF:', '#333333', '12px', '');
            $label_dt_nascimento = new TLabel('Data de Nascimento:', '#333333', '12px', '');
            $label_cidade = new TLabel('Local:', '#333333', '12px', '');
            $label_created_at = new TLabel('Criado em:', '#333333', '12px', '');
            $label_updated_at = new TLabel('Alterado em:', '#333333', '12px', '');
            
            $text_id  = new TTextDisplay($master_object->id, '#333333', '12px', '');
            $text_nome  = new TTextDisplay($master_object->nome, '#333333', '12px', '');
            $text_cpf  = new TTextDisplay($master_object->cpf, '#333333', '12px', '');
            $text_dt_nascimento = new TTextDisplay(TDate::convertToMask($master_object->dt_nascimento, 'yyyyy-mm-dd hh:ii:ss', 'dd/mm/yyyy hh:ii:ss'), '#333333', '12px', '');
            $link_maps = 'https://www.google.com/maps/place/' . $master_object->logradouro . ',' . 
                                                                $master_object->numero . ', ' .
                                                                $master_object->bairro . ', ' .
                                                                $master_object->cidade . '+' .
                                                                $master_object->estado;
            $text_cidade  = new THyperLink('<i class="fa fa-map-marker-alt"></i> Link para google maps', $link_maps, '#007bff', '12px', '');

            /*
            //Dados Pessoais
            $row = $this->form->addFields(  [ $label_id, $text_id ]               
                                            );
            $row->layout = ['col-sm-12'];

            $row = $this->form->addFields(  [ $label_nome, $text_nome ]             
                                            );
            $row->layout = ['col-sm-12'];

            $row = $this->form->addFields(  [ $label_cpf, $text_cpf  ]                                        
                                            );
            $row->layout = ['col-sm-12'];

            $row = $this->form->addFields(  [ $label_dt_nascimento, $text_dt_nascimento ]
                                            );
            $row->layout = ['col-sm-12'];

            $row = $this->form->addFields(  [ $label_cidade, $text_cidade  ]                                        
                                            );
            $row->layout = ['col-sm-12'];
            //Fim dos dados Pessoais
            */

            $this->form->addFields([$label_id],[$text_id]);
            $this->form->addFields([$label_nome],[$text_nome]);
            $this->form->addFields([$label_cpf],[$text_cpf]);
            $this->form->addFields([$label_dt_nascimento],[$text_dt_nascimento]);
            $this->form->addFields([$label_cidade],[$text_cidade]);
            
            $this->detail_list_vinculos = new BootstrapDatagridWrapper( new TDataGrid );
            $this->detail_list_vinculos->style = 'width:100%';
            $this->detail_list_vinculos->disableDefaultClick();
            
            $column_matricula = $this->detail_list_vinculos->addColumn( new TDataGridColumn('matricula', 'Matrícula', 'left') );
            $column_vinculo = $this->detail_list_vinculos->addColumn( new TDataGridColumn('vinculo', 'Vínculo', 'left') );
            $column_unidade    = $this->detail_list_vinculos->addColumn( new TDataGridColumn('unidade', 'Unidade', 'left') );
            $column_cargo = $this->detail_list_vinculos->addColumn( new TDataGridColumn('cargo', 'Nível', 'left') );
            $column_especialidade = $this->detail_list_vinculos->addColumn( new TDataGridColumn('especialidade', 'Cargo', 'left') );
            $column_admissao = $this->detail_list_vinculos->addColumn( new TDataGridColumn('dt_admissao', 'Admissão', 'left') );
            $column_ativo = $this->detail_list_vinculos->addColumn( new TDataGridColumn('ativo', 'Ativo', 'left') );
            
            $column_ativo->setTransformer( function ($value) {
                if ($value == 'SIM')
                {
                    $div = new TElement('span');
                    $div->class="label label-success";
                    $div->style="text-shadow:none; font-size:12px";
                    $div->add('SIM');
                    return $div;
                }
                else
                {
                    $div = new TElement('span');
                    $div->class="label label-danger";
                    $div->style="text-shadow:none; font-size:12px";
                    $div->add('NÃO');
                    return $div;
                }
            });
                        
            $column_admissao->setTransformer( function($value) {
                return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
            });
            
            
            //$action1 = new TDataGridAction(['ContratoForm', 'onEdit'], ['id'=>'{id}']);
            //$this->detail_list_vinculos->addAction($action1, _t('Edit'),   'far:edit blue');
            
            $this->detail_list_vinculos->createModel();
            
            $items = ViewVinculoServidor::where('s_id', '=', $master_object->id)->orderBy('id', 'desc')->load();
            $this->detail_list_vinculos->addItems($items);
            
            $panel = new TPanelGroup('Vínculos', '#f5f5f5');
            $panel->add($this->detail_list_vinculos)->style = 'overflow-x:auto';
            $this->form->addContent([$panel]);
            
            //Fim vínculos
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
   
    /**
     * Imprime a view
     */
    public function onPrint($param)
    {
        try
        {
            $this->onEdit($param);
            
            // string with HTML contents
            $html = clone $this->form;
            $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();
            
            // converts the HTML template into PDF
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($contents);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $file = 'app/output/pessoa.pdf';
            
            // write and open file
            file_put_contents($file, $dompdf->output());
            
            $window = TWindow::create('Export', 0.8, 0.8);
            $object = new TElement('object');
            $object->data  = $file.'?rndval='.uniqid();
            $object->type  = 'application/pdf';
            $object->style = "width: 100%; height:calc(100% - 10px)";
            $window->add($object);
            $window->show();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Gera etiqueta
     */
    public function onGeraEtiqueta($param)
    {
        try
        {
            $this->onEdit($param);
            
            TTransaction::open('gratifica');
            $pessoa = new Pessoa($param['key']);
            
            $replaces = $pessoa->toArray();
            $replaces['cidade'] = $pessoa->cidade;
            $replaces['estado'] = $pessoa->cidade->estado;
            
            // string with HTML contents
            $html = new THtmlRenderer('app/resources/mail-label.html');
            $html->enableSection('main', $replaces);
            $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();
            
            // converts the HTML template into PDF
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($contents);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $file = 'app/output/etiqueta.pdf';
            
            // write and open file
            file_put_contents($file, $dompdf->output());
            
            $window = TWindow::create('Export', 0.8, 0.8);
            $object = new TElement('object');
            $object->data  = $file.'?rndval='.uniqid();
            $object->type  = 'application/pdf';
            $object->style = "width: 100%; height:calc(100% - 10px)";
            $window->add($object);
            $window->show();
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Close side panel
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
