<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reviews")
 */
class Reviews
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $reviewText;
    /**
     * @ORM\ManyToOne(targetEntity="Product")
     */
    private $product;

    /**
     * @return mixed
     */
    public function getReviewText()
    {
        return $this->reviewText;
    }

    /**
     * @param mixed $reviewText
     */
    public function setReviewText($reviewText)
    {
        $this->reviewText = $reviewText;
    }

}