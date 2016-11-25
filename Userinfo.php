<?php /* Source By @Roonx_Team */
define('API_KEY', 'TOKEN');
$admin = "ADMIN";
function roonx($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
$update = json_decode(file_get_contents('php://input'));
$chat_id = $update->message->chat->id;
$text = $update->message->text;
$from_id = $update->message->from->id;
$from_first = $update->message->from->first_name;
$from_last = $update->message->from->last_name;
$from_user = $update->message->from->username;
$forward = $update->message->forward_from;
$forward_first = $update->message->forward_from->first_name;
$forward_last = $update->message->forward_from->last_name;
$forward_user = $update->message->forward_from->username;
$forward_id = $update->message->forward_from->id;


  if(preg_match('/^\/([Ss]tats)/',$text) and $from_id == $admin){
    $user = file_get_contents('Member.txt');
    $member_id = explode("\n",$user);
    $member_count = count($member_id) -1;
    roonx('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"تعداد کل اعضا: $member_count",
      'parse_mode'=>'HTML'
    ]);
}
elseif($forward != null){
	if($forward_first != null){
		$forward_first = "FirstName: ".$forward_first;
	}else{
		$forward_first = "FirstName: None";
	} 
	if($forward_last != null){
		$forward_last = "LastName: ".$forward_last;
	}else{
		$forward_last = "LastName: None";
	} 
	if($forward_user != null){
		$forward_user = "UserName: @".$forward_user;
	}else{
		$forward_user = "UserName: None";
	} 
	roonx('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"<b>Forward INFO</b>\n\n".$forward_first."\n".$forward_last."\n".$forward_user."\nID: ".$forward_id,
      'parse_mode'=>'HTML'
    ]);
}
else{
	if($from_first != null){
		$from_first = "FirstName: ".$from_first;
	}else{
		$from_first = "FirstName: None";
	} 
	if($from_last != null){
		$from_last = "LastName: ".$from_last;
	}else{
		$from_last = "LastName: None";
	} 
	if($from_user != null){
		$from_user = "UserName: @".$from_user;
	}else{
		$from_user = "UserName: None";
	} 
	roonx('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>$from_first."\n".$from_last."\n".$from_user."\nID: ".$from_id,
      'parse_mode'=>'HTML'
    ]);
}
$user = file_get_contents('Member.txt');
    $members = explode("\n",$user);
    if (!in_array($chat_id,$members)){
      $add_user = file_get_contents('Member.txt');
      $add_user .= $chat_id."\n";
     file_put_contents('Member.txt',$add_user);
    }
	?>
