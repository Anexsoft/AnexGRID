<?php
require_once 'anexgrid.php';

try
{
    $anexGrid = new AnexGrid();
    
    /* Si es que hay filtro, tenemos que crear un WHERE dinámico */
    $wh = "id > 0";
    
    foreach($anexGrid->filtros as $f)
    {
        if($f['columna'] == 'Nombre') $wh .= " AND CONCAT(Nombre, ' ', Apellido) LIKE '%" . addslashes ($f['valor']) . "%'";
        if($f['columna'] == 'Correo') $wh .= " AND Correo LIKE '%" . addslashes ($f['valor']) . "%'";
        if($f['columna'] == 'Sexo' && $f['valor'] != '') $wh .= " AND Sexo = '" . addslashes ($f['valor']) . "'";
        if($f['columna'] == 'Profesion_id' && $f['valor'] != '') $wh .= " AND Profesion_id = '" . addslashes ($f['valor']) . "'";
    }
    
    /* Nos conectamos a la base de datos */
    $db = new PDO("mysql:dbname=test;host=localhost;charset=utf8", "root", "" );
    
    /* Nuestra consulta dinámica */
    $registros = $db->query("
        SELECT * FROM empleado
        WHERE $wh ORDER BY $anexGrid->columna $anexGrid->columna_orden
        LIMIT $anexGrid->pagina,$anexGrid->limite")->fetchAll(PDO::FETCH_ASSOC
     );
    
    $total = $db->query("
        SELECT COUNT(*) Total
        FROM empleado
        WHERE $wh
    ")->fetchObject()->Total;
    
    foreach($registros as $k => $r)
    {
        $profesion = $db->query("SELECT * FROM profesion p WHERE p.id = " . $r['Profesion_id'])
                        ->fetch(PDO::FETCH_ASSOC);
        
        $registros[$k]['Profesion'] = $profesion;
    }

    header('Content-type: application/json');
    print_r($anexGrid->responde($registros, $total));
}
catch(PDOException $e)
{
    echo $e->getMessage();
}