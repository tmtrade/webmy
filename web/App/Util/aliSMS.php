<?php
date_default_timezone_set('Asia/Shanghai'); 
/**
* 
*/
class aliSMS
{
	/**
	 *发送验证码
	 **************************/
	public static function sendCode($phone, $code, $title='', $name='')
	{
		if ( empty($phone) || empty($code) ) {
			return array('code'=>0, 'msg'=>'参数为空');
		}
		if ( empty($title) ) $title = SMS_TITLE;
		if ( empty($name) ) $name = SMS_NAME;

		require_once("alisms/TopSdk.php");

	    $c 				= new TopClient;
	    $c->appkey 		= SMS_KEY;
	    $c->secretKey 	= SMS_SECRET;
	    $req 			= new AlibabaAliqinFcSmsNumSendRequest;
	    $req->setExtend("");
	    $req->setSmsType("normal");
	    $req->setSmsFreeSignName($title);
	    $req->setSmsParam("{\"sitename\":\"{$name}\",\"pass\":\"{$code}\"}");
	    $req->setRecNum($phone);
	    $req->setSmsTemplateCode("SMS_24505053");
	    $resp = $c->execute($req);

	    $res = json_decode(json_encode($resp), true);
	    if(isset($res["result"])){
	        $result = $resp["result"];
	    }else{
	        $result = $resp;
	    }
	    $errCode = empty($result["err_code"]) ? $result["code"] : $result["err_code"];
    	if($errCode != 0){
	    	return array('code'=>0, 'msg'=>'发送失败');
	    }else{
	    	return array('code'=>1, 'msg'=>'发送成功');
	    }
	    
	}
}
?>