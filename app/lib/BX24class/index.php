<?php
    require_once  './../../config.php';
    require_once  'bx24class.php';
    
    $bx24 = new bx24class();
    
    $bx24->route();
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>bx24</title>
    </head>
    <body>
        <?=$bx24->getContent()?>
        <?php var_dump($bx24->getCrmProductId(433));?>
    </body>
</html>
