<?php
function ProcessUpdateNewsStatus(){
	global $DBobject;
	$sql="UPDATE tbl_news SET news_published = 0 WHERE news_start_date IS NOT NULL AND DATE_FORMAT(news_start_date,'%Y-%m-%d') > DATE_FORMAT(now(),'%Y-%m-%d') OR DATE_FORMAT(news_end_date,'%Y-%m-%d') < DATE_FORMAT(now(),'%Y-%m-%d')";
	$res = $DBobject->executeSQL($sql);
	$sql = "UPDATE tbl_news SET news_published = 1 WHERE news_start_date IS NOT NULL AND DATE_FORMAT(news_start_date,'%Y-%m-%d') <= DATE_FORMAT(now(),'%Y-%m-%d') AND (news_end_date IS NULL OR DATE_FORMAT(news_end_date,'%Y-%m-%d') >= DATE_FORMAT(now(),'%Y-%m-%d))"; 
	$res = $DBobject->executeSQL($sql);
	if($res){
		return true;
	}
	return false;
}

function ProcessSaveRegistration($arr){
	global $DBobject;
	if(!empty($arr)){
		$sql='SELECT * from tbl_user where user_business_email = "'.$arr['user_business_email'].'" and user_deleted is null '; 
		$res = $DBobject->wrappedSqlGetSingle($sql);
		if(empty($res)){
			$password = getPass($arr['user_business_email'], $arr['user_password']);
			$sql="INSERT into tbl_user
				  (
				  user_name,
				  user_job_position,
				  user_business_name,
				  user_abn,
				  user_account_code,
				  user_business_address,
				  user_business_phone,
				  user_business_email,
				  user_youtube_email,
				  user_password
				  )
				  VALUES
				  (
				  '{$arr['user_name']}',
				  '{$arr['user_job_position']}',
				  '{$arr['user_business_name']}',
				  '{$arr['user_abn']}',
				  '{$arr['user_account_code']}',
				  '".nl2br($arr['user_business_address'])."',
				  '{$arr['user_business_phone']}',
				  '{$arr['user_business_email']}',
				  '{$arr['user_youtube_email']}',
				  '{$password}'
				   )";
			$res = $DBobject->executeSQL($sql); 
			if($res){
				ProcessSendRegistrationEmail($DBobject->id);
			}
			return $res;
			
		}else{
			return false;
		}
	}
}
function ProcessSendRegistrationEmail($user_id){
	global  $SMARTY;
	$to = 'fredy@them.com.au';
	$from = 'All Fresh website';
	$fromEmail = 'noreply@allfresh.com.au';
	$subject = 'New website registration';
	$SMARTY->assign('link',$_SERVER['HTTP_HOST'].'/admin/edit_misc.php?id='.$user_id.'&misc=70');
	$body = $SMARTY->fetch('extends:email.tpl|email-registration.tpl');	
	$res = sendMail($to, $from, $fromEmail, $subject, $body);
	return $res;
}
function  ProcessUserLogin($arr){
	global $DBobject;
	$password = getPass($arr['email'], $arr['password']);
	$row = $DBobject->GetRow('tbl_user',"user_business_email = '".$arr['email']."' AND user_password = '".$password."' and user_active = '1' ");
	if($row){
		$sql = "SELECT * FROM tbl_link_user_rep WHERE link_user_id = '{$row['user_id']}' AND link_deleted IS NULL";
		$res = $DBobject->wrappedSql($sql); 
		if(!empty($res[0])){	
			$row2=$res[0];
			$_SESSION["user"]["id"]=$row["user_id"];
			$_SESSION["user"]["user_business_name"]=$row["user_business_name"];
			$_SESSION["user"]["user_business_email"]=$row["user_business_email"];
			$row3 = $DBobject->GetRow('tbl_rep',"rep_id = '".$row2['link_rep_id']."'  ");
			$_SESSION["user"]["rep"]["name"]	= $row3["rep_name"];
			$_SESSION["user"]["rep"]["phone"]	= $row3["rep_phone"];
			$_SESSION["user"]["rep"]["email"]	= $row3["rep_email"];
			$_SESSION["user"]["rep"]["address"]	= $row3["rep_address"];
			
			$sql='INSERT INTO tbl_log_user
								(
								log_user_id,
								log_user_ip,
								log_date,
								log_deleted)
								VALUES
								(
								"'.$row["user_id"].'",
								"'.$_SERVER["SERVER_ADDR"].'",
								NOW(),
								NULL)
								';
			$res = $DBobject->executeSQL($sql); 
			$log =  $DBobject->GetTable('tbl_log_user', 'log_user_id = "'.$row["user_id"].'" order by log_id DESC limit 4 ');
			if( $log[0][log_user_ip]  != $log[2][log_user_ip] 		&&  $log[0][log_user_ip] 	!= 	$log[1][log_user_ip] ){
				if($log[0][log_user_ip] != $log[3][log_user_ip] ){
					$body="
		 						<h2>Multiple Login locations</h2>
		 						User ".$row[user_name]."  has login from a diferent location for the 3thd time.<br/>
		 						login time : ".date("Y-m-d h-i-s")."
		 						";
					$from = 'All Fresh website';
					$fromEmail = 'noreply@allfresh.com.au';
					$subject = 'New website registration';
					sendMail('fredy@them.com.au', $from, $fromEmail, $subject, $body);
				
				}
			}
			
		}else{
			$_SESSION['error'][]='User not active';
			return false;
		}
		
		return true;
	}else{
		$_SESSION['error'][]='Wrong user or password';
	}	
	return false;
}
function ProcessUserAsk($arr){
	global $DBobject;
	if( !empty($arr['name']) && !empty($arr['email']) && !empty($arr['name'])){
		$sql = "INSERT into tbl_faq
		(
		faq_question,
		faq_date,
		faq_published
		)
		VALUES
		(
		'".$arr['question']."',
		NOW(),
		0
		)
		";
		$res = $DBobject->executeSQL($sql); 
		if($res){
			ProcessSendQuestionEmail($DBobject->id);
		}
		return $res;
	}else{
		$_SESSION['error'][]='Missing fields';
	}
	return false;
}
function ProcessSendQuestionEmail($faq_id){
	global  $SMARTY;
	$to = 'fredy@them.com.au';
	$from = 'All Fresh website';
	$fromEmail = 'noreply@allfresh.com.au';
	$subject = 'New website question';
	$SMARTY->assign('link',$_SERVER['HTTP_HOST'].'/admin/edit_misc.php?id='.$faq_id.'&misc=73');
	$body = $SMARTY->fetch('extends:email.tpl|email-question.tpl');	
	$res = sendMail($to, $from, $fromEmail, $subject, $body);
	return $res;
}
function ProcessUserRecover($arr){
	global $DBobject;
	if(!empty($arr['email'])){
		$row = $DBobject->GetRow('tbl_user',"user_business_email = '".$arr['email']."' and user_active = '1' ");
		if($row){
			$newpass = genRandomString(6);
			$password = getPass($arr['email'], $newpass);
			$sql = "UPDATE tbl_user  SET user_password  = '".$password."' where user_id = '".$row['user_id']."' ";
			$res = $DBobject->executeSQL($sql); 
			if($res){
				$_SESSION['notice'][]='Your password has been updated, please check your email.';
			 	return 	ProcessNewPassEmail($row,$newpass);
			}
		}else{
			$_SESSION['error'][]='User not active';
			return false;
		}
	}
	$_SESSION['error'][]='Please enter your email';
	return false;
	
}
function ProcessNewPassEmail($user,$pass){	
	global  $SMARTY;	
	$from = 'All Fresh website';
	$fromEmail = 'noreply@allfresh.com.au';
	$subject = 'All Fresh password recovery';	
	$SMARTY->assign('user_name',$user['user_name']);
	$SMARTY->assign('password',$pass);
	$SMARTY->assign('link',$_SERVER['HTTP_HOST'].'/login');
	$body = $SMARTY->fetch('extends:email.tpl|email-password.tpl');	
	sendMail($user['user_email'], $from, $fromEmail, $subject, $body);
	return true;
}
