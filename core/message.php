<?php
################################################################################
# @Name : /core/message.php
# @Desc : page to send mail
# @call : /core/ticket.php 
# @parameters : $from, $to, $message, $object
# @Author : Flox
# @Create : 21/11/2012
# @Update : 08/04/2014
# @Version : 3.0.8
################################################################################

// require("components/PHPMailer_v5.1/class.phpmailer.php"); 
// $mail = new PHPmailer();
// $mail->CharSet = 'UTF-8'; //ISO-8859-1 possible if characters problems
// $mail->IsSMTP(); //$mail->isSendMail(); works for 1&1
// $mail->Host = "$rparameters[mail_smtp]";
// $mail->SMTPAuth = $rparameters['mail_auth'];
// if ($rparameters['debug']==1) $mail->SMTPDebug = 2;
// if ($rparameters['mail_secure']!=0) $mail->SMTPSecure = $rparameters['mail_secure'];
// if ($rparameters['mail_port']!=25) $mail->Port = $rparameters['mail_port'];
// $mail->Username = "$rparameters[mail_username]";
// $mail->Password = "$rparameters[mail_password]";
// $mail->IsHTML(true); // Envoi en html
// $mail->From = "$from";
// $mail->FromName = "$from";
// $mail->AddAddress("$to");
// $mail->AddReplyTo("$from");
// $mail->Subject = "$object";
// $mail->Body = "$message";
// if (!$mail->Send())
// {
	// echo '<div id="erreur"><img src="./images/access.png" alt="erreur" style="border-style: none" alt="img" />';
	// echo $mail->ErrorInfo;
	// echo '</div>';
// }
// $mail->SmtpClose();

	include("components/PHPMailer_v5.1/class.phpmailer.php"); 
	$mail = new PHPmailer();
	$mail->CharSet = 'UTF-8'; //ISO-8859-1 possible if characters problems
	$mail->IsSMTP(); //$mail->isSendMail(); works for 1&1
	if($rparameters['mail_secure']=='SSL') 
	{$mail->Host = "ssl://$rparameters[mail_smtp]";} 
	elseif($rparameters['mail_secure']=='TLS') 
	{$mail->Host = "tls://$rparameters[mail_smtp]";} 
	else 
	{$mail->Host = "$rparameters[mail_smtp]";}
	$mail->SMTPAuth = $rparameters['mail_auth'];
	if ($rparameters['debug']==1) $mail->SMTPDebug = 2;
	if ($rparameters['mail_secure']!=0) $mail->SMTPSecure = $rparameters['mail_secure'];
	if ($rparameters['mail_port']!=25) $mail->Port = $rparameters['mail_port'];
	$mail->Username = "$rparameters[mail_username]";
	$mail->Password = "$rparameters[mail_password]";
	$mail->IsHTML(true); 
	$mail->From = $from;
	$mail->FromName = "$rparameters[mail_from_name]";
	$mail->AddAddress($to);
	$mail->AddReplyTo($from);
	$mail->Subject = "$object";
	$mail->Body = "$message";
	if (!$mail->Send()){
    	echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> <b>Message non envoyé, vérifier la configuration de votre serveur de messagerie.</b> (';
        	echo $mail->ErrorInfo;
    	echo ')</center></div>';
	}
	else {
		echo '<div class="alert alert-block alert-success"><center><i class="icon-envelope green"></i> Message envoyé.</center></div>';
		//redirect
		echo "
		<SCRIPT LANGUAGE='JavaScript'>
		<!--
		function redirect()
		{
		window.location='./index.php?page=dashboard&&state=$_GET[state]&userid=$_GET[userid]'
		}
		setTimeout('redirect()',$rparameters[time_display_msg]);
		-->
		</SCRIPT>
		";
	}
	$mail->SmtpClose();
?>