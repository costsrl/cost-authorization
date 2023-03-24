<?php
/**
 * Laminas Framework 2 Authorization Module Table gataway
*/
namespace CostAuthorization\Acl;

use Laminas\Permissions\Acl\Acl as LaminasAcl, Laminas\Permissions\Acl\Role\GenericRole as Role, Laminas\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Class to handle Acl
 *
 * This class is for loading ACL defined in a database
 */
class AclDb extends LaminasAcl
{

    /**
     * Default Role
     */
    const DEFAULT_ROLE = 'Guest';

    /**
     * Constructor
     *
     * @param $entityManager Inject
     *            Doctrine's entity manager to load ACL from Database
     * @return void
     */
    public function __construct($sm)
    {
        $serviceTableGateway = $sm->get('table-gateway');
        
        $roles = $serviceTableGateway->get('roles')
            ->selectWith($serviceTableGateway->get('roles')
            ->getDefaultSql())
            ->toArray();
        $resources = $serviceTableGateway->get('resources')
            ->selectWith($serviceTableGateway->get('resources')
            ->getDefaultSql())
            ->toArray();
        $privileges = $serviceTableGateway->get('permissions')
            ->selectWith($serviceTableGateway->get('permissions')
            ->getDefaultSql())
            ->toArray();
        
        $this->_addRoles($roles)->_addAclRules($resources, $privileges);
    }

    /**
     * Adds Roles to ACL
     *
     * @param array $roles            
     * @return CsnAuthorization\Acl\AclDb
     */
    protected function _addRoles($roles)
    {
        $this->addRole(new Role(self::DEFAULT_ROLE));
        foreach ($roles as $role) {
            if (! $this->hasRole($role['name'])) {
                $parentNames = $role['parent'];
                if ($parentNames !== null) {
                    $this->addRole(new Role($role['name']), $parentNames);

                } else {
                    $this->addRole(new Role($role['name']));
                }
            }
        }
        
        return $this;
    }

    /**
     * Adds Resources/privileges to ACL
     *
     * @param
     *            $resources
     * @param
     *            $privileges
     * @return User\Acl
     * @throws \Exception
     */
    protected function _addAclRules($resources, $privileges)
    {
        foreach ($resources as $resource) {
            if (! $this->hasResource($resource['name'])) {
                $this->addResource(new Resource($resource['name']));
            }
        }
        
        foreach ($privileges as $privilege) {
            
            if ($privilege['assert_class']) {
                $callBackClass = $privilege['assert_class'];
                $assertion_class = new $callBackClass();
                if (is_callable($assertion_class)) {
                    $assertion_class = new CallBackAssertion($assertion_class);
                } else
                    $assertion_class = null;
            } else {
                $assertion_class = null;
            }
            
            if ($privilege['permission_allow'] == 1) {
                if ($privilege['privilege'] != '*') {
                    $this->allow($privilege['role_name'], $privilege['resource_name'], explode(',',$privilege['privilege']), $assertion_class);
                    // echo "allow".$privilege['ROLE_NAME'].'-'.$privilege['RESOURCE_NAME'].'-'.$privilege['PRIVILEGE'].'<br />';
                } else
                    $this->allow($privilege['role_name'], $privilege['resource_name'], null, $assertion_class);
            } else {
                if ($privilege['privilege'] != '*') {
                    $this->deny($privilege['role_name'], $privilege['resource_name'], explode(',',$privilege['privilege']), $assertion_class);
                    // echo "denie".$privilege['ROLE_NAME'].'-'.$privilege['RESOURCE_NAME'].'-'.$privilege['PRIVILEGE'].'<br />';
                } else
                    $this->deny($privilege['role_name'], $privilege['resource_name'], null, $assertion_class);
            }
        }
        
        return $this;
    }
}
