<?
$prefix		= 'uc_';

$dbId		= 'usercenter';
$configFile	= array( ConfigDir.'/Db/usercenter.master.config.php' );

$tbl['user'] = array(
	'name'		=> $prefix.'users',
	'dbId'		=> $dbId, 
	'configFile'=> $configFile,
);

$tbl['sessions'] = array(
    'name'      => $prefix.'user_sessions',
    'dbId'      => $dbId, 
    'configFile'=> $configFile,
);

$tbl['changelogs'] = array(
    'name'        => $prefix.'user_changelogs',
    'dbId'        => $dbId, 
    'configFile'=> $configFile,
);

$tbl['loginlogs'] = array(
    'name'        => $prefix.'user_loginlogs',
    'dbId'        => $dbId, 
    'configFile'=> $configFile,
);

$tbl['verify'] = array(
    'name'        => $prefix.'user_verify',
    'dbId'        => $dbId, 
    'configFile'=> $configFile,
);


?>
