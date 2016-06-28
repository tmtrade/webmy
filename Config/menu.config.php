<?
return array(
	0=> array(
			'name'	=>'我的服务',
			'icon'	=>'icon-book',
			'child'	=> array(
				//array('title'=>'我的需求', 'url'=>'/buyer/index/',),
				array('title'=>'我的收藏', 'url'=>'/collect/trade/',),
				array(
					'title'=>'我的求购', 
					'url'=>'/buyer/myinfo/',
					'child'	=> array(
						'/buyer/myinfo/',
						'/buyer/myview/',
					),
				
				),
			),

	),
	1=> array(
			'name'	=>'个人信息',
			'icon'	=>'icon-user',
			'child'	=> array(
				array(
					'title'		=>'基本信息', 
					'url'		=>'/user/main/',
					'child'		=> array(
						'/user/main/',
						'/user/changePwd/',
						'/user/changeEmail/',
						'/user/changePhone/',
					)
				),
				// array(
				// 	'title'=>'消息中心', 
				// 	'url'=>'/message/index/',
				// 	'child'		=> array(
				// 		'/message/views/',
				// 	)
				// ),
			),
	),
	
);
?>