<?php
/**
 * Created by PhpStorm.
 * User: uwin5
 * Date: 08/23/18
 * Time: 12:54 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    /**
     * @Route("/api/rack/list", name="api_rack_list")
     */
    public function findRackList(Request $request)
    {
        if($request->getMethod() == "POST"){
            $shop_id = $request->get('shop_id');
            $shop = $this->getDoctrine()->getManager()->getRepository('AppBundle:Shop')->find($shop_id);
            $racks = $this->getDoctrine()->getManager()->getRepository("AppBundle:Rack")->findBy(array('shop'=>$shop));
            $stdRacks = array();
            foreach($racks as $rack) {
                $stdRack = new \stdClass();
                $stdRack -> rackNo = $rack->getRackNo();
                $stdRack -> rackId = $rack->getId();
                $stdRack -> xCoord = $rack->getXCoord();
                $stdRack -> yCoord = $rack->getYCoord();
                array_push($stdRacks, $stdRack);
            }

            return new Response(json_encode($stdRacks));
        }
        else{
            return null;
        }

    }


    /**
     * @Route("/api/beacon/list", name="api_beacon_list")
     */
    public function findBeaconList(Request $request)
    {
        if($request->getMethod() == "POST"){
            $shop_id = $request->get('shop_id');
            $shop = $this->getDoctrine()->getManager()->getRepository('AppBundle:Shop')->find($shop_id);
            $beacons = $this->getDoctrine()->getManager()->getRepository("AppBundle:Beacon")->findBy(array('shop'=>$shop));
            $stdBeacons = array();
            foreach($beacons as $beacon) {
                $stdBeacon = new \stdClass();
                $stdBeacon -> uuid = $beacon->getUuid();
                $stdBeacon -> id = $beacon->getId();
                $stdBeacon -> xCoord = $beacon->getXCoord();
                $stdBeacon -> yCoord = $beacon->getYCoord();
                array_push($stdBeacons, $stdBeacon);
            }

            return new Response(json_encode($stdBeacons));
        }
        else{
            return null;
        }

    }

    /**
     * @Route("/api/itemdata", name="api_itemData")
     */
    public function itemDataAction(Request $request)
    {
        if($request->getMethod() == "POST"){
            $response = new \stdClass();
            $shop_id = $request->get('shop_id');
            $shop = $this->getDoctrine()->getManager()->getRepository('AppBundle:Shop')->find($shop_id);
            $stdItems = array();
            if($shop != null){
                $items = $this->getDoctrine()->getManager()->getRepository('AppBundle:Item')->findByShop($shop);

                foreach($items as $item){
                    $stdItem = new \stdClass();
                    $stdItem->id = $item->getId();
                    $stdItem->itemCode = $item->getCode();
                    $stdItem->price = $item->getPrice();
                    $stdItem->name = $item->getName();
                    $stdItem->rackId = $item->getRack()->getId();
                    $stdItem->rackNo = $item->getRack()->getRackNo();
                    if($item->getOffer() != null){
                        $stdOffer = new \stdClass();
                        $stdOffer->id = $item->getOffer()->getId();
                        $stdOffer->percentage = $item->getOffer()->getPercentage();
                        $stdOffer->description = $item->getOffer()->getDescription();
                        $stdItem->offer = $stdOffer;
                    }
                    else{
                        $stdItem->offer = null;
                    }

                    array_push($stdItems, $stdItem);

                }
            }

            return new Response(json_encode($stdItems));
        }
        else{
            return null;
        }

    }


    /**
     * @Route("/api/shopsuggetion", name="api_shopsuggetion")
     */
    public function suggestAShopAction(Request $request)
    {
        if($request->getMethod() == "POST"){
            $response = new \stdClass();
            $uuid = $request->get('uuid');

            $beacon = $this->getDoctrine()->getManager()->getRepository('AppBundle:Beacon')->findOneBy(array('uuid'=>$uuid));
            if($beacon != null){
                $stdShop = new \stdClass();
                $stdShop->name = $beacon->getShop()->getName();
                $stdShop->id = $beacon->getShop()->getId();

                $response->shop = $stdShop;
            }
            else{
                $response->shop = null;
            }


            return new Response(json_encode($response));
        }
        else{
            return null;
        }

    }




}