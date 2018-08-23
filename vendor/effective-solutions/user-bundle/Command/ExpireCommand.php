<?php
/**
 * Created by PhpStorm.
 * User: Prabhashi
 * Date: 12/15/2017
 * Time: 11:26 AM
 */

namespace EffectiveSolutions\UserBundle\Command;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\AST\Functions\DateDiffFunction;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExpireCommand
 * handle email confirmation key expiration ( for registration confirmation ) and temp email expiration ( for email change )
 */
class ExpireCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("esub:email_confirm_expire")
            ->setDescription("This is used to mark expired email confirm users"); //TODO change the description
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //TODO find all users
        //if there is a value for email key
        //if it is not already expired
        //get time gap between now and user last edit time
        //if it is larger than one hour mark it as a expired key ( using expired boolean parameter )

        $startDateTime = new \DateTime('now');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $userRepo = $em->getRepository('AppBundle:User');
//        $userRepo = $em->getRepository(User::class);

        $qb = $userRepo->createQueryBuilder('u')
            ->select('u')
            ->where("u.emailConfirmKey != 'null'")
            ->andWhere("u.isEmailConfirmKeyValid = '1'")
            ->getQuery();

        $users = $qb->getResult();

        $now = new \DateTime('now');
//        $output->writeln($now);

        foreach ($users as $user) {

            $i = $user->getUpdatedAt();
            $interval = $now->diff($i);

            $h = $interval->h;
            $d = $interval->d;
            $y = $interval->y;

            if ($this->expireToken($h, $d, $y)) {
                $user->setIsEmailConfirmKeyValid(false);
                $em->flush();
            }
        }

        //TODO calculate total time taken to the operation and print it using $output

        $endDateTime = new \DateTime('now');

        $totalDateTime = $startDateTime->diff($endDateTime);
//        $totalDateTime = $endDateTime->diff($startDateTime);


        $totalTime = $totalDateTime->h.' hours : '.$totalDateTime->i.' minutes : '.$totalDateTime->s.' seconds : '.$totalDateTime->f.' microseconds';

        $output->writeln('Total time taken for the operation: '.$totalTime);


    }

    //to check whether the token should be expired or not
    protected function expireToken($h, $d, $y)
    {

        if ($h > 0) {
            return true;
        } elseif ($d > 0) {
            return true;
        } elseif ($y > 0) {
            return true;
        } else {
            return false;
        }
    }

}