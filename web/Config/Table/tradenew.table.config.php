<?
$prefix		= 't_';
$dbId		= 'tradenew';
$configFile	= array( ConfigDir.'/Db/tradenew.master.config.php' );

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