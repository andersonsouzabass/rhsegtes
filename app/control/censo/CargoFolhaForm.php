<?php
/**
 * SystemUserForm
 *
 * Anderson Lopes de Souza
 */
class CargoFolhaForm extends TWindow
{
    protected $form; // form
    protected $program_list;
    protected $fornecedor_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();

        parent::setSize( 0.5, NULL);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_CargoFolha');
        $this->form->setFormTitle( 'Criar Cargo Folha' );
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $nome->forceUpperCase();
        
        //Todo cadastro novo entra como ativo= sim
        $ativo = new TEntry('ativo');
        
        // define the sizes
        $id->setSize('100%');
        $nome->setSize('100%');
 
        // outros
        $id->setEditable(false);
       
        // master fields
        $row = $this->form->addFields(  [ new TLabel('id'), $id ],
                                        [ new TLabel('Cargo'), $nome ]
                                        );
        $row->layout = ['col-sm-2', 'col-sm-7']; 
        
        $row = $this->form->addFields(  
                                        );
        $row->layout = ['col-sm-4'];

        //Adicionando o parâmetro ativo para todos os registros
        $this->form->addFields([new TLabel('')], [$ativo]);        
        $ativo->setValue('sim');
        TQuickForm::hideField('form_CargoFolha', 'ativo');
       
        //Botões do footer
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')),  'far:check-circle blue' );
        $this->form->addAction('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addAction( 'Cancelar',  new TAction(['CargoFolhaList', 'onReload']), 'fa:times red' );

        //Botões do cabeçalho
        
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['CargoFolhaList', 'onReload']), 'fa:times red' );
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);
        parent::add($container);
    }

    /**
     * Save user data
     */
    public function onSave($param)
    {
        try
        {
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            $data = $this->form->getData();
            $this->form->setData($data);
            
            $object = new CargoFolha;
            $object->fromArray( (array) $data );
            $object->ativo = "sim";
            $object->store();
            //$object->clearParts();

            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_CargoFolha', $data);
            
            // close the transaction
            TTransaction::close();
            
            // shows the success message
            new TMessage('info', 'Registro Concluído!');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'permission'
                TTransaction::open('permission');
                
                // instantiates object System_user
                $object = new CargoFolha($key);
               
                // fill the form with the active record data
                $this->form->setData($object);
                                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
