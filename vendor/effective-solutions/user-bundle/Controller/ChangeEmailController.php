<?php
/**
 * Created by PhpStorm.
 * User: Prabhashi
 * Date: 12/18/2017
 * Time: 3:22 PM
 */

namespace EffectiveSolutions\UserBundle\Controller;


use AppBundle\Entity\User;
use EffectiveSolutions\UserBundle\Form\UserEmailType;
use EffectiveSolutions\UserBundle\Form\UserNewEmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class ChangeEmailController extends BaseController
{

    //send email update confirm to old email address
    //email gives a link to enter new email address
    /**
     * @Route("/update-email-request",name="update_email")
     */
    public function startEmailChangeAction(Request $request)
    {
        $user = $this->getUser();

        if($user == null){
            return $this->redirectToRoute('login');
        }

        $this->get('esub.email_confirm_validator')->newToken($user, true);
        $email = $user->getEmail();
        $this->get('esub.email')->emailUpdateConfirm($user, $email);


        return $this->render('@EffectiveSolutionsUser/ChangeEmail/email_sent.html.twig');
    }

    /**
     * @Route("/update-email/{key}", name="confirm_new_email")
     */
    public function newEmailAction(Request $request, $key)
    {

        $userAvailable = $this->get('esub.email_confirm_validator')->validate($key);

        if ($userAvailable != false) {
            $user = $userAvailable['user'];
            $isKeyValid = $userAvailable['isKeyValid'];


            if ($isKeyValid) {

                //takes new email and sends a confirmation email to new email

                $this->get('esub.email_confirm_validator')->removeToken($user, false);
                $form = $this->createForm(UserNewEmailType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $newEmail = $form->get('temporaryEmail')->getData();

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $this->get('esub.email_confirm_validator')->newToken($user, true);

                    $this->get('esub.email')->newEmailConfirm($user, $newEmail);
//                $this->get('esub.email')->newEmailConfirm($user, 'mkdpriyankara@gmail.com');

                    return $this->render('@EffectiveSolutionsUser/ChangeEmail/check_new_email.html.twig');

                }

                return $this->render('@EffectiveSolutionsUser/ChangeEmail/new_email_form.html.twig', array(
                    'form' => $form->createView()
                ));
            } else {
                return $this->render('@EffectiveSolutionsUser/ChangeEmail/resend_email_in_change_email.html.twig', array(
                    'user' => $user
                ));
            }


        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route("update-email-success/{key}", name="update_email_success")
     */
    public function newEmailAddAction(Request $request, $key)
    {
        $userAvailable = $this->get('esub.email_confirm_validator')->validate($key);

        if ($userAvailable != false) {
            $user = $userAvailable['user'];
            $isKeyValid = $userAvailable['isKeyValid'];
            $newEmail = $user->getTemporaryEmail();

            if ($isKeyValid) {

                //this is the link sent to new email, when clicked email is updated and successful email is sent to updated email

                $this->get('esub.email_confirm_validator')->removeToken($user, false);
                $userName = $user->getUsername();
                $email = $user->getEmail();

                if ($userName == $email) {
                    $user->setUsername($newEmail);
                }

                $user->setTemporaryEmail(null);
                $user->setEmail($newEmail);

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('esub.email')->emailUpdateSuccess($user, $newEmail);
//            $this->get('esub.email')->emailUpdateSuccess($user, 'mkdpriyankara@gmail.com');

                //update success, login again
//                return $this->render('@EffectiveSolutionsUser/ChangeEmail/change_new_email_success.html.twig');
                return $this->redirectToRoute('logout');
            } else {
                if ($newEmail == null) {
                    //user has already updated to new email
//                    return $this->render('@EffectiveSolutionsUser/ChangeEmail/change_new_email_success.html.twig');
                    return $this->redirectToRoute('logout');

                } else {
                    return $this->render('@EffectiveSolutionsUser/ChangeEmail/resend_email_to_new_email.html.twig', array(
                        'user' => $user
                    ));
                }

            }

        } else {
            return $this->redirectToRoute('login');
        }

    }


    /**
     * @Route("/resend-confirmation-email/{emailConfirmKey}", name="resend_email_in_change_email")
     */
    public function resendEmailAction(Request $request, $emailConfirmKey)
    {
//        $user = $this->getDoctrine()->getRepository(\EffectiveSolutions\UserBundle\Model\User::class)->findOneBy(array('emailConfirmKey' => $emailConfirmKey));
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('emailConfirmKey' => $emailConfirmKey));

        if ($user == null) {
            return $this->redirectToRoute('login');
        } else {
            $email = $user->getEmail();

            $this->get('esub.email_confirm_validator')->newToken($user, true);

            $this->get('esub.email')->emailUpdateConfirm($user, $email);


            return $this->render('@EffectiveSolutionsUser/ChangeEmail/email_sent.html.twig');
        }


    }

    /**
     * @Route("/resend-confirmation-email-to-new-email/{emailConfirmKey}", name="resend_email_to_new_email")
     */
    public function resendEmailToNewEmailAction(Request $request, $emailConfirmKey)
    {
//        $user = $this->getDoctrine()->getRepository(\EffectiveSolutions\UserBundle\Model\User::class)->findOneBy(array('emailConfirmKey' => $emailConfirmKey));
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('emailConfirmKey' => $emailConfirmKey));

        if ($user == null) {
            return $this->redirectToRoute('login');
        } else {
            $newEmail = $user->getTemporaryEmail();

            $this->get('esub.email_confirm_validator')->newToken($user, true);

            $this->get('esub.email')->newEmailConfirm($user, $newEmail);

            return $this->render('@EffectiveSolutionsUser/ChangeEmail/email_sent.html.twig');
        }


    }
}