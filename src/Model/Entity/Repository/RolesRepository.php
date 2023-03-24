<?php
namespace CostAuthorization\Model\Entity\Repository;

use Doctrine\ORM\EntityRepository;


class RolesRepository extends EntityRepository
{
    public function findCustomAll($property,$direction='ASC')
    {
        return $this->findBy(array(), array($property => $direction));
    }
}

