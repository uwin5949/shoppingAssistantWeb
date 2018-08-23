<?php
/**
 * Created by PhpStorm.
 * User: dar
 * Date: 9/30/16
 * Time: 7:02 AM
 */

namespace EffectiveSolutions\UserBundle\Utils;


use AppBundle\Entity\User;

class Email
{

    private $templating;
    private $mailer;
    private $mu;
    /**
     * Email constructor.
     */
    public function __construct($templating,$mailer,$mailerUser)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->mu = $mailerUser;
    }


    public function registrationConfirm(User $user ,$to){

        $message = \Swift_Message::newInstance()
            ->setSubject('New Registration')
            ->setFrom($this->mu)
            ->setTo($to)
//            ->setBcc(array('mkdpriyankara@gmail.com'))
            ->setBody(
                $this->templating->render(
                    'EffectiveSolutionsUserBundle:Registration:register_confirm_email.html.twig',
                    array('user'=>$user)
                ),
                'text/html'
            );

        $this->mailer->send($message);


    }

    public function registrationSuccess(User $user ,$to){

        $message = \Swift_Message::newInstance()
            ->setSubject('Registration Success')
            ->setFrom($this->mu)
            ->setTo($to)
//            ->setBcc(array('mkdpriyankara@gmail.com'))
            ->setBody(
                $this->templating->render(
                    'EffectiveSolutionsUserBundle:Registration:register_success_email.html.twig',
                    array('user'=>$user)
                ),
                'text/html'
            );

        $this->mailer->send($message);


    }

    public function registrationFailure(){

    }

    public function emailUpdateConfirm(User $user ,$to){
        $message = \Swift_Message::newInstance()
            ->setSubject('Email update Confirm')
            ->setFrom($this->mu)
            ->setTo($to)
//            ->setBcc(array('mkdpriyankara@gmail.com'))
            ->setBody(
                $this->templating->render(
                    'EffectiveSolutionsUserBundle:ChangeEmail:change_email_confirm_email.html.twig',
                    array('user'=>$user)
                ),
                'text/html'
            );

        $this->mailer->send($message);

    }

    public function newEmailConfirm(User $user ,$to){
        $message = \Swift_Message::newInstance()
            ->setSubject('Updating email for this address')
            ->setFrom($this->mu)
            ->setTo($to)
//            ->setBcc(array('mkdpriyankara@gmail.com'))
            ->setBody(
                $this->templating->render(
                    'EffectiveSolutionsUserBundle:ChangeEmail:newemail_confirm_email.html.twig',
                    array('user'=>$user)
                ),
                'text/html'
            );

        $this->mailer->send($message);

    }

    public function emailUpdateSuccess(User $user ,$to){
        $message = \Swift_Message::newInstance()
            ->setSubject('Updating email success')
            ->setFrom($this->mu)
            ->setTo($to)
//            ->setBcc(array('mkdpriyankara@gmail.com'))
            ->setBody(
                $this->templating->render(
                    'EffectiveSolutionsUserBundle:ChangeEmail:change_new_email_success.html.twig',
                    array('user'=>$user)
                ),
                'text/html'
            );

        $this->mailer->send($message);

    }
    public function emailUpdateFailure(){

    }

    public function passwordUpdateConfirm(User $user, $to){

        $message = \Swift_Message::newInstance()
            ->setSubject('Reset password')
            ->setFrom($this->mu)
            ->setTo($to)
//            ->setBcc(array('mkdpriyankara@gmail.com'))
            ->setBody(
                $this->templating->render(
                    'EffectiveSolutionsUserBundle:ForgotPassword:reset_password_confirm_email.html.twig',
                    array('user'=>$user)
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
    public function passwordUpdateSuccess(User $user, $to){

        $message = \Swift_Message::newInstance()
            ->setSubject('Reset password Success')
            ->setFrom($this->mu)
            ->setTo($to)
//            ->setBcc(array('mkdpriyankara@gmail.com'))
            ->setBody(
                $this->templating->render(
                    'EffectiveSolutionsUserBundle:ForgotPassword:reset_password_success_email.html.twig',
                    array('user'=>$user)
                ),
                'text/html'
            );

        $this->mailer->send($message);

    }

    public function passwordUpdateFailure(){

    }

}