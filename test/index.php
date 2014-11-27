<?php

ini_set('display_startup_errors', true);

ini_set('display_errors', true);

ini_set('error_reporting', -1);

error_reporting(-1);

date_default_timezone_set('UTC');

ini_set('log_errors', 1);

ini_set('error_log', dirname(__FILE__) . '/_errorlog/' . date('Y-m-d') . '.php.log');

if (! file_exists(dirname(__FILE__) . '/_errorlog/') || ! is_dir(dirname(__FILE__) . '/_errorlog/'))
{
    mkdir(dirname(__FILE__) . '/_errorlog/', 0777, true);
}

require_once dirname(dirname(__FILE__)) . '/vendor/autoload_52.php';

$dbConfig = array
(
	'adapter'	=> 'Pdo_Mysql',
	'params'	=> array
	(
		'host'		=> 'localhost',
        'port'      => '8889',
		'username'	=> 'root',
		'password'	=> 'root',
		'dbname'	=> 'cfp',
		'charset'	=> 'utf8',
	),
);

$dbConnection = EhrlichAndreas_Db_Db::factory($dbConfig);

$cmsConfig = array
(
    'db'                => $dbConnection,
    //'dbtableprefix'     => '__test',
);

$aclCms = new EhrlichAndreas_AclCms_ModuleExtended($cmsConfig);

$aclCms->install();

$resources = array
(
    'module',
    'module-submodule',
    'module-submodule-controller',
    'module-submodule-controller-action',
);

$resourceParents = array
(
    array
    (
        'resource_id'           => '2',
        'resource_id_parent'    => '1',
    ),
    array
    (
        'resource_id'           => '3',
        'resource_id_parent'    => '2',
    ),
    array
    (
        'resource_id'           => '4',
        'resource_id_parent'    => '3',
    ),
);

$roles = array
(
    'guest',
    'editor',
    'moderator',
    'admin',
    'root',
);

$roleParents = array
(
    array
    (
        'role_id'           => '2',
        'role_id_parent'    => '1',
    ),
    array
    (
        'role_id'           => '3',
        'role_id_parent'    => '2',
    ),
    array
    (
        'role_id'           => '4',
        'role_id_parent'    => '3',
    ),
);

$permissions = array
(
    array
    (
        'role_id'       => '5',
        'resource_id'   => '1',
        'allowed'       => '1',
    ),
    array
    (
        'role_id'       => '1',
        'resource_id'   => '1',
        'allowed'       => '0',
    ),
    array
    (
        'role_id'       => '1',
        'resource_id'   => '4',
        'allowed'       => '1',
    ),
    array
    (
        'role_id'       => '2',
        'resource_id'   => '3',
        'allowed'       => '1',
    ),
    array
    (
        'role_id'       => '3',
        'resource_id'   => '2',
        'allowed'       => '1',
    ),
);

foreach ($resources as $resource)
{
    $param = array
    (
        'resource_name'    => $resource,
    );
    
    #$aclCms->addResource($param);
}

foreach ($resourceParents as $resourceParent)
{
    $param = $resourceParent;
    
    #$aclCms->addResourceParent($param);
}

foreach ($roles as $role)
{
    $param = array
    (
        'role_name' => $role,
    );
    
    #$aclCms->addRole($param);
}

foreach ($roleParents as $roleParent)
{
    $param = $roleParent;
    
    #$aclCms->addRoleParent($param);
}

foreach ($permissions as $permission)
{
    $param = $permission;
    
    #$aclCms->addPermission($param);
}

$acl = $aclCms->getAclObject(true);

echo "<pre>\n\n";

foreach ($resources as $resource)
{
    echo $resource . " :: \n";

    foreach ($roles as $role)
    {
        $isAllowed = $acl->isAllowed($role, $resource);

        echo $role . ' :: ' . var_export($isAllowed, true) . "\n";
        
        $isAllowed = $acl->isAllowed($role, $resource, true);

        echo $role . ' + privilige :: ' . var_export($isAllowed, true) . "\n";
    }
    
    echo "\n";
}

echo "\n</pre>\n\n";

