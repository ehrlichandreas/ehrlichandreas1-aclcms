<?php

/**
 * 
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AclCms_ModuleExtended extends EhrlichAndreas_AclCms_Module {

    /**
     *
     * @var Zend_Acl 
     */
    protected $acl = null;

    public function __construct($options = array())
    {
        parent::__construct($options);
    }

    /**
     * 
     */
    public function buildAcl()
    {
        if (is_null($this->acl))
        {
            $this->acl = new Zend_Acl();
        }
        
        $this->acl->removeAll();

        $permissions = $this->getPermissionList();

        $resources = $this->getResourceList();

        $resourceParents = $this->getResourceParentList();

        $roles = $this->getRoleList();

        $roleParents = $this->getRoleParentList();

        $rolesTmp = array();

        foreach ($roles as $role)
        {
            $roleId = $role['role_id'];

            $roleName = $role['role_name'];

            $rolesTmp[$roleId] = array
            (
                'name' => $roleId,
                'parents' => array(),
            );

            $rolesTmp[$roleName] = array
            (
                'name' => $roleName,
                'parents' => array
                (
                    $roleId,
                ),
            );
        }

        foreach ($roleParents as $roleParent)
        {
            $roleId = $roleParent['role_id'];

            $roleIdParent = $roleParent['role_id_parent'];

            $rolesTmp[$roleId]['parents'][] = $roleIdParent;
        }

        foreach ($rolesTmp as $role)
        {
            $this->acl->addRole($role['name'], $role['parents']);
        }

        #echo '<pre>';

        $resourcesTmp = array();

        foreach ($resources as $resource)
        {
            $resourceId = $resource['resource_id'];

            $resourceName = $resource['resource_name'];

            $resourcesTmp[$resourceId] = array
            (
                'name' => $resourceId,
                'parent' => null,
            );

            $resourcesTmp[$resourceName] = array
                (
                'name' => $resourceName,
                'parent' => $resourceId,
            );
        }

        foreach ($resourceParents as $resourceParent)
        {
            $resourceId = $resourceParent['resource_id'];

            $resourceIdParent = $resourceParent['resource_id_parent'];

            $resourcesTmp[$resourceId]['parent'] = $resourceIdParent;
        }

        foreach ($resourcesTmp as $resource)
        {
            $this->acl->addResource($resource['name'], $resource['parent']);
        }

        foreach ($permissions as $permission)
        {
            if (empty($permission['allowed'])) 
            {
                $this->acl->deny($permission['role_id'], $permission['resource_id']);
            }
            else
            {
                $this->acl->allow($permission['role_id'], $permission['resource_id']);
            }
        }
    }

    /**
     * 
     * @param boolean $rebuildAcl
     * @return Zend_Acl
     */
    public function getAclObject($rebuildAcl = false)
    {
        $build = false;

        if (is_null($this->acl))
        {
            $this->buildAcl();
        }

        if (!$build && !empty($rebuildAcl))
        {
            $this->buildAcl();
        }

        return $this->acl;
    }

    /**
     * 
     * @param array $resource
     * @return array
     */
    public function addReturnResource($resource)
    {
        if (empty($resource))
        {
            return false;
        }

        if (!is_array($resource))
        {
            $resources = array($resource);
        }
        else
        {
            $resources = $resource;
        }

        $resources = array_values($resources);

        $resources = array_combine($resources, $resources);

        $param = array
        (
            'resource_name' => $resources,
        );

        $rowset = $this->getResourceList($param);

        $resource_ids = array();

        foreach ($rowset as $row) 
        {
            $resource_ids[$row['resource_id']] = $row['resource_id'];
        }

        if (count($rowset) == count($resources))
        {
            return $resource_ids;
        }

        $resources_tmp = array();

        foreach ($rowset as $row)
        {
            $resources_tmp[$row['resource_name']] = $row['resource_name'];
        }

        foreach ($resources as $resource)
        {
            if (!isset($resources_tmp[$resource]))
            {
                $param = array
                (
                    'resource_name' => $resource,
                );

                $this->addResource($param);
            }
        }

        $param = array
        (
            'cols' => array
            (
                'resource_id' => 'resource_id',
            ),
            'resource_name' => $resources,
        );

        $rowset = $this->getResourceList($param);

        $resource_ids = array();

        foreach ($rowset as $row)
        {
            $resource_ids[$row['resource_id']] = $row['resource_id'];
        }

        return $resource_ids;
    }

    /**
     * 
     * @param array $role
     * @return array
     */
    public function addReturnRole($role)
    {
        if (empty($role))
        {
            return false;
        }

        if (!is_array($role))
        {
            $roles = array($role);
        }
        else
        {
            $roles = $role;
        }

        $roles = array_values($roles);

        $roles = array_combine($roles, $roles);

        $param = array
        (
            'role_name' => $roles,
        );

        $rowset = $this->getRoleList($param);

        $role_ids = array();

        foreach ($rowset as $row)
        {
            $role_ids[$row['role_id']] = $row['role_id'];
        }

        if (count($rowset) == count($roles))
        {
            return $role_ids;
        }

        $roles_tmp = array();

        foreach ($rowset as $row)
        {
            $roles_tmp[$row['role_name']] = $row['role_name'];
        }

        foreach ($roles as $role)
        {
            if (!isset($roles_tmp[$role]))
            {
                $param = array
                (
                    'role_name' => $role,
                );

                $this->addRole($param);
            }
        }

        $param = array
        (
            'cols' => array
            (
                'role_id' => 'role_id',
            ),
            'role_name' => $roles,
        );

        $rowset = $this->getRoleList($param);

        $role_ids = array();

        foreach ($rowset as $row)
        {
            $role_ids[$row['role_id']] = $row['role_id'];
        }

        return $role_ids;
    }

    /**
     * 
     * @param array $role
     * @param array $resource
     * @param boolean $permission
     * @return boolean
     */
    public function setPermission($role, $resource, $permission)
    {
        $resource_ids = $this->addReturnResource($resource);

        $role_ids = $this->addReturnRole($role);

        if ($resource_ids === false || count($resource_ids) == 0)
        {
            return false;
        }

        if ($role_ids === false || count($role_ids) == 0)
        {
            return false;
        }

        $permission = intval(((bool) ($permission)));

        $param = array
        (
            'allowed' => $permission,
            'where' => array
            (
                'role_id' => $role_ids,
                'resource_id' => $resource_ids,
            ),
        );

        $this->editPermission($param);

        $param = array
        (
            'where' => array
            (
                'role_id' => $role_ids,
                'resource_id' => $resource_ids,
            ),
        );

        $rowset = $this->getPermission($param);

        $var = array();

        foreach ($rowset as $row)
        {
            $var[$row['role_id']][$row['resource_id']] = '1';
        }

        foreach ($role_ids as $role_id)
        {
            foreach ($resource_ids as $resource_id)
            {
                if (!isset($var[$role_id][$resource_id]))
                {
                    $param = array
                    (
                        'role_id' => $role_id,
                        'resource_id' => $resource_id,
                        'allowed' => $permission,
                    );

                    $this->addPermission($param);
                }
            }
        }

        return true;
    }

    /**
     * 
     * @param array $role
     * @param array $resource
     * @return boolean
     */
    public function allow($role, $resource)
    {
        return $this->setPermission($role, $resource, 1);
    }

    /**
     * 
     * @param array $role
     * @param array $resource
     * @return boolean
     */
    public function deny($role, $resource)
    {
        return $this->setPermission($role, $resource, 0);
    }

    /**
     * Returns true if and only if the Role has access to the Resource
     *
     * The $role and $resource parameters may be references to, or the string identifiers for,
     * an existing Resource and Role combination.
     *
     * If either $role or $resource is null, then the query applies to all Roles or all Resources,
     * respectively. Both may be null to query whether the ACL has a "blacklist" rule
     * (allow everything to all). By default, Zend_Acl creates a "whitelist" rule (deny
     * everything to all), and this method would return false unless this default has
     * been overridden (i.e., by executing $acl->allow()).
     *
     * If a $privilege is not provided, then this method returns false if and only if the
     * Role is denied access to at least one privilege upon the Resource. In other words, this
     * method returns true if and only if the Role is allowed all privileges on the Resource.
     *
     * This method checks Role inheritance using a depth-first traversal of the Role registry.
     * The highest priority parent (i.e., the parent most recently added) is checked first,
     * and its respective parents are checked similarly before the lower-priority parents of
     * the Role are checked.
     *
     * @param  Zend_Acl_Role_Interface|string     $role
     * @param  Zend_Acl_Resource_Interface|string $resource
     * @param  string                             $privilege
     * @uses   Zend_Acl::isAllowed()
     * @return boolean
     */
    public function isAllowed($role = null, $resource = null, $privilege = null, $rebuildAcl = false)
    {
        $acl = $this->getAclObject($rebuildAcl);

        return $acl->isAllowed($role, $resource, $privilege);
    }

    /**
     * 
     * @param array $resource
     * @param array $resourceParent
     * @return boolean
     */
    public function setParentResource($resource, $resourceParent)
    {
        $resource_ids_parent = $this->addReturnResource($resourceParent);

        $resource_ids = $this->addReturnResource($resource);

        if ($resource_ids === false || count($resource_ids) == 0)
        {
            return false;
        }

        if ($resource_ids_parent === false || count($resource_ids_parent) == 0)
        {
            return false;
        }

        $param = array
        (
            'where' => array
            (
                'resource_id' => $resource_ids,
                'resource_id_parent' => $resource_ids_parent,
            ),
        );

        $rowset = $this->getResourceParent($param);

        $var = array();

        foreach ($rowset as $row)
        {
            $var[$row['resource_id']][$row['resource_id_parent']] = '1';
        }

        foreach ($resource_ids as $resource_id)
        {
            foreach ($resource_ids_parent as $resource_id_parent)
            {
                if (!isset($var[$resource_id][$resource_id_parent]))
                {
                    $param = array
                    (
                        'resource_id' => $resource_id,
                        'resource_id_parent' => $resource_id_parent,
                    );

                    $this->addResourceParent($param);
                }
            }
        }

        return true;
    }

    public function setParentRole($role, $roleParent)
    {
        $role_ids_parent = $this->addReturnRole($roleParent);

        $role_ids = $this->addReturnRole($role);

        if ($role_ids === false || count($role_ids) == 0)
        {
            return false;
        }

        if ($role_ids_parent === false || count($role_ids_parent) == 0)
        {
            return false;
        }

        $param = array
        (
            'where' => array
            (
                'role_id' => $role_ids,
                'role_id_parent' => $role_ids_parent,
            ),
        );

        $rowset = $this->getRoleParent($param);

        $var = array();

        foreach ($rowset as $row)
        {
            $var[$row['role_id']][$row['role_id_parent']] = '1';
        }

        foreach ($role_ids as $role_id)
        {
            foreach ($role_ids_parent as $role_id_parent)
            {
                if (!isset($var[$role_id][$role_id_parent]))
                {
                    $param = array
                    (
                        'role_id' => $role_id,
                        'role_id_parent' => $role_id_parent,
                    );

                    $this->addRoleParent($param);
                }
            }
        }

        return true;
    }

}
