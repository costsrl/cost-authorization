<?php
namespace CostAuthorization;

use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Permissions\Acl\Acl;
use Laminas\Console\Request as ConsoleRequest;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

   

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'ProtectPage'), - 100);
    }

    /**
     *
     * @param \Laminas\EventManager\EventInterface $e
     * @return boolean
     */
    public function ProtectPage(\Laminas\EventManager\EventInterface $e)
    { // Event manager of the app
      // return true;
        $application            = $e->getApplication();
        $sm                     = $application->getServiceManager();
        $authService            = $sm->get('Laminas\Authentication\AuthenticationService');
        $request                = $e->getRequest();

        $routeMatch             = $e->getRouteMatch();
        $sController            = $routeMatch->getParam('controller');
        $sAction                = $routeMatch->getParam('action');

        $config                 = $sm->get('config');
        $redirect_route         = $config['acl']['redirect_route'];
        $action                 = $redirect_route['params']['action'];
        $controller             = $redirect_route['params']['controller'];

        if ($request instanceof ConsoleRequest) {
            return true;
        }

        $acl                    = $sm->get('aclDoctrine'); //$sm->get('acl');
        $aControllers_no_acl    = $sm->get('controllers_no_acl');
        // everyone is guest until logging in
        $sDefaultRole   = \CostAuthorization\Acl\Acl::DEFAULT_ROLE; // The default role is guest $acl


        if (in_array($sController, $aControllers_no_acl)) {
              return true;
        } elseif (!$authService->hasIdentity()) {
            $routeMatch->setParam('controller', \CostAuthentication\Controller\IndexController::class);
            $routeMatch->setParam('action', 'login');
        } else {
            $user = $authService->getIdentity();
            $role = ($user->getRole()) ? $user->getRole()->getName() : $sDefaultRole;
            $sController = (substr($sController,-10) == "Controller") ?  $sController : $sController."Controller";
            
           
            if (! $acl->hasResource($sController)) {          
                $routeMatch->setParam('controller',\CostAuthentication\Controller\IndexController::class);
                $routeMatch->setParam('action', 'acldenie');
            }
            
       

            if (! $acl->isAllowed($role, $sController, $sAction)) {
                $routeMatch->setParam('controller', \CostAuthentication\Controller\IndexController::class);
                $routeMatch->setParam('action', 'acldenie');
            }
       
        }
        

    }

    protected function redirectTo403($e, $sm, $identity)
    {
        $response       = $e->getResponse();
        $config         = $sm->get('config');
        $redirect_route = $config['acl']['redirect_route'];
        
        if (! empty($redirect_route)) {
            $url = $e->getRouter()->assemble($redirect_route['params'], $redirect_route['options']);
            $response->getHeaders()->addHeaderLine('Location', $url);
            // The HTTP response status code 302 Found is a common way of performing a redirection.
            // http://en.wikipedia.org/wiki/HTTP_302      
            $response->setStatusCode(403);
            $response->sendHeaders();
            return $response;
            
        } else {
            // Status code 403 responses are the result of the web server being configured to deny access,
            // for some reason, to the requested resource by the client.
            // http://en.wikipedia.org/wiki/HTTP_403
            $response->setStatusCode(403);
            $response->setContent('
                    <html>
                        <head>
                            <title>403 Forbidden</title>
                        </head>
                        <body>
                            <h1>403 Forbidden</h1>
                        </body>
                    </html>');
            return $response;
        }
    }
}