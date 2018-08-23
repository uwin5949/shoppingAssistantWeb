<?php
/**
 * Created by PhpStorm.
 * User: dar
 * Date: 12/13/17
 * Time: 10:10 AM
 */

namespace EffectiveSolutions\UserBundle\Controller;


use AppBundle\Entity\User;
use EffectiveSolutions\UserBundle\Form\UserEmailType;
use EffectiveSolutions\UserBundle\Form\UserPasswordType;
use EffectiveSolutions\UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ForgotPasswordController
 * @package EffectiveSolutions\UserBundle\Controller
 * @Route("/forgot-password")
 */
class ForgotPasswordController extends BaseController
{

    /**
     * @Route("/email", name="forgot_password_email") //TODO change name to forgot_password_email
     */
    public function getEmailAddressAction(Request $request)
    {

        $form = $this->createForm(UserEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $email = $data['email'];

            $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['email' => $email]);

            if ($user != null) {
                $isActive = $user->getIsActive();
                if ($isActive) {

                    $this->get('esub.email_confirm_validator')->newToken($user, true);

                    $this->get('esub.email')->passwordUpdateConfirm($user, $email);
//                    $this->get('esub.email')->passwordUpdateConfirm($user, 'mkdpriyankara@gmail.com');

//                    return $this->redirectToRoute('login');
                    return $this->render('@EffectiveSolutionsUser/ForgotPassword/email_sent.html.twig');
                } else {
                    return $this->render('@EffectiveSolutionsUser/ForgotPassword/inactive_user.html.twig', array(
                        'user' => $user
                    ));

                }
            } else {
                return $this->render('@EffectiveSolutionsUser/ForgotPassword/wrong_email.html.twig', array(
                    'form' => $form->createView()
                ));
            }

        }

        return $this->render('@EffectiveSolutionsUser/ForgotPassword/email_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/reset/{key}",name="forgot_password_reset") //TODO route => /reset name => forgot_password_reset
     */
    public function passwordResetAction(Request $request, $key)
    {

        $userAvailable = $this->get('esub.email_confirm_validator')->validate($key);

        if ($userAvailable != false) {
            $user = $userAvailable['user'];
            $isKeyValid = $userAvailable['isKeyValid'];
            $email = $user->getEmail();

            if ($isKeyValid) {
                $this->get('esub.email_confirm_validator')->removeToken($user, false);
                $form = $this->createForm(UserPasswordType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $data = $form->getData();
                    $password = $data['password'];

                    $password = $this->get('security.password_encoder')
                        ->encodePassword($user, $password);
                    $user->setPassword($password);

                    $em = $this->getDoctrine()->getManager();
                    $em->flush();

                    $this->get('esub.email')->passwordUpdateSuccess($user, $email);

                    //TODO add flash message if success
//                    return $this->redirectToRoute('login');
                    return $this->render('@EffectiveSolutionsUser/ForgotPassword/success.html.twig');

                }

                //TODO add flash message with error messages
                return $this->render('@EffectiveSolutionsUser/ForgotPassword/reset_form.html.twig', array(
                    'form' => $form->createView()
                ));
            }

            else{
                return $this->render('@EffectiveSolutionsUser/ForgotPassword/resend_email_in_forgot_password.html.twig', array(
                    'user' => $user
                ));
            }

        } else {
            return $this->redirectToRoute('login');

        }
    }


    /**
     * @Route("/resend-confirmation-email/{emailConfirmKey}", name="resend_email_in_forgot_password")
     */
    public function resendEmailAction(Request $request, $emailConfirmKey)
    {
//        $user = $this->getDoctrine()->getRepository(\EffectiveSolutions\UserBundle\Model\User::class)->findOneBy(array('emailConfirmKey' => $emailConfirmKey));
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('emailConfirmKey' => $emailConfirmKey));

        if($user == null){
            return $this->redirectToRoute('login');
        }
        else{
            $email = $user->getEmail();

            $this->get('esub.email_confirm_validator')->newToken($user, true);

            $this->get('esub.email')->passwordUpdateConfirm($user, $email);

            return $this->render('@EffectiveSolutionsUser/ForgotPassword/email_sent.html.twig');
        }


    }
}