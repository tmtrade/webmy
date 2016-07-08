<?
/**
 * 项目首页
 *
 * 首页内容展示
 *
 * @package	Action
 * @author	void
 * @since	2015-11-20
 */
class IndexAction extends AppAction
{

	/**
	 * 控制器默认方法
	 * @author	void
	 * @since	2015-11-20
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$html = $this->isLogin ? '/user/user.main.html' : 'index/index.index.html';
		if($html=="/user/user.main.html"){
		    $userInfoId = $this->userInfo['id'];
		    $data		= $this->load("user")->getInfoById( $userInfoId );
		    $this->set('data', $data);
		}
		$this->display($html);
	}
}
?>