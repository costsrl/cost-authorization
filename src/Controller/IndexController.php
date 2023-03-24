<?php
/**
 * Laminas Framework (http://framework.Laminas.com/)
 *
 * @link      http://github.com/Laminasframework/AclApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Laminas Technologies USA Inc. (http://www.Laminas.com)
 * @license   http://framework.Laminas.com/license/new-bsd New BSD License
 */

namespace CostAuthorization\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }

    public function acldenieAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        
        $oEvent = $this->getEvent();
        $routeMatch = $oEvent->getRouteMatch();
        return array(
            'controller'=>$routeMatch->getParam('controller'),
            'action'=>$routeMatch->getParam('action'),
        );
    }
}
