<?php
/**
 * GrupoForm
 *
 * Anderson SOuza
 */
class SituacaoForm extends TWindow
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
     
        parent::setSize( 0.6, null);
        parent::removePadding();
        parent::removeTitleBar();
        parent::disableEscape();
        
        //parent::setTargetContainer('adianti_right_panel');
        $this->setAfterSaveAction( new TAction(['SituacaoList', 'onReload'], ['register_state' => 'true']) );
        
        $this->setDatabase('rh');              // defines the database
        $this->setActiveRecord('SituacaoFolha');     // defines the active record
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_situacao');
        $this->form->setFormTitle('Criar Situação do Servidor');
        $this->form->setClientValidation(true);
        $this->form->setColumnClasses( 2, ['col-sm-5 col-lg-4', 'col-sm-7 col-lg-8'] );

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $ativo = new TEntry('ativo');
        $nome->forceUpperCase();

        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Situação') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Ativo') ], [ $ativo ] );
        
        $ativo->setValue('sim');
        $nome->addValidation('Nome', new TRequiredValidator);

        TQuickForm::hideField('form_situacao', 'ativo');


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');

        $id->setEditable(FALSE);
        
        // create the form actions
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'far:save' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addActionLink('Limpar', new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addActionLink('Cancelar', new TAction(array('SituacaoList','onReload')),  'fa:times red' );
        $this->form->addHeaderActionLink( 'Fechar',  new TAction(['SituacaoList', 'onReload']), 'fa:times red' );

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }
    
    /**
     * Close side panel
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
