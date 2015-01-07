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

$resourceParents = array
(
    array
    (
        'child'     => 'module-submodule',
        'parent'    => 'module',
    ),
    array
    (
        'child'     => 'module-submodule-controller',
        'parent'    => 'module-submodule',
    ),
    array
    (
        'child'     => array
        (
            'module-submodule-controller-action',
            'module-submodule-controller-action1',
            'module-submodule-controller-action2',
            'module-submodule-controller-action3',
            'module-submodule-controller-action4',
            'module-submodule-controller-action5',
        ),
        'parent'    => 'module-submodule-controller',
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
        'child'     => 'editor',
        'parent'    => 'guest',
    ),
    array
    (
        'child'     => 'moderator',
        'parent'    => 'editor',
    ),
    array
    (
        'child'     => 'admin',
        'parent'    => 'moderator',
    ),
);

$permissions = array
(
    array
    (
        'role'          => 'root',
        'resource'      => 'module',
        'permission'    => '1',
    ),
    array
    (
        'role'          => 'guest',
        'resource'      => 'module',
        'permission'    => '0',
    ),
    array
    (
        'role'          => 'guest',
        'resource'      => 'module-submodule-controller-action',
        'permission'    => '1',
    ),
    array
    (
        'role'          => 'editor',
        'resource'      => 'module-submodule-controller',
        'permission'    => '1',
    ),
    array
    (
        'role'          => 'moderator',
        'resource'      => 'module-submodule',
        'permission'    => '1',
    ),
);

foreach ($roleParents as $roleParent)
{
    $roles[] = $roleParent['parent'];

    $roles[] = $roleParent['child'];
}

$roles = array_values($roles);

$roles = array_combine($roles, $roles);

$resources = array();

foreach ($resourceParents as $resourceParent)
{
    $parent = $resourceParent['parent'];
    
    $childs = $resourceParent['child'];
    
    $resources[] = $parent;
    
    if (!is_array($childs))
    {
        $childs = array
        (
            $childs,
        );
    }
    
    foreach ($childs as $child)
    {
        $resources[] = $child;
    }
}

$resources = array_values($resources);

$resources = array_combine($resources, $resources);

foreach ($resources as $resource)
{
    $param = $resource;
    
    #$aclCms->addReturnResource($param);
}

foreach ($resourceParents as $resourceParent)
{
    $param = $resourceParent;
    
    $aclCms->setParentResource($param['child'], $param['parent']);
}

foreach ($roles as $role)
{
    $param = $role;
    
    $aclCms->addReturnRole($param);
}

foreach ($roleParents as $roleParent)
{
    $param = $roleParent;
    
    $aclCms->setParentRole($param['child'], $param['parent']);
}

foreach ($permissions as $permission)
{
    $param = $permission;
    
    if ($param['permission'])
    {
        $aclCms->allow($param['role'], $param['resource']);
    }
    else
    {
        $aclCms->deny($param['role'], $param['resource']);
    }
}

//$aclCms->getAclObject(true);

echo "<pre>\n\n";

foreach ($resources as $resource)
{
    echo $resource . " :: \n";

    foreach ($roles as $role)
    {
        $isAllowed = $aclCms->isAllowed($role, $resource);

        echo $role . ' :: ' . var_export($isAllowed, true) . "\n";
        
        $isAllowed = $aclCms->isAllowed($role, $resource, true);

        echo $role . ' + privilige :: ' . var_export($isAllowed, true) . "\n";
    }
    
    echo "\n";
}

echo "\n</pre>\n\n";

