<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EffectiveSolutions\UserBundle\Model\Role as BaseRole;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Role")
 */
class Role extends BaseRole
{

}
