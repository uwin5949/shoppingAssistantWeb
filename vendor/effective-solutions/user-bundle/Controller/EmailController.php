<?php
/**
 * Created by PhpStorm.
 * User: Prabhashi
 * Date: 12/11/2017
 * Time: 4:38 PM
 */

namespace EffectiveSolutions\UserBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends BaseController
{

//Dummy code TODO remove
    /**
     * @Route("/send", name="send_mail")
     */
    public function regSuccessAction(Request $request){


        $message = \Swift_Message::newInstance()
            ->setFrom('rslmis.notification@gmail.com')
            ->setTo('prabhashi.mm@gmail.com')
            ->setBody(
                $this->renderView(
                    '@EffectiveSolutionsUser/Registration/register_confirm_email.html.twig',
                    array('name'=>'dar')
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);

        return $this->render('@EffectiveSolutionsUser/Registration/register_success_pending.html.twig');
    }



}