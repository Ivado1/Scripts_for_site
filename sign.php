<?php
//echo '<pre>'+;
//var_dump($_POST);

//Проверка на пустоту

$url='https://www.google.com/recaptcha/api/siteverify';
$data = [
'secret' =>'pass_secret',
'response'=> $_POST['g-recaptcha-response']
];

$option=[
    'http'=>[
        'header'=>"Content-type: application/x-www-form-urlencoded\r\n",
        'method'=>'POST',
        'content'=>http_build_query($data),
    ]
    ];

    $context=stream_context_create($option);
    $result=file_get_contents($url,false,$context);
    $result=json_decode($result,false);
    //var_dump($result);

    if($result->success){
        
        //echo"\n success \n";
        header('Location:https://~.ru/order/index.html');
        exit;
    }
    else{
        //echo"\n it`s a bot \n";
    }
?>