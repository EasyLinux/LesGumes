<?php
/*
class myMail extends PHPmailer 
{
  private $mail;

  function _construct($Cfg)
  {
    $this->mail = @parent::__construct();
    $this->mail->isSMTP();
  } 
}
*/
/*
$mail = new PHPmailer();

Après avoir collecté les informations nécessaires pour envoyer votre premier email, placez vos informations collectées comme indiquées sur l’exemple ci-dessous :

$mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP 
$mail->Host = 'mail.votredomaine.com'; // Spécifier le serveur SMTP
$mail->SMTPAuth = true; // Activer authentication SMTP
$mail->Username = 'user@votredomaine.com'; // Votre adresse email d'envoi
$mail->Password = 'secret'; // Le mot de passe de cette adresse email
$mail->SMTPSecure = 'ssl'; // Accepter SSL
$mail->Port = 465;

$mail->setFrom('from@example.com', 'Mailer'); // Personnaliser l'envoyeur
$mail->addAddress('To1@example.net', 'Karim User'); // Ajouter le destinataire
$mail->addAddress('To2@example.com'); 
$mail->addReplyTo('info@example.com', 'Information'); // L'adresse de réponse
$mail->addCC('cc@example.com');
$mail->addBCC('bcc@example.com');

$mail->addAttachment('/var/tmp/file.tar.gz'); // Ajouter un attachement
$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); 
$mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

$mail->Subject = 'Here is the subject';
$mail->Body = 'This is the HTML message body';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

Envoyer votre email comme suit :

if(!$mail->send()) {
echo 'Erreur, message non envoyé.';
echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
echo 'Le message a bien été envoyé !';
}
*/