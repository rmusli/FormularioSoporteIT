<?

require_once('/home/rmuslim2/public_html/soporte/form/PHPMailer-master/class.phpmailer.php');
include_once('/home/rmuslim2/public_html/soporte/form/PHPMailer-master/class.smtp.php');


$nombreYApellido = $_POST['nombre']." ".$_POST['apellido'];
$tel = $_POST['telefono'];
$asunto = $_POST['asunto'];
$comentario = $_POST['comentario'];
$emailFrom = $_POST['email'];
$password = $_POST['password'];
$destino = $_POST['target'];
$emailMessage = "Nombre y Apellido:".$nombreYApellido."</br>".
                "Telefono:".$tel."</br>".
                "Comentario:".$comentario."</br>";
$adjuntos = array();

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP
$mail->IsHTML(true);	
		  //$mail->Host       = "localhost"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = 'ssl';                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = $emailTo;    // GMAIL username
$mail->Password   = $password;            // GMAIL password
$mail->From = $email;
$mail->FromName = $nombreYApellido;
//$mail->AddReplyTo('rmuslimovich@forestcar.com.ar', 'Roman Muslimovich');
$mail->Subject = $asunto;
$mail->MsgHTML($emailMessage);
$mail->AddAddress($destino);


foreach($_FILES['input-2']['name'] as $index => $fileName) {
    $filePath = $_FILES['input-2']['tmp_name'][$index];
    $adjunto[] = array($filePath, $fileName);              
}

foreach($adjuntos as $attachment) {
    $mail->AddAttachment($attachment[0], $attachment[1]);
}


 //$mail->CharSet="UTF-8";


if (!$mail->send()) {
    echo "Error envio: " . $mail->ErrorInfo;
} else {
    echo "Mensaje enviado!";
}	

