<?
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('/home/rmuslim2/public_html/soporte/sistemas/PHPMailer-master/class.phpmailer.php');
include_once('/home/rmuslim2/public_html/soporte/sistemas/PHPMailer-master/class.smtp.php');


$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$tel = $_POST['telefono'];
$asunto = $_POST['asunto'];
$comentario = $_POST['comentario'];
$emailFrom = $_POST['email'];
$password = $_POST['password'];
$destino = $_POST['target'];
$emailMessage = "Nombre y Apellido:".$nombre." ".$apellido."<br>".
                "Telefono:".$tel."<br>".
                "Comentario:".$comentario."<br>";
$adjuntos = array();

if (isset($nombre) && isset($apellido) && isset($tel) && isset($asunto) && isset($comentario)) {

    //check if any of the inputs are empty
    if (empty($nombre) || empty($apellido) || empty($tel) || ($asunto == "Seleccionar") || empty($comentario)) {
        $data = array('success' => false, 'message' => 'Por favor complete el campo.');
        echo json_encode($data);
        exit;
    }

	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
	
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->IsHTML(true);	
	//$mail->Host       = "localhost"; // SMTP server
	$mail->SMTPDebug  = 0;                   // enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = 'ssl';                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	$mail->Username   = $emailFrom; //$emailFrom;    // GMAIL username
	$mail->Password   = $password; // $password;            // GMAIL password
	$mail->From = $email;
	$mail->FromName = $nombre ." ".$apellido;
	$mail->Subject = $asunto;
	$mail->MsgHTML($emailMessage);
	$mail->AddAddress($destino);
	
	// Adjuntos
	//foreach($_FILES['input-2']['name'] as $index => $fileName) {
	  // $filePath = $_FILES['input-2']['tmp_name'][$index];   
	  // $adjunto[] = array($filePath, $fileName);              
	//}
	
	//foreach($adjuntos as $attachment) {
	 //   $mail->AddAttachment($attachment[0], $attachment[1]);
	//}
	
	// Se envia el mail
	// Si hay error se codifica el error
	if(!$mail->send()) {
        $data = array('success' => false, 'message' => 'El mensaje no pudo ser enviado!. Error: ' . $mail->ErrorInfo);
        echo json_encode($data);
        exit;
    }

    // Envio correcto! 
    $data = array('success' => true, 'message' => 'Gracias! Nosotros hemos recibido su mensaje.');
    echo json_encode($data);



}else {
    
    // Excepcion: campo vacio
    $data = array('success' => false, 'message' => 'Por favor complete el campo solicitado!');
    echo json_encode($data);

}


?>
