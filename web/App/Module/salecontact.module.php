<?
/**
* 应用业务组件基类
*
* 商标联系人
* 
* @package	Model
* @author	void
* @since	2015-11-20
*/
class SaleContactModule extends AppModule
{
	/**
	* 引用业务模型
	*/
	public $models = array(
		'sale'				=> 'sale',
        'salecontact'		=> 'salecontact',
		'salehistory'		=> 'salehistory',
	);

	/**
	* 获取获取出售商标号
	* @author  haydn
	* @since   2016-02-25
	* @param   int			$uid      当前登录id
	* @param   array		$where    查询条件
	* @return  array		$idArr    商标号
	*/
	public function getSellBrandIds($uid,$search = array())
	{
		$strand		= $string = '';
        $w[]        = " uid='".$uid."' ";
        $str        = '('.implode(' OR ',$w).')';
       	if( count($search) > 0 ){
       		$where	= array();
       		!empty($search['startprice']) ? $where[]= "`price` >='{$search['startprice']}'" : "";
       		!empty($search['endprice']) ? $where[]= "`price` <='{$search['endprice']}'" : "";
       		!empty($search['status']) ? $where[]= "`isVerify` ='{$search['status']}'" : "";
			$strand = count($where) > 0 ? 'AND ('.implode(' AND ',$where).')' : '';
       	}
        $r['raw']   = $str.$strand;
        $r['col']   = array('number');
		$r['limit']	= 10000;

		$data		= $this->import('salecontact')->find($r);
        $idArr  = array(0);
        if( !empty($data) ){
            foreach( $data as $k => $v ){
                if(!empty($v['number'])){
                   $idArr[] = $v['number'];
                }
            }
        }
		return $idArr;
	}
	/**
	* 获取联系人信息
	* @author	hyand
	* @since	2016-03-02
	* @param 	int 		$saleId	出售id
	* @param 	int 		$uid	用户id
	* @param 	string 		$field	查询字段
	* @return	array		$data	返回结果
	*/
	public function getSaleContactInfo($saleId,$uid,$field = '*')
	{
        $r['eq']   	= array('saleId' => $saleId,'uid' => $uid);
        $r['col']   = array($field);
		$r['limit']	= 1;
		$data		= $this->import('salecontact')->find($r);
		return $data;
	}
	/**
	* 获取用户出售的商标信息
	* @author	martin
	* @since	2016/3/9
	* @param 	int 		$uId	用户ID
	* @param 	string 		$number	商标号
	* @return	array		$data	返回结果
	*/
	public function getSaleContactByUserId($uId, $number)
	{
		if(empty($uId) || empty($number)) return array();
        $cfId       = $this->load('user')->getChofanId($uId);
        $w[]        = " uid='".$uid."' ";
        $cfId       > 0 && $w[]    = " userId='".$cfId."' ";
        $r['raw']   = '('.implode(' OR ',$w).')';
        $r['eq']   	= array('number' => $number);
		$r['limit']	= 1;
		$data		= $this->import('salecontact')->find($r);
		return $data;
	}
	
	/**
	 * 获取出售信息总条数【出售商标】
	 * @author	martin
	 * @since	2016/4/1
	 *
	 * @access	public
	 * @param	string	$uId	用户ID
	 * @return	null  
	 */
	public function getSaleContact($uId)
	{
		$r['eq']	= array('uId' => $uId);
		$count		= $this->import('salecontact')->count($r);
		return $count;
	}

	/**
     * 修改出售价格
	 * @author	martin
	 * @since	2016/4/5
	 *
	 * @access	public
	 * @param	array	$param	参数
	 * @return	bool  
	 */
	public function editSalePrice($param)
	{

		$price	= $param['price'] * $param['type'];
		$input	= array(
				'price'	=> $price,
				'cid'	=>$param['saleid']
				);
		$data	= $this->importBi('tradmin')->updatePrice($input);
		return $data;
	}
}
?>