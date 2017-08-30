<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/28/2017
 * Time: 5:54 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="gallery")
 */
class Gallery
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $logo;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image3;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image4;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image5;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image1;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image2;
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Company",inversedBy="gallery")
     */
    private $myCompany;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMyCompany()
    {
        return $this->myCompany;
    }

    /**
     * @param mixed $myCompany
     */
    public function setMyCompany($myCompany)
    {
        $this->myCompany = $myCompany;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * @param mixed $image1
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;
    }

    /**
     * @return mixed
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param mixed $image2
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;
    }

    /**
     * @return mixed
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * @param mixed $image3
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;
    }

    /**
     * @return mixed
     */
    public function getImage4()
    {
        return $this->image4;
    }

    /**
     * @param mixed $image4
     */
    public function setImage4($image4)
    {
        $this->image4 = $image4;
    }

    /**
     * @return mixed
     */
    public function getImage5()
    {
        return $this->image5;
    }

    /**
     * @param mixed $image5
     */
    public function setImage5($image5)
    {
        $this->image5 = $image5;
    }

}