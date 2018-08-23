<?php
/**
 * Created by PhpStorm.
 * User: uwin5
 * Date: 08/23/18
 * Time: 9:30 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Item;
use AppBundle\Entity\Offer;
use AppBundle\Entity\Rack;
use AppBundle\Entity\Shop;
use AppBundle\Form\ItemType;
use AppBundle\Form\RackType;
use AppBundle\Form\ShopType;
use AppBundle\Form\OfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{

    /**
     * @Route("", name="dashboard_home")
     */
    public function indexAction(Request $request)
    {
        $this->addFlash('info', 'Welcome Back!');
        return $this->render('dashboard/home.html.twig', array(
            'heading' => 'Dashboard',
        ));
    }


    /**
     * @Route("/shop/configure/{id}", name="shop_configure")
     */
    public function shopConfigureAction(Request $request, $id = null)
    {

        $isEdit=null;
        if($id==null){
            $shop=new Shop();
            $isEdit=false;
        }
        else{
            $shop = $this->getDoctrine()->getManager()->getRepository('AppBundle:Shop')->find($id);
            $isEdit=true;
        }
        if($shop==null){
            return null;
        }
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);
        $errors = $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            $em->persist($shop);
            $em->flush();
            if($isEdit){
                $this->addFlash(
                    'success',
                    'Shop Has been Updated Successfully!'
                );
            }
            else{
                $this->addFlash(
                    'success',
                    'Shop Has been Added Successfully!'
                );
            }
            return $this->redirectToRoute('shop_configure',array('id'=>$shop->getId()));
        }

        return $this->render('dashboard/shopConfigure.html.twig', array(
            'heading' => 'Shop Configure',
            'errors' => $errors,
            'isEdit'=>$isEdit,
            'form' => $form->createView()
        ));
    }




    /**
     * @Route("/rack/configure/{id}", name="rack_configure")
     */
    public function racksConfigureAction(Request $request, $id = null)
    {

        $isEdit=null;
        if($id==null){
            $rack=new Rack();
            $isEdit=false;
        }
        else{
            $rack = $this->getDoctrine()->getManager()->getRepository('AppBundle:Rack')->find($id);
            $isEdit=true;
        }
        if($rack==null){
            return null;
        }
        $form = $this->createForm(RackType::class, $rack);
        $form->handleRequest($request);
        $errors = $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$isEdit){
                $rack->setShop($this->getUser()->getShop());
            }


            $em->persist($rack);
            $em->flush();
            if($isEdit){
                $this->addFlash(
                    'success',
                    'Rack Has been Updated Successfully!'
                );
            }
            else{
                $this->addFlash(
                    'success',
                    'Rack Has been Added Successfully!'
                );
            }
            return $this->redirectToRoute('rack_configure',array('id'=>$rack->getId()));
        }

        return $this->render('dashboard/racksConfigure.html.twig', array(
            'heading' => 'Rack Configure',
            'errors' => $errors,
            'isEdit'=>$isEdit,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/item/configure/{id}", name="item_configure")
     */
    public function itemConfigureAction(Request $request, $id = null)
    {

        $user = $this->getUser();

        $isEdit=null;
        if($id==null){
            $item=new Item();
            $isEdit=false;
        }
        else{
            $item = $this->getDoctrine()->getManager()->getRepository('AppBundle:Item')->find($id);
            $isEdit=true;
        }
        if($item==null){
            return null;
        }

        $form = $this->createForm(ItemType::class, $item, array('shopId' => $user->getShop()));
        $form->handleRequest($request);
        $errors = $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            $em->persist($item);
            $em->flush();
            if($isEdit){
                $this->addFlash(
                    'success',
                    'Item Has been Updated Successfully!'
                );
            }
            else{
                $this->addFlash(
                    'success',
                    'Item Has been Added Successfully!'
                );
            }
            return $this->redirectToRoute('item_configure',array('id'=>$item->getId()));
        }

        return $this->render('dashboard/itemConfigure.html.twig', array(
            'heading' => 'Item Configure',
            'errors' => $errors,
            'isEdit'=>$isEdit,
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/offer/configure/{id}", name="offer_configure")
     */
    public function offerConfigureAction(Request $request, $id = null)
    {

        $isEdit=null;
        if($id==null){
            $offer=new Offer();
            $isEdit=false;
        }
        else{
            $offer = $this->getDoctrine()->getManager()->getRepository('AppBundle:Offer')->find($id);
            $isEdit=true;
        }
        if($offer==null){
            return null;
        }
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        $errors = $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(!$isEdit){
                $offer->setShop($this->getUser()->getShop());
            }
            $em->persist($offer);
            $em->flush();
            if($isEdit){
                $this->addFlash(
                    'success',
                    'Offer Has been Updated Successfully!'
                );
            }
            else{
                $this->addFlash(
                    'success',
                    'Offer Has been Added Successfully!'
                );
            }
            return $this->redirectToRoute('offer_configure',array('id'=>$offer->getId()));
        }

        return $this->render('dashboard/offerConfigure.html.twig', array(
            'heading' => 'Offer Configure',
            'errors' => $errors,
            'isEdit'=>$isEdit,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/item/list", name="dashboard_view_item_list")
     */
    public function viewItemListAction(Request $request)
    {
        $user = $this->getUser();
        $racks = $this->getDoctrine()->getManager()->getRepository('AppBundle:Rack')->findBy(array("shop"=>$user->getShop()));


        return $this->render('dashboard/itemList.html.twig', array(
            'racks' => $racks,
            'heading' => 'Item List',
        ));
    }

    /**
     * @Route("/dashboard/item/list/ajax/load/data", name="dashboard_item_list_ajax_load_data")
     */
    public function busLoadAjaxAction(Request $request)
    {
        $data=json_decode($request->get('data'));
        $rack=$this->getDoctrine()->getManager()->getRepository('AppBundle:Rack')->find($data->rack);
        $items=$this->getDoctrine()->getManager()->getRepository('AppBundle:Item')->findBy(array('rack'=>$rack));
        $stdItems=array();
        for($i=0;$i<count($items);$i++){
            $stdItem=new \stdClass();
            $stdItem->id=$items[$i]->getId();
            if($items[$i]->getOffer() !=null){
                $stdItem->offer=$items[$i]->getOffer()->getDescription();
            }
            else{
                $stdItem->offer=null;
            }

            $stdItem->itemCode=$items[$i]->getCode();
            $stdItem->itemName=$items[$i]->getName();
            $stdItem->price=$items[$i]->getPrice();
            array_push($stdItems,$stdItem);


        }

        return new Response(json_encode($stdItems),200);
    }

    /**
     * @Route("/rack/list", name="dashboard_view_rack_list")
     */
    public function viewRackListAction(Request $request)
    {
        $racks = $this->getDoctrine()->getManager()->getRepository('AppBundle:Rack')->findBy(array("shop"=>$this->getUser()->getShop()));

        return $this->render('dashboard/rackList.html.twig', array(
            'racks' => $racks,
            'heading' => 'Rack List',
        ));
    }

    /**
     * @Route("/offer/list", name="dashboard_view_offer_list")
     */
    public function viewOfferListAction(Request $request)
    {
        $offers = $this->getDoctrine()->getManager()->getRepository('AppBundle:Offer')->findBy(array("shop"=>$this->getUser()->getShop()));

        return $this->render('dashboard/offerList.html.twig', array(
            'offers' => $offers,
            'heading' => 'Offer List',
        ));
    }


}