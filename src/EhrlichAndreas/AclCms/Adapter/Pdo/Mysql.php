<?php 

/**
 *
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_AclCms_Adapter_Pdo_Mysql extends EhrlichAndreas_AbstractCms_Adapter_Pdo_Mysql
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
     *
     * @var string 
     */
    protected $tableVersion = 'acl_version';
    
    /**
     * 
     * @return EhrlichAndreas_NewsletterCms_Adapter_Pdo_Mysql
     */
    public function install ()
    {
        $this->_install_version_10000();
        
        return $this;
    }
    
    /**
     * 
     * @return EhrlichAndreas_NewsletterCms_Adapter_Pdo_Mysql
     */
    protected function _install_version_10000 ()
    {
        $version = '10000';
        
        $dbAdapter = $this->getConnection();
        
        $tableVersion = $this->getTableName($this->tableVersion);
        
        $versionDb = $this->_getVersion($dbAdapter, $tableVersion);
        
        if ($versionDb >= $version)
        {
            return $this;
        }
		
        $tablePermission = $this->getTableName($this->tablePermission);
        
        $tableResource = $this->getTableName($this->tableResource);
        
        $tableResourceParent = $this->getTableName($this->tableResourceParent);
        
        $tableRole = $this->getTableName($this->tableRole);
        
        $tableRoleParent = $this->getTableName($this->tableRoleParent);
		
        
        $queries = array();
        
        
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`num` BIGINT(19) NOT NULL AUTO_INCREMENT, ';
        $query[] = '`count` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = 'PRIMARY KEY (`num`) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';
		
		$queries[] = str_replace('%table%', $tableVersion, implode("\n", $query));
        
        
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`permission_id` BIGINT(19) unsigned NOT NULL AUTO_INCREMENT, ';
        $query[] = '`published` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`updated` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`enabled` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = '`role_id` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = '`project_id` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = '`allowed` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = 'PRIMARY KEY (`permission_id`), ';
        $query[] = 'KEY `idx_role_project_id` (`role_id` (19), `project_id` (19)), ';
        $query[] = 'KEY `idx_project_role_id` (`project_id` (19), `role_id` (19)) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';
		
		$queries[] = str_replace('%table%', $tablePermission, implode("\n", $query));

        
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`resource_id` BIGINT(19) unsigned NOT NULL AUTO_INCREMENT, ';
        $query[] = '`published` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`updated` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`enabled` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = '`resource_name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\', ';
        $query[] = '`resource_description` TEXT COLLATE utf8_unicode_ci NOT NULL, ';
        $query[] = 'PRIMARY KEY (`resource_id`), ';
        $query[] = 'KEY `idx_name` (`name`) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';
		
		$queries[] = str_replace('%table%', $tableResource, implode("\n", $query));

        
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`resource_parent_id` BIGINT(19) unsigned NOT NULL AUTO_INCREMENT, ';
        $query[] = '`published` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`updated` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`enabled` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = '`resource_id` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = '`resource_id_parent` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = 'PRIMARY KEY (`resource_parent_id`), ';
        $query[] = 'KEY `idx_resource_parent` (`resource_id`, `resource_id_parent`), ';
        $query[] = 'KEY `idx_parent_resource` (`resource_id_parent`, `resource_id`) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';
		
		$queries[] = str_replace('%table%', $tableResourceParent, implode("\n", $query));

        
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`role_id` BIGINT(19) unsigned NOT NULL AUTO_INCREMENT, ';
        $query[] = '`published` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`updated` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`enabled` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = '`role_name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\', ';
        $query[] = '`role_description` TEXT COLLATE utf8_unicode_ci NOT NULL, ';
        $query[] = 'PRIMARY KEY (`role_id`), ';
        $query[] = 'KEY `idx_name` (`name`) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';
		
		$queries[] = str_replace('%table%', $tableRole, implode("\n", $query));

        
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`role_parent_id` BIGINT(19) unsigned NOT NULL AUTO_INCREMENT, ';
        $query[] = '`published` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`updated` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`enabled` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = '`role_id` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = '`role_id_parent` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = 'PRIMARY KEY (`role_parent_id`), ';
        $query[] = 'KEY `idx_role_parent` (`role_id`, `role_id_parent`), ';
        $query[] = 'KEY `idx_parent_role` (`role_id_parent`, `role_id`) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';
		
		$queries[] = str_replace('%table%', $tableRoleParent, implode("\n", $query));
		
		
		if ($versionDb < $version)
		{
            foreach ($queries as $query)
            {
                $stmt = $dbAdapter->query($query);

                $stmt->closeCursor();
            }
            
			$this->_setVersion($dbAdapter, $tableVersion, $version);
		}
		
		return $this;
    }
}