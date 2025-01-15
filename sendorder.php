<?php

include_once 'dbcon.php';
// получим данные с элементов формы
//              name
$courses = mysqli_real_escape_string($conn,$_POST['courses']);
$name = mysqli_real_escape_string($conn,$_POST['name']);
$mail = mysqli_real_escape_string($conn,$_POST['mail']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);

// обработаем данные

$courses = htmlspecialchars($courses);
$name = htmlspecialchars($name);
$mail = htmlspecialchars($mail);
$phone = htmlspecialchars($phone);

// декодирует url адресацию

$courses = urldecode($courses);
$name = urldecode($name);
$mail = urldecode($mail);
$phone = urldecode($phone);

// удаляет пробелы с обих сторон

$courses = trim($courses);
$name = trim($name);
$mail = trim($mail);
$phone = trim($phone);

// отправляем данные

$url='https://www.google.com/recaptcha/api/siteverify';
$data = [
'secret' =>'твой_код',
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

    if($result->success)
    {
        //echo"\n success \n";
        if(    
            mail("почта",
            "Новое письмо с сайта",
            "Курс: " .$courses."\n".
            "Имя: " .$name."\n".
            "Почта: " .$mail."\n".
            "Телефон: " .$phone,
            "From:MySite@mydomain.ru \r\n")
        )
        {
            if($conn->connect_error)
            {
                //die("Connect failed: " .$conn->connect_error);
            }
            else
            {
                $mysql="INSERT INTO `client` (`nickname`,`course`,`mail`,`phone`,`date_msg`) VALUES('$name','$courses','$mail','$phone',NOW())"; 
                mysqli_query($conn,$mysql);
            }
            
            header('Location:переход_на_страницу');
            exit;
        }
        else
        {
            //echo("Ошибка! Проверьте данные");
        }
        
    }
    else{      
        echo "<script>window.history.back()</script>";  // return page back
        echo "<script>document.getElementById(checks').style.visibility = 'visible'</script>";
    }
?>