<?php
function sendMail($cust_email,$qid){
	include('../mail/smtp/PHPMailerAutoload.php');
	$email = "duuta1999@gmail.com";

	$html='test';

	echo smtp_mailer($email,'Invoice From AIM Digitalise',$html);
	function smtp_mailer($to,$subject, $msg){
		$mail = new PHPMailer(); 
		// $mail->SMTPDebug  = 3;
		$mail->IsSMTP(); 
		$mail->SMTPAuth = true; 
		$mail->SMTPSecure = 'ssl'; 
		$mail->Host = "smtp.hostinger.com";
		$mail->Port = 465; 
		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';
		$mail->Username = "sales@aimdigitalise.in";
		$mail->Password = "Sales@123";
		$mail->SetFrom("sales@aimdigitalise.in");
		$mail->addCC('sales@aimdigitalise.in');
		$mail->addCC($_GET['cc']);
		$mail->addAttachment("/pdf-file.pdf");
		$mail->Subject = $subject;
		$mail->Body =$msg;
		$mail->AddAddress($to);
		$mail->SMTPOptions=array('ssl'=>array(
			'verify_peer'=>false,
			'verify_peer_name'=>false,
			'allow_self_signed'=>false
		));
		if(!$mail->Send()){
	// 		echo $mail->ErrorInfo;
	        // return 'Not Send';
		}else{
	// 		return 'Sent';
		}
	}
}

?>