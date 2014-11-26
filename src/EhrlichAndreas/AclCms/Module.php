<?php 

/**
 * 
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AclCms_Module extends EhrlichAndreas_AbstractCms_Module
{
    /**
     *
     * @var string
     */
    private $tablePermission = 'acl_permission';

    /**
     *
     * @var string
     */
    private $tableResource = 'acl_resource';

    /**
     *
     * @var string
     */
    private $tableResourceParent = 'acl_resource_parent';
    
    /**
     *
     * @var string
     */
    private $tableRole = 'acl_role';

    /**
     *
     * @var string
     */
    private $tableRoleParent = 'acl_role_parent';
    
    /**
     * Constructor
     * 
     * @param array $options
     *            Associative array of options
     * @throws EhrlichAndreas_NewsletterCms_Exception
     * @return void
     */
    public function __construct ($options = array())
    {
        $options = $this->_getCmsConfigFromAdapter($options);
        
        if (! isset($options['adapterNamespace']))
        {
            $options['adapterNamespace'] = 'EhrlichAndreas_AclCms_Adapter';
        }
        
        if (! isset($options['exceptionclass']))
        {
            $options['exceptionclass'] = 'EhrlichAndreas_AclCms_Exception';
        }
        
        parent::__construct($options);
    }
    
    /**
     * 
     * @return EhrlichAndreas_NewsletterCms_Module
     */
    public function install()
    {
        $this->adapter->install();
        
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getTablePermission ()
    {
        return $this->adapter->getTableName($this->tablePermission);
    }

    /**
     * 
     * @return string
     */
    public function getTableResource ()
    {
        return $this->adapter->getTableName($this->tableResource);
    }

    /**
     * 
     * @return string
     */
    public function getTableResourceParent ()
    {
        return $this->adapter->getTableName($this->tableResourceParent);
    }

    /**
     * 
     * @return string
     */
    public function getTableRole ()
    {
        return $this->adapter->getTableName($this->tableRole);
    }

    /**
     * 
     * @return string
     */
    public function getTableRoleParent ()
    {
        return $this->adapter->getTableName($this->tableRoleParent);
    }

    /**
     * 
     * @return array
     */
    public function getFieldsPermission ()
    {
        return array
		(
			'permission_id' => 'permission_id',
            'published'     => 'published',
            'updated'       => 'updated',
            'enabled'       => 'enabled',
            'role_id'       => 'role_id',
            'project_id'    => 'project_id',
            'allowed'       => 'allowed',
		);
    }

    /**
     * 
     * @return array
     */
    public function getFieldsResource ()
    {
        return array
		(
			'resource_id'           => 'resource_id',
            'published'             => 'published',
            'updated'               => 'updated',
            'enabled'               => 'enabled',
            'resource_name'         => 'name',
            'resource_description'  => 'description',
		);
    }

    /**
     * 
     * @return array
     */
    public function getFieldsResourceParent ()
    {
        return array
		(
			'resource_parent_id'    => 'resource_parent_id',
            'published'             => 'published',
            'updated'               => 'updated',
            'enabled'               => 'enabled',
            'resource_id'           => 'resource_id',
            'resource_id_parent'    => 'resource_id_parent',
		);
    }

    /**
     * 
     * @return array
     */
    public function getFieldsRole ()
    {
        return array
		(
			'role_id'           => 'role_id',
            'published'         => 'published',
            'updated'           => 'updated',
            'enabled'           => 'enabled',
            'role_name'         => 'name',
            'role_description'  => 'description',
		);
    }

    /**
     * 
     * @return array
     */
    public function getFieldsRoleParent ()
    {
        return array
		(
			'role_parent_id'    => 'role_parent_id',
            'published'         => 'published',
            'updated'           => 'updated',
            'enabled'           => 'enabled',
            'role_id'           => 'role_id',
            'role_id_parent'    => 'role_id_parent',
		);
    }

    /**
     * 
     * @return array
     */
    public function getKeyFieldsPermission ()
    {
        return array
		(
			'permission_id' => 'permission_id',
		);
    }

    /**
     * 
     * @return array
     */
    public function getKeyFieldsResource ()
    {
        return array
		(
			'resource_id'   => 'resource_id',
		);
    }

    /**
     * 
     * @return array
     */
    public function getKeyFieldsResourceParent ()
    {
        return array
		(
			'resource_parent_id'    => 'resource_parent_id',
		);
    }

    /**
     * 
     * @return array
     */
    public function getKeyFieldsRole ()
    {
        return array
		(
			'role_id'   => 'role_id',
		);
    }

    /**
     * 
     * @return array
     */
    public function getKeyFieldsRoleParent ()
    {
        return array
		(
			'role_parent_id'    => 'role_parent_id',
		);
    }

	/**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
    public function addPermission ($params = array(), $returnAsString = false)
    {
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['published']) || $params['published'] == '0000-00-00 00:00:00' || $params['published'] == '')
        {
            $params['published'] = date('Y-m-d H:i:s', time());
        }
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        if (! isset($params['enabled']))
        {
            $params['enabled'] = '1';
        }
        if (! isset($params['role_id']))
        {
            $params['role_id'] = '0';
        }
        if (! isset($params['project_id']))
        {
            $params['project_id'] = '0';
        }
        if (! isset($params['allowed']))
        {
            $params['allowed'] = '0';
        }
		
		$function = 'Permission';
		
		return $this->_add($function, $params, $returnAsString);
    }

	/**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
    public function addResource ($params = array(), $returnAsString = false)
    {
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['published']) || $params['published'] == '0000-00-00 00:00:00' || $params['published'] == '')
        {
            $params['published'] = date('Y-m-d H:i:s', time());
        }
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        if (! isset($params['enabled']))
        {
            $params['enabled'] = '1';
        }
        if (! isset($params['resource_name']))
        {
            $params['resource_name'] = '';
        }
        if (! isset($params['resource_description']))
        {
            $params['resource_description'] = '';
        }
		
		$function = 'Resource';
		
		return $this->_add($function, $params, $returnAsString);
    }

	/**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
    public function addResourceParent ($params = array(), $returnAsString = false)
    {
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['published']) || $params['published'] == '0000-00-00 00:00:00' || $params['published'] == '')
        {
            $params['published'] = date('Y-m-d H:i:s', time());
        }
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        if (! isset($params['enabled']))
        {
            $params['enabled'] = '1';
        }
        if (! isset($params['resource_id']))
        {
            $params['resource_id'] = '0';
        }
        if (! isset($params['resource_id_parent']))
        {
            $params['resource_id_parent'] = '0';
        }
		
		$function = 'ResourceParent';
		
		return $this->_add($function, $params, $returnAsString);
    }

	/**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
    public function addRole ($params = array(), $returnAsString = false)
    {
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['published']) || $params['published'] == '0000-00-00 00:00:00' || $params['published'] == '')
        {
            $params['published'] = date('Y-m-d H:i:s', time());
        }
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        if (! isset($params['enabled']))
        {
            $params['enabled'] = '1';
        }
        if (! isset($params['role_name']))
        {
            $params['role_name'] = '';
        }
        if (! isset($params['role_description']))
        {
            $params['role_description'] = '';
        }
		
		$function = 'Role';
		
		return $this->_add($function, $params, $returnAsString);
    }

	/**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
    public function addRoleParent ($params = array(), $returnAsString = false)
    {
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['published']) || $params['published'] == '0000-00-00 00:00:00' || $params['published'] == '')
        {
            $params['published'] = date('Y-m-d H:i:s', time());
        }
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        if (! isset($params['enabled']))
        {
            $params['enabled'] = '1';
        }
        if (! isset($params['role_id']))
        {
            $params['role_id'] = '0';
        }
        if (! isset($params['role_id_parent']))
        {
            $params['role_id_parent'] = '0';
        }
		
		$function = 'RoleParent';
		
		return $this->_add($function, $params, $returnAsString);
    }
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function deletePermission ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
		$function = 'Permission';
		
		return $this->_delete($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function deleteResource ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
		$function = 'Resource';
		
		return $this->_delete($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function deleteResourceParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
		$function = 'ResourceParent';
		
		return $this->_delete($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function deleteRole ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
		$function = 'Role';
		
		return $this->_delete($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function deleteRoleParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
		$function = 'RoleParent';
		
		return $this->_delete($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function editPermission ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '0001-01-01 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = date('Y-m-d H:i:s', time());
        }
		
		$function = 'Permission';
		
		return $this->_edit($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function editResource ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '0001-01-01 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = date('Y-m-d H:i:s', time());
        }
		
		$function = 'Resource';
		
		return $this->_edit($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function editResourceParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '0001-01-01 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = date('Y-m-d H:i:s', time());
        }
		
		$function = 'ResourceParent';
		
		return $this->_edit($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function editRole ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '0001-01-01 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = date('Y-m-d H:i:s', time());
        }
		
		$function = 'Role';
		
		return $this->_edit($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function editRoleParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00' || $params['updated'] == '0001-01-01 00:00:00' || $params['updated'] == '')
        {
            $params['updated'] = date('Y-m-d H:i:s', time());
        }
		
		$function = 'RoleParent';
		
		return $this->_edit($function, $params, $returnAsString);
	}

    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
    public function getPermission ($params = array(), $returnAsString = false)
    {
		$function = 'Permission';
		
		return $this->_get($function, $params, $returnAsString);
    }

    /**
     *
     * @param array $where
     * @return array
     */
    public function getPermissionList ($where = array())
    {
		$function = 'Permission';
		
		return $this->_getList($function, $where);
    }

    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
    public function getResource ($params = array(), $returnAsString = false)
    {
		$function = 'Resource';
		
		return $this->_get($function, $params, $returnAsString);
    }

    /**
     *
     * @param array $where
     * @return array
     */
    public function getResourceList ($where = array())
    {
		$function = 'Resource';
		
		return $this->_getList($function, $where);
    }

    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
    public function getResourceParent ($params = array(), $returnAsString = false)
    {
		$function = 'ResourceParent';
		
		return $this->_get($function, $params, $returnAsString);
    }

    /**
     *
     * @param array $where
     * @return array
     */
    public function getResourceParentList ($where = array())
    {
		$function = 'ResourceParent';
		
		return $this->_getList($function, $where);
    }

    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
    public function getRole ($params = array(), $returnAsString = false)
    {
		$function = 'Role';
		
		return $this->_get($function, $params, $returnAsString);
    }

    /**
     *
     * @param array $where
     * @return array
     */
    public function getRoleList ($where = array())
    {
		$function = 'Role';
		
		return $this->_getList($function, $where);
    }

    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
    public function getRoleParent ($params = array(), $returnAsString = false)
    {
		$function = 'RoleParent';
		
		return $this->_get($function, $params, $returnAsString);
    }

    /**
     *
     * @param array $where
     * @return array
     */
    public function getRoleParentList ($where = array())
    {
		$function = 'RoleParent';
		
		return $this->_getList($function, $where);
    }
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function disablePermission ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '0';
		
		return $this->editPermission($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function disableResource ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '0';
		
		return $this->editResource($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function disableResourceParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '0';
		
		return $this->editResourceParent($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function disableRole ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '0';
		
		return $this->editRole($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function disableRoleParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '0';
		
		return $this->editRoleParent($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function enablePermission ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '1';
		
		return $this->editPermission($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function enableResource ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '1';
		
		return $this->editResource($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function enableResourceParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '1';
		
		return $this->editResourceParent($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function enableRole ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '1';
		
		return $this->editRole($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function enableRoleParent ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '1';
		
		return $this->editRoleParent($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function softDeletePermission ($params = array(), $returnAsString = false)
	{
		return $this->disablePermission($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function softDeleteResource ($params = array(), $returnAsString = false)
	{
		return $this->disableResource($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function softDeleteResourceParent ($params = array(), $returnAsString = false)
	{
		return $this->disableResourceParent($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function softDeleteRole ($params = array(), $returnAsString = false)
	{
		return $this->disableRole($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function softDeleteRoleParent ($params = array(), $returnAsString = false)
	{
		return $this->disableRoleParent($params, $returnAsString);
	}
}
