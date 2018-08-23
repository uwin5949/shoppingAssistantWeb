<?php
/**
 * Created by PhpStorm.
 * User: Prabhashi
 * Date: 12/15/2017
 * Time: 9:43 AM
 */

namespace EffectiveSolutions\UserBundle\Utils;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class EmailConfirmValidator
{

    private $templating;
    private $mailer;
    private $mu;
    private $entityManager;

    /**
     * Email constructor.
     */
    public function __construct($templating, EntityManager $entityManager, $mailer, $mailerUser)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->mu = $mailerUser;
        $this->entityManager = $entityManager;
    }


    public function newToken(User $user, $flush)
    {

        //TODO generate new token and update
//        find by, do-while to avoid same key generated ? ********
//        $random = random_bytes(5);
//        $key = base64_encode($random); //but contains +,= and / ,thus cannot be used

//        $key = urlencode($key);


        $key = bin2hex(openssl_random_pseudo_bytes(64));

        $user->setEmailConfirmKey($key);
        $user->setIsEmailConfirmKeyValid(true);

        if ($flush) {
            $this->entityManager->flush();
        }

    }

    public function removeToken(User $user, $flush)
    {
        $user->setIsEmailConfirmKeyValid(false);

        if ($flush) {
            $this->entityManager->flush();
        }

    }

    public function validate($key)
    {

        $user = $this->entityManager->getRepository('AppBundle:User')->findOneBy(['emailConfirmKey' => $key]);


        if ($user != null) {

        $isKeyValid = $user->getIsEmailConfirmKeyValid();
                return array(
                    'user' => $user,
                    'isAvailable' => true,
                    'isKeyValid' => $isKeyValid
                );


        } else {
            return false;
        }

    }

}