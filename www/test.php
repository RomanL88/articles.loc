<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <pre>



    <?

     var_dump(ini_get("disable_functions"));
     ini_set("disable_functions",'var_dump');
     var_dump(ini_get("disable_functions"));

    ?>



    
    </pre>
</body>

</html>