//解析页面
function analyzePage(ptype){
	var str = '';
	switch (ptype){
		case '201':
			str = '首页';break;
		case '202':
			str = '商标筛选页';break;
		case '203':
			str = '专利筛选页';break;
		case '204':
			str = '商标详情页';break;
		case '205':
			str = '专利详情页';break;
		case '12':
			str = '我要买';break;
		case '13':
			str = '我要卖';break;
		default:
			str = '未知';
	}
	return str;
}