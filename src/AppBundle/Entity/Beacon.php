<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Beacon
 *
 * @ORM\Table(name="beacon")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BeaconRepository")
 */
class Beacon
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
     * @ORM\Column(name="uuid", type="string", length=50)
     */
    private $uuid;

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
     * One Beacon has a Shop
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
     * Set uuid
     *
     * @param string $uuid
     *
     * @return Beacon
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set xCoord
     *
     * @param float $xCoord
     *
     * @return Beacon
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
     * @return Beacon
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
     * @return Beacon
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
