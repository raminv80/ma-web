<?php
require_once ("Mail.php");
require_once ("AttachmentMail.php");
require_once ("Multipart.php");

class email{

	var $to;
	var $bcc;
	var $cc;
	var $fromName 				= "Website";
	var $fromEmail 				= "noreply@example.com";
	var $subject;
	var $body;
	var $mail;
	var $path;
	var $attachementFile;

	function sendEmail(){
		try{
			 $this->path = getcwd();
			if(!empty($this->to) && !empty($this->subject) && !empty($this->body) ){
				$mail = new AttachmentMail($this->to, $this->subject, $this->fromName, $this->fromEmail);

				if(!empty($this->attachementFile) && file_exists($this->path.$this->attachementFile)){
					$mp1 = new Multipart($this->path.$this->attachementFile);
					$mail->addAttachment($mp1);
				}

				if(!empty($this->cc)){
					$mail->addCC($this->cc);
				}

				if(!empty($this->bcc)){
					$mail->addBCC($this->bcc);
				}

				$mail->setHtml($this->body);

				return ($mail->send()	?	true:false);
			}
		}catch(Exception $e){
			return false;
		}
	}

}

?>