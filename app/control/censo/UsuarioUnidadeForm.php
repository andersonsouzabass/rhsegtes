<?php
/**
 * UsuarioUnidadeForm Form
 * @author  <your name here>
 */
class UsuarioUnidadeForm extends TWindow
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        parent::setSize( 0.6, NULL);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_UsuarioUnidade');
        $this->form->setFormTitle('Conceder Acesso para Unidade para o Censo');
        

        // create the form fields
        // = new TEntry('id');
        $system_user_id = new TEntry('system_user_id');
        $nome = new TEntry('nome');
        $folha_unidade_2_id = new TDBMultiSearch('folha_unidade_2_id', 'rh', 'Unidade2', 'id', 'nome');


        // add the fields
        $row = $this->form->addFields(  //[ new TLabel('ID Acesso'), $id ],
                                        [ new TLabel('ID Usuário'), $system_user_id ],
                                        [ new TLabel('Nome'), $nome ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-6'];
        
        $row = $this->form->addFields(  [ new TLabel('Unidades de Acesso'), $folha_unidade_2_id ]
                                        );
        $row->layout = ['col-sm-10'];

        // set sizes
        //$id->setSize('100%');
        $system_user_id->setSize('100%');
        $nome->setSize('100%');
        $folha_unidade_2_id->setSize('100%');


        if (!empty($system_user_id))
        {
            //$id->setEditable(FALSE);
            $system_user_id->setEditable(FALSE);
            $nome->setEditable(FALSE);
        }
        
        //Botões do footer
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')),  'far:check-circle blue' );
        $this->form->addAction('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addAction( 'Cancelar',  new TAction(['UsuarioUnidadeList', 'onReload']), 'fa:times red' );
        
        
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
            
            //$object = new UsuarioUnidade;  // create an empty object
            //$object->fromArray( (array) $data); // load the object with data
            //var_dump($param);
            
            // Deleta antes de incluir um novo para evitar duplicidae
            UsuarioUnidade::where('system_user_id', '=', $param['system_user_id'])->delete();
            
            //Registar os dados corretamente na tabela
            if( !empty($param['folha_unidade_2_id']) AND is_array($param['folha_unidade_2_id']) )
            {
                foreach( $param['folha_unidade_2_id'] as $row => $tipo)
                {
                    if (!empty($tipo))
                    {
                        $object = new UsuarioUnidade;
                        $object->system_user_id = $param['system_user_id'];
                        $object->folha_unidade_2_id = $param['folha_unidade_2_id'][$row];
                        $object->store();
                    }
                }
            }
            
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
                
                //Carrega os dados do usuário no form
                $criteria = new TCriteria; 
                $criteria->add(new TFilter('id', '=', $key)); 
                
                // Carregar dados do usuário
                $repository = new TRepository('SystemUser'); 
                $customers = $repository->load($criteria); 
                
                $vUsuario = new stdClass;
                foreach ($customers as $customer) 
                { 
                    $vUsuario->system_user_id = $customer->id;
                    $vUsuario->nome = $customer->name;
                }
                TForm::sendData( 'form_UsuarioUnidade', $vUsuario, false, false );
                //Fim dos dados do Usuário
                
                // Carregar dados das unidades de acesso
                $vAcessos = new SystemUser($key);
                $acessos_ids = array();
                foreach ($vAcessos->getUsuarioUnidade() as $acessos)
                {
                    $acessos_ids[] = $acessos->id;
                }
                $vAcessos->folha_unidade_2_id = $acessos_ids;
                
                // fill the form with the active record data
                $this->form->setData($vAcessos);
                //Fim dos dados da Unidade
                
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