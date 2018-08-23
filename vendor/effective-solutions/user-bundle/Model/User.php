<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 2/2/17
 * Time: 10:29 PM
 */

namespace EffectiveSolutions\UserBundle\Model;


use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use EffectiveSolutions\UserBundle\Validator\Constraints as UserAssert;



/**
 * Class User
 * @package EffectiveSolutions\UserBundle\Model
 * @UserAssert\Email
 * 
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $username;

    /**
     * @Assert\Length(
     *     min=6,
     *     max=4096,
     *     minMessage = "Your password must be at least {{ limit }} characters long",
     *     maxMessage = "Your password cannot be longer than {{ limit }} characters"
     *     )
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     * @Assert\Email(
     *     message="The email is not a valid email.",
     *     checkMX=true
     * )
     */
    protected $email;

    //TODO new
    /**
     * @ORM\Column(type="string", length=128, unique=true, nullable=true)
     */
    protected $emailConfirmKey;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isEmailConfirmKeyValid;


    /**
     * @ORM\Column(type="string", unique=true, nullable=true, length=255)
     * @Assert\Email(
     *     message="The email is not a valid email.",
     *     checkMX=true
     * )
     */
    protected $temporaryEmail;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(name="updated_at",type="datetime")
     */
    protected $updatedAt;


    public function __construct()
    {
        //TODO initially this should be false, after confirmation change this to true
        $this->isActive = false;
//        $this->isEmailConfirmKeyValid = true;

        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }



    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }



    /**
     * @return mixed
     */
    public function getEmailConfirmKey()
    {
        return $this->emailConfirmKey;
    }

    /**
     * @param mixed $emailConfirmKey
     */
    public function setEmailConfirmKey($emailConfirmKey)
    {
        $this->emailConfirmKey = $emailConfirmKey;
    }

    /**
     * @return mixed
     */
    public function getTemporaryEmail()
    {
        return $this->temporaryEmail;
    }

    /**
     * @param mixed $temporaryEmail
     */
    public function setTemporaryEmail($temporaryEmail)
    {
        $this->temporaryEmail = $temporaryEmail;
    }

    /**
     * @return mixed
     */
    public function getIsEmailConfirmKeyValid()
    {
        return $this->isEmailConfirmKeyValid;
    }

    /**
     * @param mixed $isEmailConfirmKeyValid
     */
    public function setIsEmailConfirmKeyValid($isEmailConfirmKeyValid)
    {
        $this->isEmailConfirmKeyValid = $isEmailConfirmKeyValid;
    }



    public function getRoles()
    {
        return array();
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

}