<?php
class AnexGrid
{
    public $limite = 0;
    public $pagina = 0;
    public $columna = '';
    public $columna_orden = '';
    public $filtros = array();
    public $parametros = array();
    
    public function __CONSTRUCT()
    {
        /* Cantidad de registros por página */
        $this->limite = $_REQUEST['limite'];
        if(!is_numeric($this->limite)) return;
        
        /* Desde que número de fila va a paginar */
        $this->pagina = $_REQUEST['pagina'] - 1;
        if(!is_numeric($this->pagina)) return;
        
        if( $this->pagina > 0) $this->pagina = $this->pagina * $this->limite;
        
        /* Ordenamiento de las filas */
        $this->columna = $_REQUEST['columna'];
        $this->columna_orden = $_REQUEST['columna_orden'];
        
        /* Filtros */
        if(isset($_REQUEST['filtros']))
            $this->filtros = $_REQUEST['filtros'];
        
        /* Parametros adicionales */
        if(isset($_REQUEST['parametros']))
            $this->parametros = $_REQUEST['parametros'];
    }
    
    public function responde($data, $total)
    {
        return json_encode(array(
            'data' => $data,
            'total' => $total
        ));
    }
}