<?
/**
* 应用业务组件基类
*
* 购买商标
* 
* @package	Model
* @author	void
* @since	2015-11-20
*/
class BuyBrandModule extends AppModule
{
	/**
	* 引用业务模型
	*/
	public $models = array(
		'user'		=> 'user',
		'buybrand'	=> 'buybrand',
		'relation'	=> 'relation'
	);

	/**
	* 添加购买信息（并提到分配系统）
	* @author  haydn
	* @since   2016-02-25
	* @param   array		$array  	 数据包
	* @return  array		$array
	*/
	public function buyAdd($array)
	{
		$code	= 0;
		$msg	= '不能重复添加';
		//$account= !empty($array['userId']) ? $array['userId'] : $array['tel'];
		$account= $array['tel'];
		$count	= $this->exist($account,$array['trademark'],$array['class']);
		if( $count == 0 ){
			$addNet['name'] 	= $array['name'];
			$addNet['tel'] 		= $array['tel'];
			$addNet['subject'] 	= $array['subject'];
			$addNet['remarks'] 	= $array['remarks'];
			$aid				= $this->load('register')->addNetwork($addNet);//添加到分配系统
			if( $aid > 0 ){
				$array['mobile']= $array['tel'];
				$array['aid']	= $aid;
				$id				= $this->import('buybrand')->add($array);//备份
				if( !empty($array['userId']) ){//网络id和账号关联
					$this->import('relation')->add($array['userId'],$aid);
				}
				$code 			= 1;
				$msg 			= '数据添加成功';
			}else{
				$code 			= 2;
				$msg 			= '请休息一下在提交数据';
			}
		}
		$array = array('code' => $code,'msg' => $msg);	
		return $array;
	}
	/**
	* 验证是否购买过
	* @author  haydn
	* @since   2016-03-14
	* @param   string||int	$account||$uid  手机或用户id
	* @param   string		$trademark   	商标号
	* @param   string		$class  	 	商标类型
	* @return  int			$count
	*/
	public function exist($account,$trademark,$class)
	{
		$cateId = isCheck($account);
		$field	= $cateId == 3 ? 'userId' : 'mobile';
		$count	= $this->import('buybrand')->getBuyCount($account,$trademark,$class,$field);
		return $count;
	}
	/**
	* 数据提交到分配系统,并备份（一只蝉用）
	* @since	2016-03-23
	* @author	haydn
	* @param 	string 	$account	账号
	* @param 	int 	$uid		账号id
	* @param 	array  	$data		数据包
	* @return  	array	$array
	*/
	public function addBackup($account,$uid,$data)
	{
		$code	= 0;
		$msg	= '添加失败';
		$cateId	= isCheck($account);
		$aid	= $this->load('register')->addNetwork($data,$cateId);//添加到分配系统
		if( $aid > 0 ){
			if( $uid == 0 ){
				$usInfo			= $this->import('user')->getUserInfo($account,$cateId);
				$uid			= !empty($usInfo['id']) ? $usInfo['id'] : 0;
			}
			$array['subject']	= $data['subject'];
			$array['remarks']	= $data['remarks'];
			$array['mobile']	= $account;
			$array['name']    	= $data['name'];
			$array['aid']		= $aid;
			$array['tid']		= 0;
			$array['trademark'] = 0;
        	$array['class']    	= 0;
			$array['userId']	= $uid;
			$id					= $this->import('buybrand')->add($array);//备份
			if( !empty($uid) ){//网络id和账号关联
				$rid = $this->import('relation')->add($uid,$aid);
			}
			$code 			= 1;
			$msg 			= '数据添加成功';
		}
		$array = array('code' => $code,'msg' => $msg);	
		return $array;
	}

}
?>