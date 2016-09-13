<?php


	// funcion para verificar el mail


	function mailOK($email){ 


	    $mail_correcto = 0; 


		$email = trim($email) ;


	    //compruebo unas cosas primeras 


	    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 


	       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 


	          //miro si tiene caracter . 


	          if (substr_count($email,".")>= 1){ 


	             //obtengo la terminacion del dominio 


	             $term_dom = substr(strrchr ($email, '.'),1); 


	             //compruebo que la terminación del dominio sea correcta 


	             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 


	                //compruebo que lo de antes del dominio sea correcto 


	                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 


	                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 


	                if ($caracter_ult != "@" && $caracter_ult != "."){ 


	                   $mail_correcto = 1; 


	                } 


	             } 


	          } 


	       } 


	    }


		


		$ret = ( $mail_correcto ) ? 1 : 0 ;


		


		return $ret ;


	}


	


	//tomo los valores pasados...


	foreach ($_POST as $key => $value) {


		$valores[ $key ] = urlencode($value) ;


		$errores[ $key ] = "" ;


	}


	// Datos obligatorios del formulario


	$emailTo =  $_POST['target'];

	$email = $_POST['email'];

	$emailSubject = $_POST['asunto'];

	$userName = $_POST['nombre'];
	
	$emailURL = $_POST['redirect'];





	// Campos que no se enviaran en el mensaje


	$excluido = array();


	$excluido[] = 'submit';


	$excluido[] = 'reset';





	$excluido[] = 'target';


	$excluido[] = 'redirect';


	$excluido[] = 'subject';


	$excluido[] = 'required';





	// Campos obligatorios del formulario


	$aux = explode(',', $_POST['required']);





	foreach ($aux as $key => $value) {


		$requerido[$value] = $value;


	}





	// Verifico si se cargaron los campos obligatorios


	$msgError = '';


	$error = 0;


	$email2valid=array();





	foreach ($_POST as $key => $value) {


		if (isset($requerido[$key]))


		{


			if ($_POST[$key] == "")


			{


				$msgError .= ' '.(ucfirst($key = str_replace('_',' ', $requerido[$key]))).'<br>';


				$error = 1;


				$errores[$key] = "Campo Obligatorio";


			}


			if( substr($key, 0, 5) == "email" ) {


				$email2valid[] = $key ;


			}


		}


	}





	// Verfifico el email


	foreach($email2valid as $key => $val )


	{


		if( $_POST[$val] and ! mailOK($_POST["$val"]) )


		{


			$msgError .= '<br>';


			$msgError .= 'email incorrecto';


			$error = 1;


			$errores[$val] = $errores[$val] . "Formato incorrecto";


		}


	}


	


	$errorMsg = "?ValidationError=".$error;


	foreach( $valores as $key => $val ) {


		$errorMsg.= ($val) ? "&$key=$val" : "";


	}


	


	if( $error == 1 ) {


		foreach( $errores as $key => $val ) {


			$spanName = "err_" . $key ;


			$errorMsg.= ($val) ? "&".$spanName."=".$val : "" ;


		}


	}


	//


	$position = strpos( $_SERVER[HTTP_REFERER], "?" ) ;


	if( $position === FALSE ) {


		$redirectURL = $_SERVER[HTTP_REFERER];


	}


	else{


		$redirectURL = substr( $_SERVER[HTTP_REFERER], 0, $position);


	}


	


	$redirectURL = $redirectURL . $errorMsg ;


	


	if( $error == 0 ) {


		// mail del formulario


		$emailFrom = 'From: Consulta<' .$_POST['email']. ">\r\n" .


			'Reply-To: ' .$_POST['email']. "\r\n" .


			'X-Mailer: PHP/' . phpversion();





		// Armo el mensaje


		$emailMessage = '';


		foreach ($_POST as $key => $value) { 


			if (!in_array($key, $excluido)) {


				$emailMessage .= (ucfirst($key = str_replace('_',' ', $key))) . ': ' . $value . "\n";


			}


		}


		//@mail($emailTo, $emailSubject, $emailMessage, $emailFrom);

        
		require_once('/home/rmuslim2/public_html/soporte/sistemas/PHPMailer-master/PHPMailer-master/class.phpmailer.php');
		include_once('/home/rmuslim2/public_html/soporte/sistemas/PHPMailer-master/PHPMailer-master/class.smtp.php'); // optional, gets called from within class.phpmailer.php if not already loaded

		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->IsHTML(true);
		//try {
		  //$mail->Host       = "localhost"; // SMTP server
		  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		  $mail->SMTPAuth   = true;                  // enable SMTP authentication
		  $mail->SMTPSecure = 'ssl';                 // sets the prefix to the servier
		  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
		  $mail->Username   = $emailTo;    // GMAIL username
		  $mail->Password   = "FermatWiles1994";            // GMAIL password
		  $mail->From = $email;
		  //$mail->FromName = $userName;
		  //$mail->AddReplyTo('rmuslimovich@forestcar.com.ar', 'Roman Muslimovich');
		  $mail->Subject = $emailSubject;
		  $mail->MsgHTML($emailMessage);
		  //$mail->AltBody($emailMessage);
		  $mail->AddAddress('soporte.it@forestcar.com.ar');
		  //$mail->CharSet="UTF-8";
		  $mail->Send();
		/*if (!$mail->send()) {
		    echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		    echo "Message sent!";
		}	*/
	         //header('Location: '.$emailURL);
		$redirectURL.= "&wrtMsg=1";
		/****************************************************************************************************************************************************/
		
		
		//header('Location: '.$emailURL);


		

	}

	header('Location: http://forestcar.com.ar/soporte/sistemas');


	//header('Location: '.$emailURL);


?>