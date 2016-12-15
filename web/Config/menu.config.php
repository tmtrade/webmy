<?
return array(
	0 => array(
			'name'	=>'买家中心',
			'icon'	=>'icon-book',
			'child'	=> array(
				array('title'=>'我的收藏', 'url'=>'/collect/trade/',),
				array(
					'title'=>'我的求购',
					'url'=>'/buyer/myinfo/',
					'child'	=> array(
						'/buyer/myinfo/',
						'/buyer/myview/',
					),
				),
//				array('title'=>'监控商标','url'=>'/collect/index/',),
			),
	),
    1 => array(
        'name'	=>'卖家中心',
        'icon'	=>'icon-book',
        'child'	=> array(
            array(
                'title'=>'我的出售',
                'url'=>'/buyer/mysell/',
                'child'	=> array(
                    '/buyer/history/',
                    '/buyer/mysellcontent/',
                ),
            ),
            array('title'=>'商标报价单', 'url'=>'/quotation/index/', ),
            array(
                'title' => '我要出售',
                'url'   => '/sell/index/',
                'child'	=> array(
                    '/sell/number/',
                    '/sell/person/',
                    '/sell/document/',
                ),
            ),
            array( 'title' => '我的出售', 'url'   => '/goods/index/', ),
        ),
    ),
	2 => array(
			'name'	=>'用户中心',
			'icon'	=>'icon-user',
			'child'	=> array(
				array(
					'title'		=>'我的账户', 
					'url'		=>'/user/main/',
					'child'		=> array(
						'/user/main/',
						'/user/changePwd/',
						'/user/changeEmail/',
						'/user/changePhone/',
					)
				),
//				array(
//					'title'=>'我的申请人',
//					'url'=>'/proposer/myproposer/',
//					'child'		=> array(
//						'/proposer/myproposer/',
//						'/proposer/proposerquerylist/',
//					)),
//				array('title'=>'我的顾问', 'url'=>'/user/mystaffmore/',),
				array(
					'title'=>'消息中心', 
					'url'=>'/message/index/',
					'child'		=> array(
						'/message/views/',
					)
				),
			),
	),	
);
?>