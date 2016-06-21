<?
/**
 * 商标模块
 *
 * 商标详情
 * 
 * @package	Module
 * @author	martin
 * @since	2016/1/27
 */
class TrademarkModule extends AppModule
{
	/**
	 * 引用业务模型
	 */
	public $models = array(
		'trademark'		=> 'Trademark',
		'proposer'		=> 'proposer',
		'secondstatus' => 'secondstatus',
		);

	/**
	 * 引用业务模型
	 */
	public function details($number, $class)
	{
		$r['eq']				= array('id' => $number, 'class' => $class);
		$r['limit']				= 1;
		$data					= $this->import('trademark')->find($r);
		if(empty($data)) return array();
		$data['imgUrl']			= "http://f1.chofn.net/tmimg/ico/".$data['id']. "-". $data['class']. "/". substr(substr (md5(($data['id']. ($data['class'] *100). "9SNkmd9emwle#$290csd@")),0, 23), -16). ".jpg";

		$w['eq']				= array('id' => $data['proposer_id']);
		if($data['valid_start'] == '0000-00-00' && $data['valid_end'] != '0000-00-00'){
			$data['valid_start'] = date('Y-m-d',strtotime("-10 year +1 day", strtotime( $data['valid_end'] )));
		}
		$w['limit']				= 1;
		$proposer				= $this->import('proposer')->find($w);
		$data['trademark']		= empty($data['trademark']) ? '暂无名称' : $data['trademark'];
		$data['group']			= $this->emptyreplace( $data['group'] );
		

		$data['newId']			= $proposer['newId'];
		$data['proposerName']	= $proposer['name'];
		$status					= $this->load("secondstatus")->details($number, $class);
		$data['newstatus']		= $status;
		$data['yzc_url']		= C('WEBSITE_URL')."d-".$data['auto'] ."-".$data['class'].".html";
		$data['shansoo_url']	= C('SEARCH_URL')."trademark/detail/?id=".$data['auto'];
		return $data;
	}


	/* 
	* 群组字符串替换处理
	*/ 
	public function emptyreplace($str) 
	{   $str = str_replace(",", ' ', $str); //替换全角空格为半角 
        $str = str_replace("　", ' ', $str); //替换全角空格为半角 
        $str = str_replace("    ", ' ', $str); //替换全角空格为半角 
        $str = str_replace("<br>", ' ', $str); //替换全角空格为半角 
        $str = str_replace("\n", ' ', $str); //替换全角空格为半角 
        $str = str_replace("\r", ' ', $str); //替换全角空格为半角 
        $str = str_replace("\t", ' ', $str); //替换全角空格为半角 
        $str = str_replace("\r\n", ' ', $str); //替换全角空格为半角 
        $str = str_replace('&lt;br&gt;', ' ', $str); //替换全角空格为半角 
        $str = str_replace('/\(.*?\)/', ' ', $str); //替换全角空格为半角 
        $str = preg_replace("/(?:\()(.*)(?:\))/i", '', $str);//替换带(1)(xx)(一)这种类型的数据
        $result = '';
        $strArr = explode(" ",$str);
        $strArr = array_filter(array_unique($strArr)); //去掉空字符串
        sort($strArr);
        $result = implode(',', $strArr);
        return $result; 
	}

	
	/**
	 * 查询一标多类
	 */
	public function moreInfo($number)
	{
		$r['eq']				= array('id' => $number);
		$r['order']				= array('class' => 'asc');
		$r['limit']				= 45;
		$data					= $this->import('trademark')->findAll($r);
		return $data;
	}
}
?>