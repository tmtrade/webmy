<?
/**
 * 应用业务组件基类
 *
 * 存放业务组件公共方法
 * 
 * @package	Model
 * @author	void
 * @since	2015-11-20
 */
class BuyerModule extends AppModule
{
	/**
     * 引用业务模型
     */
    public $models = array(
        'relation'	=> 'relation',
        'collect'	=> 'collect',
        'buybrand'  => 'buybrand',
    );

	/**
	 * 获求购信息(调用crm接口)
	 * @author	martin
	 * @since	2016/1/26
	 *
	 * @access	public
	 * @param	string	$userId	会员编号
	 * @return	object  返回业务对象
	 */
	public function getInfoAll($userId , $aid = "", $search=array())
	{
		$list = $this->load('relation')->getRelationList($userId, $aid);
		if(empty($list)) return array();

		$search['id'] 	= $list;
		$search['type'] = empty($search['type']) ? array() : array($search['type']);
        $cacheKey       = 'network_'.md5(serialize($search));
        if ( empty($this->com('redisHtml')->get($cacheKey)) ){
            $json = $this->importBi('crm')->getNetwork($search);
            $this->com('redisHtml')->set($cacheKey, $json, 600);
        }else{
            $json = $this->com('redisHtml')->get($cacheKey);
        }

		return empty($json['data']) ? array() : $json;
	}

    public function getBuyList($userId, $params=array(), $page=1, $nums=20)
    {
        if ( intval($userId) <= 0 ) return array();

        $r['eq']    = array('userId'=>$userId);
        $r['raw']   = ' 1 ';

        if ($params['startdate'] && strtotime($params['startdate']) > 0){
            $r['raw'] .= ' AND `recorddate` >= '.(strtotime($params['startdate']));
        }
        if ($params['enddate'] && strtotime($params['enddate']) > 0){
            $r['raw'] .= ' AND `recorddate` <= '.(strtotime($params['enddate']));
        }

        $r['order'] = array('id'=>'desc');
        $r['page']  = $page;
        $r['limit'] = $nums;

        $data = $this->import('buybrand')->findAll($r);
        return $data;
    }


	/**
	 * 获求购信息(调用crm接口)
	 * @author	martin
	 * @since	2016/1/26
	 *
	 * @access	public
	 * @param	string	$userId	会员编号
	 * @return	object  返回业务对象
	 */
	public function getInfoBuyId($userId , $aid)
	{
		$r['eq']	= array('userId'=>$userId,'relationId'=>$aid);
		$r['limit']	= 10;
		$relation	= $this->import('relation')->findAll($r);
		if( empty( $relation['rows'] ) ){
			return array();	
		}
		$list = explode(',', $aid);
		$json = $this->importBi('crm')->getNetwork($list);
		if(!empty($json['data'])){
			$brand = $this->importBi('crm')->getBrandInfo($json['data'][0]['id']);
			foreach($brand['data'] as $key => $item){
				$trademark  = $this->load('trademark')->details($item['trademark'],$item['class']);
				$brand['data'][$key]['trade'] = $trademark;
			}
			return $brand['data'];
		}
		return array();

	}


	/**
	 * 获取我的购买商标(调用crm接口)
	 * @author	martin
	 * @since	2016/3/2
	 *
	 * @access	public
	 * @param	string	$userId	会员编号
	 * @return	object  返回业务对象
	 */
	public function getClinchBrandInfo($userId , $search)
	{
		$list['aid']		= $this->load('relation')->getRelationList($userId);
		$list['search']		= $search;
		$json				= $this->importBi('crm')->getClinchBrand($list);
		$data				= array();
		if(!empty($json['data'])){
			
			$saleList		= $this->load('salecontact')->getSellBrandIds($userId);
			foreach($json['data'] as $key => $item){
				$trademark						= $this->load('trademark')->details($item['trademark'],$item['class']);
				if(empty($trademark)){ continue; }
				$data['rows'][$key]['trade']	= $trademark;
				$data['rows'][$key]['sale']		= 0;
				if(in_array($item['trademark'],$saleList)){
					$data['rows'][$key]['sale'] = 1;
				}
				$second							= $this->load('secondstatus')->getTwoDetails($item['trademark']);
				$data['rows'][$key]['second']	= $this->load('mytrade')->SecondStatusValue($second);
			}
			
		}
		$data['total']		= $json['paging']['total'];
		return $data;

	}
	/**
	 * 获求购信息(调用crm接口)
	 * @author	haydn
	 * @since	2016/3/31
	 *
	 * @access	public
	 * @param	string	$userId	会员编号
	 * @return	object  返回业务对象
	 */
	public function getWantTm($userId)
	{
		$list				= $this->load('relation')->getRelationList($userId);
		if(!empty($list)){
			$json			= $this->importBi('crm')->getWantTm($list);
			return empty($json['data']) ? array() : $json['data'];
		}
		return array();
	}
    /**
     * 获求购信息(调用crm接口)
     * @author	alexey
     * @since	2016/4/1
     *
     * @access	public
     * @param	string	$data	数组
     * @return	object  返回业务对象
     */
    public function delMysell($data)
    {
        if(!empty($data)){
            $json			= $this->importBi('Tradmin')->deleteSale($data);
            return empty($json['code']) ? array() : $json['code'];
        }
        return array();
    }
    /**
     * 获取交易动态列表
     * @author	haydn
     * @since	2016/4/8
     *
     * @access	public
     * @param	int		$userId		用户id
     * @param	array	$search		数组
     * @return	array	$data
     */
	pubLic function getMysellContent($userId,$search)
	{
		$tmLog	= $this->importBi('crm')->getTrademarkLog($search['number'],$search['class']);
		if( !empty($tmLog['data']) ){
			$aidArr = $timeArr = array();
			foreach( $tmLog['data'] as $k => $v ){
				$aidArr[$v['aid']] = $v['aid'];
			}
			$query2['id'] 	= $aidArr;
			$query2['limit']= 2000;
			$netArr			= $this->importBi('crm')->getNetwork($query2);
			if( !empty($netArr['data']) ){
				foreach( $netArr['data'] as $k => $v ){
					$network['dateline']	= $v['dateline'];
					$network['aid'] 		= $v['id'];
					$timeArr[] = $network;
				}
				$timeArr = array_sort($timeArr);
			}
		}
		$data = array(
			'tmlog'		=> $tmLog['data'],
			'network' 	=> $timeArr,
		);
		return $data;
	}

    public function  addBuy($userId, $arr){
        $_arr               = $arr;
        $bool				= 0;
        $arr['type']		= 1;
        $arr['pttype']		= "求购";
        $arr['source']		= 0;
        $output				= $this->load('network')->networkJoin($arr);
        if($output['code'] == 1 && !empty($output['data']['id'])){
            $this->load('relation')->addRelation($userId,$output['data']['id'] );

            $_arr['subject']    = $_arr['remarks'];
            $_arr['mobile']     = $_arr['tel'];
            $_arr['userId']     = $userId;
            $_arr['aid']        = $output['data']['id'];
            $this->import('buybrand')->add($_arr);//备份

            $bool=1;
        }
        return $bool;
    }
}
?>