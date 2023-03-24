<?php
namespace CostAuthorization;

$fixtureResource    = include "fixture.resource.config.php";
$fixturePermission  = include "fixture.permission.config.php";

return array(
    'doctrine' =>[
        'driver' =>[
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Model/Entity']
            ],
            'orm_default' =>['drivers' => [__NAMESPACE__ . '\Model\Entity' => __NAMESPACE__ . '_driver']]
        ],
        'fixture' => [
            'CostAuthorization' => __DIR__ . '/../src/Fixture'
        ]
    ],
    'service_manager' => array(
        'factories' => array(
            'acl' => function ($sm) {
                $config = $sm->get('config');
                if ($config['acl']['use_database_storage'])
                    return new \CostAuthorization\Acl\AclDb($sm);
                else
                    return new \CostAuthorization\Acl\Acl($config);
            },
            'aclDoctrine' => function ($sm) {
                $config = $sm->get('config');
                if ($config['acl']['use_database_storage'])
                    return new \CostAuthorization\Acl\AclDoctrine($sm->get('doctrine.entitymanager.orm_default'));
                else
                    return new \CostAuthorization\Acl\Acl($config);
            }
        ),
        'services' => array(
            'controllers_no_acl' => [],
            'resources-fixture' =>  $fixtureResource,
            'permissions-fixture'=> $fixturePermission
        )
    )
    ,
    'controllers' => array(
        'invokables' => array(
            Controller\IndexController::class => 'CostAuthorization\Controller\IndexController'
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'isAllowed' => function ($sm) {
                $sm     = $sm->getServiceLocator(); // $sm was the view helper's locator
                $auth   = $sm->get('Laminas\Authentication\AuthenticationService');
                $acl    = $sm->get('aclDoctrine');       
                $helper = new \CostAuthorization\View\Helper\IsAllowed($auth, $acl);
                return $helper;
            }
        )
    ),
    'controller_plugins' => array(
        'factories' => array(
            'isAllowed' => function ($sm) {
                $sm     = $sm->getServiceLocator(); // $sm was the view helper's locator
                $auth   = $sm->get('Laminas\Authentication\AuthenticationService');
                $acl    = $sm->get('aclDoctrine');             
                $plugin = new \CostAuthorization\Controller\Plugin\IsAllowed($auth, $acl);
                return $plugin;
            }
        )
    ),
    'router' => array(
        'routes' => array(
            'acl-application' => array(
                'type' => 'Literal',
                'options' => array(
                    
                    // Change this to something specific to your module
                    'route' => '/index',
                    'defaults' => array(
                        
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'CostAuthorization\Controller',
                        'controller' =>  Controller\IndexController::class,
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array(
                                'controller' =>  Controller\IndexController::class,
                                'action' => 'index'
                            )
                        )
                    )
                )
            ),
            'acl-denie' => array(
                'type' => 'Literal',
                'options' => array(                   
                    // Change this to something specific to your module
                    'route' => '/accessdenie',
                    'defaults' => array(
                        
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'CostAuthorization\Controller',
                        'controller' =>  Controller\IndexController::class,
                        'action' => 'acldenie'
                    )
                )
            )
        )
    ),
    'table-gateway' =>[
        'map' => [
            'roles'         => 'CostAuthorization\Model\TableGateway\Role',
            'resources'     => 'CostAuthorization\Model\TableGateway\Resource',
            'permissions'   => 'CostAuthorization\Model\TableGateway\Permission',
        ]
    ],
    'view_manager' => array(
        'template_path_stack' => array(
            'CostAuthorization' => __DIR__ . '/../view'
        )
    )
    
);