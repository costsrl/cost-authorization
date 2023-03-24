<?php

namespace CostAuthorization\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CostAuthorization\Model\Entity\Repository\RolesRepository;

/**
 * Roles
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="CostAuthorization\Model\Entity\Repository\RolesRepository")
 */
class Roles
{
    
    const defaultRole = 'Member';
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=15, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="CostAuthorization\Model\Entity\Roles", mappedBy="parent")
     * */
    private $children;
    

    /**
     * @ORM\ManyToOne(targetEntity="CostAuthorization\Model\Entity\Roles", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;
    
    
    /**
     * @return the $children
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Roles
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parent
     *
     * @param \CostAuthorization\Model\Entity\Roles $parent
     * @return Roles
     */
    public function setParent(\CostAuthorization\Model\Entity\Roles $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \CostAuthorization\Model\Entity\Roles 
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    
    /**
     *
     * @param Roles $children
     */
    public function addChildren(Roles $children){
        if ($this->children->contains($children)) {
            return;
        }
        $this->children->add($children);
        $children->setParent($this);
    }
    
    /**
     *
     * @param Roles $children
     */
    public function removeChildren(Roles $children)
    {
        if (!$this->children->contains(Roles)) {
            return;
        }
        $this->children->removeElement($children);
        $children->setChildren(null);
    }
}
