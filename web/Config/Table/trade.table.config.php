<?
$prefix		= 't_';
$dbId		= 'trade';
$configFile	= array( ConfigDir.'/Db/trade.master.config.php' );

$tbl['sale'] = array(
	'name'		=> $prefix.'sale',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);
$tbl['salecontact'] = array(
	'name'		=> $prefix.'sale_contact',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);
$tbl['salehistory'] = array(
	'name'		=> $prefix.'user_sale_history',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);
$tbl['packageItems'] = array(
	'name'		=> $prefix.'package_items',
	'dbId'		=> $dbId,
	'configFile'=> $configFile,
);
$tbl['package'] = array(
	'name'		=> $prefix.'package',
	'dbId'		=> $dbId,
	'configFile'=> $configFile,
);
?>