<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 10/10/18
 * Time: 16.11
 */

namespace CostAuthorization\Fixture;
use CostAuthorization\Model\Entity\Resources;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CostAuthentication\Container\ContainerAwareTrait;
use CostAuthentication\Container\ContainerAwareInterface;



class ResourceFixture extends AbstractFixture implements FixtureInterface,OrderedFixtureInterface,ContainerAwareInterface
{

    use ContainerAwareTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $aResuorcesFixture = $this->container->get('resources-fixture');

        foreach ($aResuorcesFixture as $type => $resuorces){
            foreach ($resuorces as $key => $resource){
                $oResource = new Resources();
                $oResource->setName($resource);
                $oResource->setType($type);
                $manager->persist($oResource);
                $this->addReference("resource-$key", $oResource);
                $manager->flush();
            }

        }

    }

    public function getOrder(){
        return 3;
    }


}