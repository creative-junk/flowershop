<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Yoann Aparici <y.aparici@lexik.fr>
 * @ORM\Entity
 * @ORM\Table(name="currency")
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @Assert\Length(min=3)
     * @Assert\Length(max=3)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $originalCode;
    /**
     * @Assert\Length(min=3)
     * @Assert\Length(max=3)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $targetCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $rate;

    /**
     * Get ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getOriginalCode()
    {
        return $this->originalCode;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setOriginalCode($code)
    {
        $this->originalCode = $code;
    }

    /**
     * @return string
     */
    public function getTargetCode()
    {
        return $this->targetCode;
    }

    /**
     * @param string $targetCode
     */
    public function setTargetCode($targetCode)
    {
        $this->targetCode = $targetCode;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set rate
     *
     * @param string $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * Convert currency rate
     *
     * @param float $rate
     */
    public function convert($rate)
    {
        $this->rate /= $rate;
    }
}
