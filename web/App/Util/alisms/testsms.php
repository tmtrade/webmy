<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    $c = new TopClient;
    $c->appkey = $appkey = '23468191';
    $c->secretKey = $secret = 'eaabaf215b0abd55b59cd93a37aaaf3e';
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setExtend("123456");
    $req->setSmsType("normal");
    $req->setSmsFreeSignName("营销管理平台");
    $req->setSmsParam("{\"sitename\":\"营销管理平台\",\"pass\":\"123123\"}");
    $req->setRecNum("13551112451");
    $req->setSmsTemplateCode("SMS_24505053");
    $resp = $c->execute($req);

    echo "<pre>";
    print_r($resp);
    print_r($c->appkey);

?>