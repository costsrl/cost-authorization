<?php
/**
 * Coolcsn Laminas Framework 2 Authorization Module
 * 
 */
namespace CostAuthorization\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class IsAllowed extends AbstractPlugin {

    protected $auth;
    protected $acl;

    public function __construct($auth, $acl) {
        $this->auth = $auth;
        $this->acl = $acl;
    }

    /**
     * Checks whether the current user has acces to a resource.
     * 
     * @param string $resource
     * @param string $privilege
     */
    public function __invoke($resource, $privilege = null) {
        if ($this->auth->hasIdentity()) {
            $user = $this->auth->getIdentity()->getRole()->getName();
            if (!$this->acl->hasResource($resource)) {
                throw new \Exception('Resource ' . $resource . ' not defined');
            }
            return $this->acl->isAllowed($user, $resource, $privilege);
        } else {
            return $this->acl->isAllowed(\CostAuthorization\Acl\Acl::DEFAULT_ROLE, $resource, $privilege);
        }
    }

}
