# Effective Solutions - User Bundle

## Installation

Use following command in command line to install Effective Solutions User Bundle

`php composer.phar require effective-solutions/user-bundle`

After the installation add following in your `AppKernel.php` file

`new EffectiveSolutions\UserBundle\EffectiveSolutionsUserBundle(),`

Add following in `app/config/routing.yml` file

```
effective_solutions_user:
    resource: "@EffectiveSolutionsUserBundle/Controller/"
    type:     annotation
    prefix:   /
    
logout:
    path: /logout
```

Add following in `app/config/services.yml` file.

```
parameters:
    registration_default_role: < role metacode >
```

## Usage

- Create `User.php` file in your Entity folder and add following code.

```
<?php

namespace AppBundle\Entity;

use EffectiveSolutions\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="app_users")
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
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

    public function getRoles()
    {
        return array($this->getRole()->getMetacode());
    }
}

```

- Create `Role.php` file in your Entity folder and add following code.

```
<?php

namespace AppBundle\Entity;

use EffectiveSolutions\UserBundle\Model\Role as BaseRole;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 * @ORM\Table(name="role")
 */
class Role extends BaseRole
{
}
```
- Create `UserRepository.php` file in your Repository Folder and add following code.

```
<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * UserRepository
 *
 * Add your own custom repository methods below.
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
```

- Create `RoleRepository.php` file in your Repository folder and add following code.

```
<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RoleRepository
 *
 * Add your own custom repository methods below.
 */
class RoleRepository extends EntityRepository
{
}
```

- Run following command to create your user and role tables in your database

`php app/console doctrine:schema:update --force`

- Run following command to install assets

`php app/console asset:install`

- Add following code in your `app/config/security.yml` file

```
security:
    encoders:
        AppBundle\Entity\User:
            algorithm: bcrypt
            cost: 12

#    role_hierarchy:
#        ROLE_ADMIN: ROLE_USER

    providers:
        our_db_provider:
            entity:
                class: AppBundle:User

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

        main:
            pattern:    ^/
            anonymous: ~
            http_basic: ~
            provider: our_db_provider
            form_login:
                login_path: login
                check_path: login
                csrf_token_generator: security.csrf.token_manager
#                default_target_path: redirect
            logout:
                path:   /logout
                target: /
            remember_me:
                secret:   '%secret%'
                lifetime: 604800 # 1 week in seconds
                path:     /

    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/, roles: ROLE_ADMIN }
```

- Then go to `http://localhost/YourAppName/web/app_dev.php/login`

Thank you for using Effective Solutions User Bundle. Powered By [EffectiveSolutions.lk](http://effectivesolutions.lk)