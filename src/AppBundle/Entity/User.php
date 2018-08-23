<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EffectiveSolutions\UserBundle\Model\User as BaseUser;
use AppBundle\Entity\Role;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\User")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * One User has a Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop")
     * @ORM\JoinColumn(name="shop", referencedColumnName="id",nullable=true)
     */
    private $shop;


    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role", referencedColumnName="id")
     * })
     */
    private $role;


    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     * @return User
     */
    public function setRole(\AppBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return array
     */
    public function getRoles(){
        return array($this->getRole()->getMetacode());
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * Set shop
     *
     * @param \AppBundle\Entity\Shop $shop
     *
     * @return User
     */
    public function setShop(\AppBundle\Entity\Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \AppBundle\Entity\Shop
     */
    public function getShop()
    {
        return $this->shop;
    }
}
