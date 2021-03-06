<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link href="favicon.ico" rel="icon" type="image/x-icon" />
        <title>Интернет Магазин Коузи</title>
              
        <link rel="stylesheet" type="text/css" href="lib/css/bootstrap.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="lib/css/jquery-ui.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="lib/css/chosen.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/shop.css" media="screen" /> 
        
        <script src="lib/js/jquery.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery.mask.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery.autocompleter.js" type="text/javascript"></script>
         <script src="lib/js/chosen.jquery.js" type="text/javascript"></script>
        <script src="lib/js/bootstrap.min.js" type="text/javascript"></script> 
        <script src="lib/js/jquery.json.min.js" type="text/javascript"></script> 
    </head>
    <body>
        <div id="shop">
        </div>
                  
        <script src="js/shop.js" type="text/javascript"></script>
        <script type="text/javascript">
            KouziShop.url="/addon/shopdemo/";
            KouziShop.successUrl="/addon/shopdemo/";
            KouziShop.phone="8-800-333-81-98";
            KouziShop.init("#shop");            
        </script>                                
    </body>
</html>