<?
/**
 * 应用业务组件基类
 *
 * 商标浏览
 * 
 * @package	Model
 * @author	void
 * @since	2016-03-02
 */
class BrowseModule extends AppModule
{
	
	public $models = array(
        'browse'   		=> 'browse',
        'trademark'		=> 'Trademark',
	);
	/**
	* 商标浏览器数
	* @author	hyand
	* @since	2016-03-02
	* @param 	string 		$trademark	商标数
	* @param 	string 		$class		类别
	* @param 	int 		$uid		用户id
	* @return	int			$count
	*/
	public function browseCount($trademark,$class,$uid = 0)
	{
		$r['eq']['trademark']	= $trademark;
		$r['eq']['class']		= $class;
		$uid > 0 && $r['eq']['userId']		= $uid;
		$count = $this->import('browse')->count($r);
		return $count;
	}
	/**
	* 商标浏览器数加一（用主键id）
	* @author	hyand
	* @since	2016-03-02
	* @param 	int 		$id			商标主键id
	* @param 	int 		$uid		用户id
	* @return	int			$bid
	*/
	public function addTidBrowse($id,$url,$uid = 0)
	{
		$bid				= 0;
		$time				= $this->newBrowseTime($id,$url);
		$ktime				= TIME - $time;
		if( $ktime > 4 ){
			$r['eq']['auto']	= $id;
			$array 				= $this->import('trademark')->find($r);
			$data['tid'] 		= $id;
			$data['trademark'] 	= $array['id'];
			$data['class'] 		= $array['class'];
			$data['name'] 		= $array['trademark'];
			$data['sourceurl']	= $url;
			$data['userId'] 	= $uid;
			$data['recorddate'] = TIME;
			$data['recordip'] 	= getClientIp();
			$bid				= $this->import('browse')->create($data);
		}
		return $bid;
	}
	/**
	* 最新的浏览时间
	* @access	haydn
	* @since	2016-04-11
	* @param	int 		$id		商标主键id
	* @param	string 		$url	来源地址
	* @return	int			$time
	*/
	public function newBrowseTime($id,$url)
	{
		$r['eq']['tid']			= $id;
		$r['eq']['sourceurl']	= $url;
		$r['col']   = array('`id`,`recorddate`');
		$r['order'] = array('recorddate' => 'desc');
        $data       = $this->import('browse')->find($r);
        $time		= !empty($data['recorddate']) ? $data['recorddate'] : 0;
        return $time;
	}   
}
?>