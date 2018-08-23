<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 2/3/17
 * Time: 11:32 AM
 */

namespace EffectiveSolutions\UserBundle\Controller;

use AppBundle\Entity\User;
use EffectiveSolutions\UserBundle\Form\UserAddType;
use EffectiveSolutions\UserBundle\Form\UserPasswordType;
use EffectiveSolutions\UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dashboard/user-mngt")
 */
class DashboardController extends BaseController
{
    /**
     * @Route("/list", name="dashboard_user_mngt_list")
     */
    public function userListAction()
    {
        $users = $this->getRepository('User')->findAll();
        return $this->render('EffectiveSolutionsUserBundle:Dashboard:list.html.twig',array(
            'users'=>$users
        ));
    }

    /**
     * @Route("/{id}/view", name="dashboard_user_mngt_view")
     */
    public function userViewAction($id)
    {
        $user = $this->getRepository('User')->find($id);
        return $this->render('EffectiveSolutionsUserBundle:Dashboard:view.html.twig',array(
            'user'=>$user
        ));
    }

    /**
     * @Route("/{id}/edit", name="dashboard_user_mngt_edit")
     */
    public function userEditAction(Request $request,$id)
    {
        $user = $this->getRepository('User')->find($id);
        // form 1
        $form1 = $this->createForm(UserType::class,$user);
        $form1->handleRequest($request);
        if($form1->isSubmitted() && $form1->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        // form2
        $form2 = $this->createForm(UserPasswordType::class,$user);
        $form2->handleRequest($request);

        if($form2->isSubmitted() && $form2->isValid()){
            $em = $this->getDoctrine()->getManager();
            // get password from form and encode
            $password = $form2->getData()->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $password);
            // set password to entity
            $user->setPassword($encoded);
            $em->persist($user);
            $em->flush();
        }
        return $this->render('EffectiveSolutionsUserBundle:Dashboard:edit.html.twig',array(
            'form1'=>$form1->createView(),
            'form2'=>$form2->createView(),
        ));
    }

    /**
     * @Route("/add", name="dashboard_user_mngt_add")
     */
    public function userAddAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserAddType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // get password from data and encode
            $password = $form->getData()->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $password);
            // set password to entity
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('EffectiveSolutionsUserBundle:Dashboard:add.html.twig',array(
            'form'=>$form->createView()
        ));
    }
}