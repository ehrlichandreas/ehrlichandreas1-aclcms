<?php

/**
 * 
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AclCms_ModuleExtended extends EhrlichAndreas_AclCms_Module
{
    protected $acl = null;
    
    public function __construct($options = array())
    {
        parent::__construct($options);
        
        $this->acl = new Zend_Acl();
    }
    
    protected function buildAcl()
    {
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
                'name'      => $roleId,
                'parents'   => array(),
            );
            
            $rolesTmp[$roleName] = array
            (
                'name'      => $roleName,
                'parents'   => array
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
        
        $resourcesTmp = array();
        
        foreach ($resources as $resource)
        {
            $resourceId = $resource['resource_id'];
            
            $resourceName = $resource['resource_name'];
            
            $resourcesTmp[$resourceId] = array
            (
                'name'      => $resourceId,
                'parent'    => null,
            );
            
            $resourcesTmp[$resourceName] = array
            (
                'name'      => $resourceName,
                'parent'    => $resourceId,
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
     */
    public function getAclObject($rebuildAcl = false)
    {
        if (!empty($rebuildAcl))
        {
            $this->buildAcl();
        }
        
        return $this->acl;
    }
}

