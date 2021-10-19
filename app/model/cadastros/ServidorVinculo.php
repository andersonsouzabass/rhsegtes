<?php
/**
 * Vinculos do servidor Active Record
 * @author  Anderson Lopes Souza (81) 997032438
 */
class ServidorVinculo extends TRecord
{
    const TABLENAME = 'servidor_vinculo';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}

    private $contatos;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('servidor_id'); //ok
        parent::addAttribute('matricula'); //ok
        parent::addAttribute('conselho_desc');
        parent::addAttribute('conselho_num');
        parent::addAttribute('vinculo_id');
        parent::addAttribute('simbolo_id');
        parent::addAttribute('cargo_id');
        parent::addAttribute('funcao_id');
        parent::addAttribute('especialidade_id');
        parent::addAttribute('especialidadefolha_id');
        parent::addAttribute('unidade_id');
        parent::addAttribute('regime_trabalho_id');
        parent::addAttribute('ativo');
        parent::addAttribute('dt_admissao');

        parent::addAttribute('anotacao');
        parent::addAttribute('dt_doe');
        parent::addAttribute('dt_limite');
        parent::addAttribute('instrumento_legal');
        parent::addAttribute('situacao');
        parent::addAttribute('tipo_cessao');
        parent::addAttribute('forma_cessao');
        parent::addAttribute('matricula_origem');
        parent::addAttribute('orgao_cedente_destino_id');
    }
   
    
    public function get_servidor()
    {
        return Servidor::find($this->servidor_id);
    }


}
