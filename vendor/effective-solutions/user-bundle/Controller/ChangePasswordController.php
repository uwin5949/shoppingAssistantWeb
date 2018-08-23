<?php
/**
 * Created by PhpStorm.
 * User: Prabhashi
 * Date: 12/18/2017
 * Time: 11:30 AM
 */

namespace EffectiveSolutions\UserBundle\Controller;


use EffectiveSolutions\UserBundle\Form\ChangePasswordType;
use EffectiveSolutions\UserBundle\Form\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordController extends BaseController
{
    /**
     * @Route("change-password", name="change_password")
     */
    public function changePasswordAction(Request $request){

        $changePassword = new ChangePassword();

        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getUser();
//            $user = $this->get('security.token_storage')->getToken()->getUser();

//            if user is logged out
            if($user == null){
                return $this->redirectToRoute('login');
            }

            $newPassword = $changePassword->getNewPassword();

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $newPassword);
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $email = $user->getEmail();

            $this->get('esub.email')->passwordUpdateSuccess($user, $email);

//            return $this->render('@EffectiveSolutionsUser/ChangePassword/change_password_success.html.twig');
            return $this->redirectToRoute('logout');
        }

        return $this->render('@EffectiveSolutionsUser/ChangePassword/change_password.html.twig', array(
           'form' => $form->createView()
        ));

    }



}