<?
/**
* 应用业务组件基类
*
* 商标联系人
* 
* @package	Model
* @author	martin
* @since	2016/4/5
*/
class SaleHistoryModule extends AppModule
{
	/**
	* 引用业务模型
	*/
	public $models = array(
		'history'		=> 'usersalehistory',
	);

	/**
	 * 获取已删除信息的商标商量
	 * @author	martin
	 * @since	2016/4/1
	 *
	 * @access	public
	 * @param	string	$uId	用户ID
	 * @return	null  
	 */
	public function getHistoryCount($uId)
	{
	
		$r['eq']	= array('uid' => $uId,'type'=>4);
		$count		= $this->import('history')->count($r);
		return $count;
	}

	/**
	 * 获取删除列表
	 * @author	martin
	 * @since	2016/4/1
	 *
	 * @access	public
	 * @param	string	$uId	用户ID
	 * @return	null  
	 */
	public function getHistoryPage($uId, $search)
	{
	
		$r['page']		= $search['page'];
		$r['limit']		= $search['pagesize'];
		$r['eq']		= array('uid' => $uId,'type'=>4);
		!empty($search['keywords']) && $r['raw']		= "( number = '".$search['keywords']."' or name like '%".$search['keywords']."%')";
		!empty($search['status']) && $r['eq']['status']	= $search['status'];
		!empty($search['number']) && $r['eq']['number']	= $search['number'];
		//!empty($search['class']) && $r['eq']['class']	= $search['class'];

		$data		= $this->import('history')->findAll($r);
		foreach($data['rows'] as & $item){
			$item['data']	= unserialize($item['data']);
			if(empty($item['data']['name'])) $item['data']['name'] = '暂无名称';
		}
		return $data;
	}

}
?>