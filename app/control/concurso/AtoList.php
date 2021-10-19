<?php
/**
 * AtoList Listing
 * @author  <your name here>
 */
class AtoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_AtoView');
        $this->form->setFormTitle('Lista de Atos de Nomeação de Concursados');
        

        // create the form fields
        $ato = new TDBUniqueSearch('ato', 'rh', 'Ato', 'ato', 'ato');
        $justificativa = new TDBCombo('justificativa', 'rh', 'JustificativaAto', 'id', 'nome');
        $dt_nomeacao = new TDate('dt_nomeacao');
        $dt_publicacao = new TDate('dt_publicacao');
        $ato->setMinLength(2);
        

        // add the fields
        $row = $this->form->addFields(  [ new TLabel('Ato'), $ato ],
                                        [ new TLabel('Justificativa'), $justificativa ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4'];

        $row = $this->form->addFields(  [ new TLabel('Data da Nomeação'), $dt_nomeacao ],
                                        [ new TLabel('Data da Publicação'), $dt_publicacao ]
                                        );
        $row->layout = ['col-sm-4', 'col-sm-4'];

        // set sizes
        $ato->setSize('100%');
        $justificativa->setSize('100%');
        $dt_nomeacao->setSize('100%');
        $dt_publicacao->setSize('100%');

        $dt_publicacao->setMask('dd/mm/yyyy');
        $dt_publicacao->setDatabaseMask('yyyy-mm-dd');

        $dt_nomeacao->setMask('dd/mm/yyyy');
        $dt_nomeacao->setDatabaseMask('yyyy-mm-dd');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink('Cadastrar', new TAction(['AtoForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_ato = new TDataGridColumn('ato', 'Ato', 'left');
        $column_justificativa = new TDataGridColumn('justificativa', 'Justificativa', 'left');
        $column_dt_nomeacao = new TDataGridColumn('dt_nomeacao', 'Data Nomeação', 'left');
        $column_dt_publicacao = new TDataGridColumn('dt_publicacao', 'Data Publicação', 'left');
        $column_obs = new TDataGridColumn('obs', 'Observação', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_ato);
        $this->datagrid->addColumn($column_justificativa);
        $this->datagrid->addColumn($column_dt_nomeacao);
        $this->datagrid->addColumn($column_dt_publicacao);
        $this->datagrid->addColumn($column_obs);

        //Formatar as colunas de datas no padrão
        $column_dt_publicacao->setTransformer( function($value) {
           return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });
        
        $column_dt_nomeacao->setTransformer( function($value) {
            return TDate::convertToMask($value, 'yyyy-mm-dd', 'dd/mm/yyyy');
        });

        $action1 = new TDataGridAction(['AtoForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        //$this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('rh'); // open a transaction with database
            $object = new AtoView($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue(__CLASS__.'_filter_ato',   NULL);
        TSession::setValue(__CLASS__.'_filter_justificativa',   NULL);
        TSession::setValue(__CLASS__.'_filter_dt_nomeacao',   NULL);
        TSession::setValue(__CLASS__.'_filter_dt_publicacao',   NULL);
        TSession::setValue(__CLASS__.'_filter_obs',   NULL);

        if (isset($data->ato) AND ($data->ato)) {
            $filter = new TFilter('ato', '=', $data->ato); // create the filter
            TSession::setValue(__CLASS__.'_filter_ato',   $filter); // stores the filter in the session
        }


        if (isset($data->justificativa) AND ($data->justificativa)) {
            $filter = new TFilter('justificativa_ato_id', 'like', "%{$data->justificativa}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_justificativa',   $filter); // stores the filter in the session
        }


        if (isset($data->dt_nomeacao) AND ($data->dt_nomeacao)) {
            $filter = new TFilter('dt_nomeacao', 'like', "%{$data->dt_nomeacao}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_dt_nomeacao',   $filter); // stores the filter in the session
        }


        if (isset($data->dt_publicacao) AND ($data->dt_publicacao)) {
            $filter = new TFilter('dt_publicacao', 'like', "%{$data->dt_publicacao}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_dt_publicacao',   $filter); // stores the filter in the session
        }


        if (isset($data->obs) AND ($data->obs)) {
            $filter = new TFilter('obs', 'like', "%{$data->obs}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_obs',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue(__CLASS__ . '_filter_data', $data);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'rh'
            TTransaction::open('rh');
            
            // creates a repository for AtoView
            $repository = new TRepository('AtoView');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue(__CLASS__.'_filter_ato')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_ato')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_justificativa')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_justificativa')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_dt_nomeacao')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_dt_nomeacao')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_dt_publicacao')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_dt_publicacao')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_obs')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_obs')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('rh'); // open a transaction with database
            $object = new AtoView($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
