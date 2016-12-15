<?
/** 
*
* 商标出售信息表
* 
* @package	Model
* @author	void
* @since	2016-03-01
*/
class SaleModule extends AppModule
{
	/**
	* 引用业务模型
	*/
	public $models = array(
		'sale'		    => 'sale',
		'verify'	    => 'verify',
        'contact'	    => 'saleContact',
        'saleTminfo'	=> 'SaleTminfo',
	);

    /**
     * 根据商标得到tminfo
     * @author dower
     * @param $number
     * @param string $field
     * @return array|bool
     */
    public function getSaleTmByNumber($number){
        $r['eq'] = array('number'=>$number);
        $r['col']   = array('embellish');
        $info = $this->import('saleTminfo')->find($r);
        if($info==false){
            return false;
        }
        //返回包装数据
        if($info['embellish']){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 根据商标号得到商标的包装信息(无美化图,获得商标图)
     * @param $number
     * @return array|bool
     * @throws SpringException
     */
    public function getSaltTminfoByNumber($number){
        $r['eq'] = array('number'=>$number);
        $info = $this->import('saleTminfo')->find($r);
        //无销售数据
        if($info==false){
            $info = array();
            $info['alt1'] = '';
            $info['embellish'] = $this->load('trademark')->getImg($number);
            return $info;
        }
        //返回包装数据
        if($info['embellish']){
            $info['embellish'] = TRADE_URL.$info['embellish'];
        }else{
            $info['embellish'] = $this->load('trademark')->getImg($number);
        }

        return $info;
    }

    /**
     * 判断商标是否销售中
     * @author dower
     * @param $number
     * @return bool
     */
    public function isSale($number){
        $r['eq'] = array('number'=>$number);
        $r['col'] = array('status');
        $result = $this->import('sale')->find($r);
        if($result && $result['status']==1){
            return true;
        }
        return false;
    }

    //获取商品信息
    public function getSale($number)
    {
        $arr['eq'] = array(
            'number' => $number,
        );
        $info = $this->import('sale')->find($arr);
        if ( empty($info) ) return array();
        return $info;
    }

    //获取商品number和UId获取商标下的联系人信息
    public function getSaleContactByUid($number, $uid)
    {
        $r['eq'] = array(
            'number' => $number,
            'uid' => $uid,
        );
        $r['limit'] = 1;
        return $this->import('contact')->find($r);
    }

    /**
     * 获取出售信息
     * @author  haydn
     * @since   2016-02-25
     * @param   int			$uid  	 当前登录id
     * @param   array		$search  查询条件
     * @param   int			$page  	 当前页
     * @param   int			$limit   每页数据
     * @return  array		$array
     */
    public function getSellRows($uid,$search,$page,$limit = 5)
    {
        if ( $uid <= 0 ) return array();
        $r = array();
        $r['eq']    = array('uid'=>$uid);

        if( count($search) > 0 ){
            $where	= array();
            !empty($search['keywords']) && $where[] = " number IN (SELECT number FROM t_sale WHERE (name like '%".$search['keywords']."%' or number like '%".$search['keywords']."%') ) ";

            !empty($search['startprice']) ? $where[] = "`price` >='{$search['startprice']}'" : "";
            !empty($search['endprice']) ? $where[] = "`price` <='{$search['endprice']}'" : "";
            !empty($search['status']) ?$r['eq']['isVerify'] = "`isVerify` ='{$search['status']}'" : "";
            $strand = count($where) > 0 ? ' ('.implode(' AND ',$where).')' : '';
        }
        $r['raw']   = $strand;
        $r['page']	= $page;
        $r['limit']	= $limit;
        $r['order'] = array('date'=>'desc');
//debug($r);
        $data		= $this->import('contact')->findAll($r);

        if( $data['total'] > 0 ){
            foreach( $data['rows'] as $k => $v ){
                $number	= $v['number'];
                //$class	= $v['class'];
                $data['rows'][$k]['dealdate'] = 0;
                //获取联系人相关信息
                //$data['rows'][$k]['contact']	= $this->load('salecontact')->getSaleContactInfo($v['saleId'],$uid);
                //获取申请人、图片等信息
                $trademark 						= $this->load('trademark')->details($v['number']);
                $data['rows'][$k]['trade']		= $trademark;
                //用于排序
                $data['rows'][$k]['apply_date']	= $trademark['apply_date'];
                //获取二级状态
                $statusTwo						= $this->load('secondstatus')->getTwoDetails($v['number']);
                $data['rows'][$k]['second']		= $this->load('mytrade')->SecondStatusValue($statusTwo);

                $_info = $this->getSale($number);
                $data['rows'][$k]['class'] = $_info['class'];
                $data['rows'][$k]['name']  = $_info['name'];
            }
        }
        //$data['rows'] 	= array_sort($data['rows'],'apply_date',$search['regdate']);
        return $data;
    }

	/**
	* 获取出售信息
	* @author  haydn
	* @since   2016-02-25
	* @param   int			$uid  	 当前登录id
	* @param   array		$search  查询条件
	* @param   int			$page  	 当前页
	* @param   int			$limit   每页数据
	* @return  array		$array
	*/
	public function getSellList($uid,$search,$page,$limit = 5)
	{
		$list['aid']	= $this->load('relation')->getRelationList($uid);

		$sbArr 			= $this->load('salecontact')->getSellBrandIds($uid,$search);

		!empty($search['keywords']) && $r['raw'] = "(name like '%".$search['keywords']."%' or number like '%".$search['keywords']."%')";
		!empty($search['number']) && $r['eq']['number']	= $search['number'];
		!empty($search['class']) && $r['eq']['class']	= $search['class'];
		$r['in']	= array('number' => $sbArr);
		$r['page']	= $page;
		$r['limit']	= $limit;
        $r['order'] = array('id'=>'desc');

		$data		= $this->import('sale')->findAll($r);

		if( $data['total'] > 0 ){
			foreach( $data['rows'] as $k => $v ){
				$number	= $v['number'];
				$class	= $v['class'];
				$data['rows'][$k]['dealdate'] = 0;
				//获取联系人相关信息
				$data['rows'][$k]['contact']	= $this->load('salecontact')->getSaleContactInfo($v['id'],$uid);
				//获取申请人、图片等信息
				$trademark 						= $this->load('trademark')->details($v['number'],$v['class']);
				$data['rows'][$k]['trade']		= $trademark;
				//用于排序
				$data['rows'][$k]['apply_date']	= $trademark['apply_date'];
				//获取二级状态
				$statusTwo						= $this->load('secondstatus')->getTwoDetails($v['number']);
				$data['rows'][$k]['second']		= $this->load('mytrade')->SecondStatusValue($statusTwo);
				//获取商标的出售信息
				/*$pricechange					= $this->load('changeprice')->getChangeInfo($v['number']);
				if( $pricechange!= false ){
					$data['rows'][$k]['sale']	= $pricechange;
				}else{
					$data['rows'][$k]['sale']	= $this->getSaleInfo($v['number']);
				}*/
			}
		}
		$data['rows'] 	= array_sort($data['rows'],'apply_date',$search['regdate']);
		return $data;
	}


	/**
	* 获取商标价格信息
	* @author  martin
	* @since   2016/3/2
	* @param   int			$number  	 商标号
	* @return  array		$array
	*/
	public function getSaleInfo($number)
	{
		$s['eq']					= array('number'=>$number, 'status'=>1);
		$s['col']					= array('status','isOffprice', 'salePrice', 'salePriceDate', 'priceType', 'isSale', 'isLicense', 'price');
		$sale						= $this->import('sale')->find($s);
		if($sale  == false)  return array();
		if($sale['priceType'] == 1){
//			$salePrice = $sale['salePrice'] >= 10000 ? (getFloatValue(($sale['salePrice'] *1.1) / 10000,2) . '万') : ($sale['salePrice'] == 0 ? '议价' : $sale['salePrice']*1.1);
			$price = $sale['price']*1.1;

			if($sale['isOffprice'] == 1 && $sale['salePriceDate'] > time()){//特价
				$mess = $sale['salePrice'] .'元（截至'.date('Y-m-d',$sale['salePriceDate']).'）';
			}if($sale['isOffprice'] == 1 && $sale['salePriceDate'] == 0){//特价
				$mess = $sale['salePrice'] .'元（不限时特价）';
			}else {//定价
				$mess = $price.'元';
			}
		}else{
			$mess = '议价'; 
		}

		$priceType			= array();
		if(isset($sale['isSale']) && $sale['isSale'] == 1){
			$priceType[]	= "出售";
		}
		if(isset($sale['isLicense']) && $sale['isLicense'] == 1){
			$priceType[]	= "许可";
		}
		return array('price'=>$mess, 'priceType'=>implode('、', $priceType));
	}


	/**
	* 调用接口插入出售信息
	* @author  martin
	* @since   2016/3/2
	* @param   int			$userId  	 用户编号
	* @param   string		$search  	 出售信息
	* @return  array		$array
	*/
	public function  addSaleInfo($userId, $search)
	{
		// //动态参数
		$param = array(
			'uid'           => $userId,
			'number'        => $search['number'],
			'phone'         => $search['phone'],
			'contact'       => $search['name'],
			'price'         => $search['price'] * $search['pricetype'],
			'type'          => $search['saleType'],
			'source'        => 1,
		);
		$output				= $this->importBi('tradmin')->insertSale($param);
		return $output;
	}
	
	/**
	 * 导出excel
	 * @author	haydn
	 * @since	2016/3/10
	 *
	 * @access	public
	 * @param	string	$data	数据包
	 * @return	null  
	 */
	public function exceltable($data)
	{
		$objEndDate	= $this->load('noticenumber')->getNewDate();
		$count 		= count($data);
		$statusArr 	= array(1 	=> '销售中',2	=> '已下架',3	=> '审核中');
		$content	= array();
		foreach( $data as $k => $v ){			
			$applyDate	= $v['trade']['apply_date']	== '0000-00-00' ? '-' : $v['trade']['apply_date'];//申请时间
			$validEnd	= $v['trade']['valid_end']	== '0000-00-00' ? '-' : $v['trade']['valid_end'];//截止时间
			$dealDate	= empty($v['dealdate']) ? '-' : date('Y-m-d',$v['dealdate']);//成交日期
			$status		= !empty($v['status']) && array_key_exists($v['status'],$statusArr)? $statusArr[$v['status']] : '-';//业务状态
			$second		= empty($v['second']) ? '-' : implode(',',$v['second']);//商标状态
			$content[$k] = array(
						($k + 1),
						$v['trade']['id'],
						$v['trade']['class'],
						$v['trade']['trademark'],
						$v['trade']['proposerName'],
						$applyDate,
						$validEnd,
						$dealDate,
						$v['contact']['price'],//售价
						$v['trade']['goods'],
						$status,
						$second,
			);
			//ksort($content);
		}
		$time1					= date('Y/m/d', time());
		$time2					= date('YmdHis', time());
		$array['content']		= $content;
		$array['filename'] 		= '我的出售-业务总表';
		$array['templateid']	= '6';
		$string					= "报告编号：{$time2}  数据截止时间：{$objEndDate} 导出时间：{$time1}  ";
		$array['specify']		= array('C2' => $string,'A3' => "【我的商标】——业务总表（共计" .$count  ."件）");
		return serialize($array);
	}

	/**
	 * 获取出售信息【交易收藏】
	 * @author	martin
	 * @since	2016/4/1
	 *
	 * @access	public
	 * @param	string	$data	数据包
	 * @return	null  
	 */
	public function getSaleTotal($numberList, $raw)
	{
		$str			= "if(isOffprice=2, price * 1.1, if( salePriceDate > UNIX_TIMESTAMP() ,salePrice, price *1.1)) ";
		$r['in']		= array('number' => $numberList);
		$r['eq']		= array('status' => 1);
		if(!empty($raw)){
			$r['raw']	= sprintf($raw, $str, $str);
		}
		$count		= $this->import('sale')->count($r);
		return $count;
	}

	/**
	 * 获取出售信息【交易收藏】
	 * @author	martin
	 * @since	2016/4/1
	 *
	 * @access	public
	 * @param	string	$data	数据包
	 * @return	null  
	 */
	public function getSaleListBuyer($num)
	{
		$data = $this->importBi('trade')->getHotTm(null, $num);
		return $data;
	}
}
?>