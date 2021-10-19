<?php
/**
 * 
 */
class GestaoPessoaDashboard extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        
        try
        {
            $html = new THtmlRenderer('app/resources/gestao_pessoas/gestao_pessoa_dashboard.html');
            
            TTransaction::open('permission');
            $indicator1 = new THtmlRenderer('app/resources/info-box.html');
            $indicator2 = new THtmlRenderer('app/resources/info-box.html');
            $indicator3 = new THtmlRenderer('app/resources/info-box.html');
            $indicator4 = new THtmlRenderer('app/resources/info-box.html');
            
            $indicator1->enableSection('main', ['title' => 'Pessoas',    'icon' => 'user',       'background' => 'orange', 'value' => Servidor::count()]);
            $indicator2->enableSection('main', ['title' => 'Matrículas',   'icon' => 'users',      'background' => 'blue',   'value' => ServidorVinculo::count()]);
            /*
            $indicator3->enableSection('main', ['title' => 'Unidades',    'icon' => 'university', 'background' => 'purple', 'value' => Pessoa::count()]);
            $indicator4->enableSection('main', ['title' => _t('Programs'), 'icon' => 'code',       'background' => 'green',  'value' => SystemProgram::count()]);
            */

            $chart1 = new THtmlRenderer('app/resources/google_bar_chart.html');
            $data1 = [];
            $data1[] = [ 'Unidade', 'Profissionais' ];
            
            $stats1 = ServidorVinculo::groupBy('unidade_id')->countBy('servidor_id', 'count');
            if ($stats1)
            {
                foreach ($stats1 as $row)
                {
                    $data1[] = [ Pessoa::find($row->unidade_id)->nome, (int) $row->count];
                }
            }
            
            // replace the main section variables
            $chart1->enableSection('main', ['data'   => json_encode($data1),
                                            'width'  => '100%',
                                            'height'  => '500px',
                                            'title'  => 'Prossionais por Unidade',
                                            'ytitle' => 'Unidades', 
                                            'xtitle' => _t('Count'),
                                            'uniqid' => uniqid()]);


            
            $chart2 = new THtmlRenderer('app/resources/google_pie_chart.html');            
            $data2 = [];
            $data2[] = [ 'Vínculo', 'Profissionais' ];
            
            $stats2 = ServidorVinculo::groupBy('vinculo_id')->countBy('servidor_id', 'count');
            
            if ($stats2)
            {
                foreach ($stats2 as $row)
                {
                    $data2[] = [ Vinculo::find($row->vinculo_id)->nome, (int) $row->count];
                }
            }
            // replace the main section variables
            $chart2->enableSection('main', ['data'   => json_encode($data2),
                                            'width'  => '100%',
                                            'height'  => '500px',
                                            'title'  => 'Profissionais por Vínculo',
                                            'ytitle' => 'Vínculo', 
                                            'xtitle' => _t('Count'),
                                            'uniqid' => uniqid()]);


            $chart3 = new THtmlRenderer('app/resources/google_bar_chart.html');
            $data3 = [];
            $data3[] = [ 'Cargo', 'Profissionais' ];
            
            $stats3 = ServidorVinculo::groupBy('cargo_id')->countBy('servidor_id', 'count');
            if ($stats3)
            {
                foreach ($stats3 as $row)
                {
                    $data3[] = [ Cargo::find($row->cargo_id)->nome, (int) $row->count];
                }
            }
            
            // replace the main section variables
            $chart3->enableSection('main', ['data'   => json_encode($data3),
                                            'width'  => '100%',
                                            'height'  => '500px',
                                            'title'  => 'Prossionais por Cargo',
                                            'ytitle' => 'Cargos', 
                                            'xtitle' => _t('Count'),
                                            'uniqid' => uniqid()]);

            $chart4 = new THtmlRenderer('app/resources/google_pie_chart.html');            
            $data4 = [];
            $data4[] = [ 'Cargo', 'Profissionais' ];
            
            $stats4 = ServidorVinculo::groupBy('cargo_id')->countBy('servidor_id', 'count');
            
            if ($stats4)
            {
                foreach ($stats4 as $row)
                {
                    $data4[] = [ Cargo::find($row->cargo_id)->nome, (int) $row->count];
                }
            }
            // replace the main section variables
            $chart4->enableSection('main', ['data'   => json_encode($data4),
                                            'width'  => '100%',
                                            'height'  => '500px',
                                            'title'  => '% Profissionais por Cargo',
                                            'ytitle' => 'Cargo', 
                                            'xtitle' => _t('Count'),
                                            'uniqid' => uniqid()]);
            
            $html->enableSection('main', ['indicator1' => $indicator1,
                                          'indicator2' => $indicator2,
                                          'indicator3' => $indicator3,
                                          'indicator4' => $indicator4,
                                          'chart1'     => $chart1,
                                          'chart2'     => $chart2,
                                          'chart3'     => $chart3,
                                          'chart4'     => $chart4] );
            
            $container = new TVBox;
            $container->style = 'width: 100%';
            $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $container->add($html);
            
            parent::add($container);
            TTransaction::close();
        }
        catch (Exception $e)
        {
            parent::add($e->getMessage());
        }
    }
}
