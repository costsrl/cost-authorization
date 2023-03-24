<?php
/**
 * Coolcsn Laminas Framework 2 Authorization Module
 *
 * @link https://github.com/coolcsn/CsnAuthorization for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAuthorization/blob/master/LICENSE BSDLicense
 * @author Stoyan Revov <st.revov@gmail.com>
*/

namespace CostAuthorization\Acl;

use Laminas\Permissions\Acl\Acl as LaminasAcl,
    Laminas\Permissions\Acl\Role\GenericRole as Role,
    Laminas\Permissions\Acl\Resource\GenericResource as Resource;
use CostAuthorization\Acl\Assertion\CallBack as CallBackAssertion;



/**
 * Class to handle Acl
 *
 * This class is for loading ACL defined in a database
 *
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAuthorization/blob/master/LICENSE BSDLicense
 */
class AclDoctrine extends LaminasAcl {
    /**
     * Default Role
     */
    const DEFAULT_ROLE = 'Guest';

    /**
     * Constructor
     *
     * @param $entityManager Inject Doctrine's entity manager to load ACL from Database
     * @return void
     */
    public function __construct($entityManager)
    {

        $roles      = $entityManager->getRepository('CostAuthorization\Model\Entity\Roles')->findCustomAll("parent","ASC");
        $resources  = $entityManager->getRepository('CostAuthorization\Model\Entity\Resources')->findAll();
        $privileges = $entityManager->getRepository('CostAuthorization\Model\Entity\Permissions')->findAll();
        $this->_addRoles($roles)
             ->_addAclRules($resources, $privileges);
    }

    /**
     * @param $roles
     */
    protected function _addRecursiveRole($role)
    {
        if (!$this->hasRole($role->getName())) {
                $roleParent = $role->getParent();
                if ($roleParent instanceof \CostAuthorization\Model\Entity\Roles) {
                    if (!$this->hasRole($roleParent->getName())) {
                        $roleParentParent = $roleParent->getParent();
                        if ($roleParentParent instanceof \CostAuthorization\Model\Entity\Roles) {
                            $this->addRole(new Role($role->getName()),$this->_addRecursiveRole($roleParent));
                        } else {
                            $this->addRole(new Role($role->getName()),$roleParent->getName());
                            return $role->getName();
                        }
                    } else {
                        $this->addRole(new Role($role->getName()),$roleParent->getName());
                        return $roleParent->getName();
                    }
                }
                else{
                    $this->addRole(new Role($role->getName()));
                    return $role->getName();
                }
        }
        else{
            return $role->getName();
        }

    }


    /**
     * Adds Roles to ACL
     *
     * @param array $roles
     * @return CsnAuthorization\Acl\AclDb
     */
    protected function _addRoles($roles)
    {
        foreach($roles as $role) {
            try{
                  $this->_addRecursiveRole($role);
            }
            catch (\Exception $e){
                  echo $e->getMessage();
            }
        }
        return $this;
    }

    /**
     * Adds Resources/privileges to ACL
     *
     * @param $resources
     * @param $privileges
     * @return User\Acl
     * @throws \Exception
     */
    protected function _addAclRules($resources, $privileges)
    {
        foreach ($resources as $resource) {
            if (!$this->hasResource($resource->getName())) {
                $this->addResource(new Resource($resource->getName()));
            }
        }
        
         
        foreach ($privileges as $privilege) {

            if($privilege->getAssertClass()){
                $assertion_class = null;
                $callBackClass = trim($privilege->getAssertClass());
                if($callBackClass){
                    $assertion_class = new  $callBackClass();
                    if(is_callable($assertion_class)){
                        $assertion_class = new CallBackAssertion($assertion_class);
                    }
                }
            }
            else{
                $assertion_class = null;
            }
            
           
           if($privilege->getPermissionAllow()== 1) {
                if($privilege->getPrivilege()=='*'){
                    $this->allow($privilege->getRole()->getName(), $privilege->getResource()->getName(), NULL,$assertion_class);
                }
                else{
                    $this->allow($privilege->getRole()->getName(), $privilege->getResource()->getName(), explode(',',$privilege->getPrivilege()),$assertion_class);
                }
            } else {
                if($privilege->getPrivilege()=='*')
                    $this->deny($privilege->getRole()->getName(), $privilege->getResource()->getName(),NULL,$assertion_class);
                else
                    $this->deny($privilege->getRole()->getName(), $privilege->getResource()->getName(), explode(',',$privilege->getPrivilege()),$assertion_class);
            }
        }
        return $this;
    }
}
