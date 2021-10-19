<?php

class RelatorioConcurso extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        $object = new TElement('iframe');
        $object->width = '100%';
        $object->height = '600px';
        $object->src = "https://datastudio.google.com/embed/reporting/a5ae3fe3-ccbc-4522-9716-d758472aa15d/page/oT2OC";
        $object->frameborder = '0';
        parent::add( $object);
    }
}