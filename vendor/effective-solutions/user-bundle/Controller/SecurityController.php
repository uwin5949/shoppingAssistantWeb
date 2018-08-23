<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 2/3/17
 * Time: 1:04 AM
 */

namespace EffectiveSolutions\UserBundle\Controller;


use AppBundle\Events\UserRegisteredEvent;
use EffectiveSolutions\UserBundle\Form\UserRegisterType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('EffectiveSolutionsUserBundle:Login:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }


    //TODO change name to register

    /**
     * @Route("/register", name="register")
     */
    public function registrationAction(Request $request)
    {

        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $role = $this->getRepository('Role')->findOneByMetacode($this->getParameter('registration_default_role'));
            $user->setRole($role);

            $email = $user->getEmail();
            $user->setUsername($email);

            $this->get('esub.email_confirm_validator')->newToken($user, false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('esub.email')->registrationConfirm($user, $email);

            $dispatcher = $this->get('event_dispatcher');

            $event = new UserRegisteredEvent($user);
            $dispatcher->dispatch(UserRegisteredEvent::NAME,$event);


//            $this->get('esub.email')->registrationConfirm($user, 'mkdpriyankara@gmail.com');

            return $this->render('@EffectiveSolutionsUser/Registration/register_success_pending.html.twig');


        }

        return $this->render('EffectiveSolutionsUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("register/confirm/{key}",name="register_confirm")
     */
    public function registerConfirmAction(Request $request, $key)
    {

        // TODO user email validator service to validate email validation key and change the user status to active

        $userAvailable = $this->get('esub.email_confirm_validator')->validate($key);

        if ($userAvailable != false) {

            $isKeyValid = $userAvailable['isKeyValid'];
            $user = $userAvailable['user'];
            $email = $user->getEmail();

            if ($isKeyValid) {
                $this->get('esub.email_confirm_validator')->removeToken($user, false);
                $user->setIsActive(true);
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->get('esub.email')->registrationSuccess($user, $email);
//            $this->get('esub.email')->registrationSuccess($user, 'mkdpriyankara@gmail.com');

                return $this->redirectToRoute('register_success');
            } else {
                //if token expired

                if ($user->getIsActive()) {
                    return $this->redirectToRoute('login');
                } else {
//                    $this->get('esub.email_confirm_validator')->newToken($user, true);

                    return $this->render('@EffectiveSolutionsUser/Registration/resend_email_to_register.html.twig', array(
                        'user' => $user
                    ));
                }

            }

        } else {
            //no user for the given key,therefore redirect to register
            return $this->redirectToRoute('register');
        }

    }

    /**
     * @Route("register/confirm/result/success", name="register_success")
     */
    public function registerSuccessAction()
    {
        return $this->render('@EffectiveSolutionsUser/Registration/register_success.html.twig');
    }

//    /**
//     * @Route("register/confirm/result/failure", name="register_failure")
//     */
//    public function registerFailureAction(Request $request)
//    {
//        //code to get the user
//        return $this->render('@EffectiveSolutionsUser/Registration/resend_email_to_register.html.twig');
//    }


    /**
     * @Route("register/resend-confirmation-email/{emailConfirmKey}", name="resend_email")
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

            $this->get('esub.email')->registrationConfirm($user, $email);

            return $this->render('@EffectiveSolutionsUser/Registration/register_success_pending.html.twig');

        }

    }

    /**
     * @Route("/login/inactive_user/resend_email/{username}", name="resend_email_inactive_user")
     */
    public function resendEmailInactiveUserAction(Request $request, $username){
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('username'=>$username));

        if ($user == null){
            return $this->redirectToRoute('login');
        }

        $emailConfirmKey = $user->getEmailConfirmKey();

        return $this->redirectToRoute('resend_email', array(
            'emailConfirmKey' => $emailConfirmKey
        ));
    }



}