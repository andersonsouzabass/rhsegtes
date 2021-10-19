<?php
/**
 * RelatorioLogReport Report
 * @author  <your name here>
 */
class RelatorioLogReport extends TPage
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_RelatorioLog_report');
        $this->form->setFormTitle('RelatorioLog Report');
        

        // create the form fields
        $id = new TEntry('id');
        $logdate = new TDate('logdate');
        $login = new TEntry('login');
        $tablename = new TEntry('tablename');
        $primarykey = new TEntry('primarykey');
        $pkvalue = new TEntry('pkvalue');
        $operation = new TEntry('operation');
        $columnname = new TEntry('columnname');
        $oldvalue = new TEntry('oldvalue');
        $newvalue = new TEntry('newvalue');
        $class_name = new TEntry('class_name');
        $output_type = new TRadioGroup('output_type');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Data do Registro') ], [ $logdate ] );
        $this->form->addFields( [ new TLabel('Login') ], [ $login ] );
        $this->form->addFields( [ new TLabel('Tablename') ], [ $tablename ] );
        $this->form->addFields( [ new TLabel('Primarykey') ], [ $primarykey ] );
        $this->form->addFields( [ new TLabel('Pkvalue') ], [ $pkvalue ] );
        $this->form->addFields( [ new TLabel('Operation') ], [ $operation ] );
        $this->form->addFields( [ new TLabel('Columnname') ], [ $columnname ] );
        $this->form->addFields( [ new TLabel('Oldvalue') ], [ $oldvalue ] );
        $this->form->addFields( [ new TLabel('Newvalue') ], [ $newvalue ] );
        $this->form->addFields( [ new TLabel('Class Name') ], [ $class_name ] );
        $this->form->addFields( [ new TLabel('Output') ], [ $output_type ] );

        $output_type->addValidation('Output', new TRequiredValidator);


        // set sizes
        $id->setSize('100%');
        $logdate->setSize('100%');
        $login->setSize('100%');
        $tablename->setSize('100%');
        $primarykey->setSize('100%');
        $pkvalue->setSize('100%');
        $operation->setSize('100%');
        $columnname->setSize('100%');
        $oldvalue->setSize('100%');
        $newvalue->setSize('100%');
        $class_name->setSize('100%');
        $output_type->setSize('100%');


        
        $output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'RTF', 'xls' => 'XLS'));
        $output_type->setLayout('horizontal');
        $output_type->setUseButton();
        $output_type->setValue('pdf');
        $output_type->setSize(70);
        
        // add the action button
        $btn = $this->form->addAction(_t('Generate'), new TAction(array($this, 'onGenerate')), 'fa:cog');
        $btn->class = 'btn btn-sm btn-primary';
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }
    
    /**
     * Generate the report
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'rh'
            TTransaction::open('rh');
            
            // get the form data into an active record
            $data = $this->form->getData();
            
            $this->form->validate();
            
            $repository = new TRepository('RelatorioLog');
            $criteria   = new TCriteria;
            
            if ($data->id)
            {
                $criteria->add(new TFilter('id', '=', "{$data->id}"));
            }
            if ($data->logdate)
            {
                $criteria->add(new TFilter('logdate', 'like', "%{$data->logdate}%"));
            }
            if ($data->login)
            {
                $criteria->add(new TFilter('login', 'like', "%{$data->login}%"));
            }
            if ($data->tablename)
            {
                $criteria->add(new TFilter('tablename', 'like', "%{$data->tablename}%"));
            }
            if ($data->primarykey)
            {
                $criteria->add(new TFilter('primarykey', 'like', "%{$data->primarykey}%"));
            }
            if ($data->pkvalue)
            {
                $criteria->add(new TFilter('pkvalue', 'like', "%{$data->pkvalue}%"));
            }
            if ($data->operation)
            {
                $criteria->add(new TFilter('operation', 'like', "%{$data->operation}%"));
            }
            if ($data->columnname)
            {
                $criteria->add(new TFilter('columnname', 'like', "%{$data->columnname}%"));
            }
            if ($data->oldvalue)
            {
                $criteria->add(new TFilter('oldvalue', 'like', "%{$data->oldvalue}%"));
            }
            if ($data->newvalue)
            {
                $criteria->add(new TFilter('newvalue', 'like', "%{$data->newvalue}%"));
            }
            if ($data->class_name)
            {
                $criteria->add(new TFilter('class_name', 'like', "%{$data->class_name}%"));
            }

           
            $objects = $repository->load($criteria, FALSE);
            $format  = $data->output_type;
            
            if ($objects)
            {
                $widths = array(100,50,100,100,100,100,100,100,100,100,100);
                
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'rtf':
                        $tr = new TTableWriterRTF($widths);
                        break;
                }
                
                // create the document styles
                $tr->addStyle('title', 'Arial', '10', 'B',   '#ffffff', '#9898EA');
                $tr->addStyle('datap', 'Arial', '10', '',    '#000000', '#EEEEEE');
                $tr->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff');
                $tr->addStyle('header', 'Arial', '16', '',   '#ffffff', '#494D90');
                $tr->addStyle('footer', 'Times', '10', 'I',  '#000000', '#B1B1EA');
                
                // add a header row
                $tr->addRow();
                $tr->addCell('RelatorioLog', 'center', 'header', 11);
                
                // add titles row
                $tr->addRow();
                $tr->addCell('Id', 'right', 'title');
                $tr->addCell('Logdate', 'left', 'title');
                $tr->addCell('Login', 'left', 'title');
                $tr->addCell('Tablename', 'left', 'title');
                $tr->addCell('Primarykey', 'left', 'title');
                $tr->addCell('Pkvalue', 'left', 'title');
                $tr->addCell('Operation', 'left', 'title');
                $tr->addCell('Columnname', 'left', 'title');
                $tr->addCell('Oldvalue', 'left', 'title');
                $tr->addCell('Newvalue', 'left', 'title');
                $tr->addCell('Class Name', 'left', 'title');

                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objects as $object)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($object->id, 'right', $style);
                    $tr->addCell($object->logdate, 'left', $style);
                    $tr->addCell($object->login, 'left', $style);
                    $tr->addCell($object->tablename, 'left', $style);
                    $tr->addCell($object->primarykey, 'left', $style);
                    $tr->addCell($object->pkvalue, 'left', $style);
                    $tr->addCell($object->operation, 'left', $style);
                    $tr->addCell($object->columnname, 'left', $style);
                    $tr->addCell($object->oldvalue, 'left', $style);
                    $tr->addCell($object->newvalue, 'left', $style);
                    $tr->addCell($object->class_name, 'left', $style);

                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 11);
                
                // stores the file
                if (!file_exists("app/output/RelatorioLog.{$format}") OR is_writable("app/output/RelatorioLog.{$format}"))
                {
                    $tr->save("app/output/RelatorioLog.{$format}");
                }
                else
                {
                    throw new Exception(_t('Permission denied') . ': ' . "app/output/RelatorioLog.{$format}");
                }
                
                // open the report file
                parent::openFile("app/output/RelatorioLog.{$format}");
                
                // shows the success message
                new TMessage('info', 'Report generated. Please, enable popups.');
            }
            else
            {
                new TMessage('error', 'No records found');
            }
    
            // fill the form with the active record data
            $this->form->setData($data);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
