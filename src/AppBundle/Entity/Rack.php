<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rack
 *
 * @ORM\Table(name="rack")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RackRepository")
 */
class Rack
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="rackNo", type="string")
     */
    private $rackNo;

    /**
     * @var float
     *
     * @ORM\Column(name="x_coord", type="float")
     */
    private $x_coord;

    /**
     * @var float
     *
     * @ORM\Column(name="y_coord", type="float")
     */
    private $y_coord;

    /**
     * One Rack has a Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop")
     * @ORM\JoinColumn(name="shop", referencedColumnName="id")
     */
    private $shop;




    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rackNo
     *
     * @param integer $rackNo
     *
     * @return Rack
     */
    public function setRackNo($rackNo)
    {
        $this->rackNo = $rackNo;

        return $this;
    }

    /**
     * Get rackNo
     *
     * @return integer
     */
    public function getRackNo()
    {
        return $this->rackNo;
    }

    /**
     * Set xCoord
     *
     * @param float $xCoord
     *
     * @return Rack
     */
    public function setXCoord($xCoord)
    {
        $this->x_coord = $xCoord;

        return $this;
    }

    /**
     * Get xCoord
     *
     * @return float
     */
    public function getXCoord()
    {
        return $this->x_coord;
    }

    /**
     * Set yCoord
     *
     * @param float $yCoord
     *
     * @return Rack
     */
    public function setYCoord($yCoord)
    {
        $this->y_coord = $yCoord;

        return $this;
    }

    /**
     * Get yCoord
     *
     * @return float
     */
    public function getYCoord()
    {
        return $this->y_coord;
    }

    /**
     * Set shop
     *
     * @param \AppBundle\Entity\Shop $shop
     *
     * @return Rack
     */
    public function setShop(\AppBundle\Entity\Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \AppBundle\Entity\Shop
     */
    public function getShop()
    {
        return $this->shop;
    }
}
