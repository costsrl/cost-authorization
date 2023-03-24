<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 10/10/18
 * Time: 16.12
 */
namespace CostAuthorization\Fixture;
use CostAuthorization\Model\Entity\Permissions;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CostAuthentication\Container\ContainerAwareTrait;
use CostAuthentication\Container\ContainerAwareInterface;


class PermissionsFixture extends AbstractFixture implements FixtureInterface,OrderedFixtureInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $aPermissionFixture = $this->container->get('permissions-fixture');
        foreach ($aPermissionFixture as $name => $permissions){
            $oResource = $this->getReference("resource-${permissions['resource']}");
            $oRole     = $this->getReference("role-${permissions['role']}");
            $oPermission = new Permissions();
            $oPermission->setName($name);
            $oPermission->setRole($oRole);
            $oPermission->setResource($oResource);
            $oPermission->setPrivilege($permissions["privilege"]);
            $oPermission->setPermissionAllow($permissions["permission_allow"]);
            $manager->persist($oPermission);
            $this->addReference("permission-$name", $oPermission);
            $manager->flush();
        }
    }

    public function getOrder(){
        return 4;
    }


}