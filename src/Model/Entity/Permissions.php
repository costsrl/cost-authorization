<?php

namespace CostAuthorization\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permissions
 *
 * @ORM\Table(name="permissions", indexes={@ORM\Index(name="IDX_87209A8789329D25", columns={"RESOURCE_ID"}), @ORM\Index(name="IDX_87209A87D60322AC", columns={"ROLE_ID"})})
 * @ORM\Entity
 */
class Permissions
{
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="privilege", type="string", length=255, nullable=false)
     */
    private $privilege;

    /**
     * @var boolean
     *
     * @ORM\Column(name="permission_allow", type="integer", nullable=false)
     */
    private $permissionAllow;

    /**
     * @var \CostAuthorization\Model\Entity\Resources
     *
     * @ORM\ManyToOne(targetEntity="CostAuthorization\Model\Entity\Resources")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * })
     */
    private $resource;

    /**
     * @var \CostAuthorization\Model\Entity\Roles
     *
     * @ORM\ManyToOne(targetEntity="CostAuthorization\Model\Entity\Roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;



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
     * @return Permissions
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
     * Set privilege
     *
     * @param string $privilege
     * @return Permissions
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;

        return $this;
    }

    /**
     * Get privilege
     *
     * @return string 
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * Set permissionAllow
     *
     * @param boolean $permissionAllow
     * @return Permissions
     */
    public function setPermissionAllow($permissionAllow)
    {
        $this->permissionAllow = $permissionAllow;

        return $this;
    }

    /**
     * Get permissionAllow
     *
     * @return boolean 
     */
    public function getPermissionAllow()
    {
        return $this->permissionAllow;
    }

    /**
     * Set resource
     *
     * @param \CostAuthorization\Model\Entity\Resources $resource
     * @return Permissions
     */
    public function setResource(\CostAuthorization\Model\Entity\Resources $resource = null)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Get resource
     *
     * @return \CostAuthorization\Model\Entity\Resources 
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set role
     *
     * @param \CostAuthorization\Model\Entity\Roles $role
     * @return Permissions
     */
    public function setRole(\CostAuthorization\Model\Entity\Roles $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \CostAuthorization\Model\Entity\Roles 
     */
    public function getRole()
    {
        return $this->role;
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="assert_class", type="string", length=255, nullable=true)
     */
    private $assertClass;

    public function getAssertClass()
    {
        return $this->assertClass;
    }

    public function setAssertClass($assertClass)
    {
        $this->assertClass = $assertClass;
        return $this;
    }
 
}
