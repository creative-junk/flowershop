<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/17/2017
 * Time: 1:38 PM
 ********************************************************************************/

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Class UserToUsernameTransformer
 * @package AppBundle\Form\DataTransformer
 * Transforms between a User instance and a username string
 */
class UserToUsernameTransformer implements DataTransformerInterface
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * UserToUsernameTransformer constructor.
     * @param Registry $doctrine
     */
    function __construct(Registry $doctrine)
    {
        $this->repository =   $doctrine->getManager()->getRepository("AppBundle:User");
    }

    /**
     * Transforms a User instance into a username string
     *
     * @param mixed $value
     *
     * @return null|string
     *
     * @throws UnexpectedTypeException if the given value is not a User instance
     */
    public function transform($value)
    {
        if (null== $value){
            return null;
        }
        if (! $value instanceof User){
            throw new UnexpectedTypeException($value,'AppBundle\Entity\User');
        }
        return $value->getUsername();
    }

    /**
     * Transforms a username string into a User instance
     * @param mixed $value
     * @return User|null
     * @throws UnexpectedTypeException if the given value is not a string
     */
    public function reverseTransform($value)
    {
        if (null == $value || '' == $value){
            return null;
        }

        if (!is_string($value)){
            throw new UnexpectedTypeException($value,'string');
        }

        return $this->repository->findOneByUsername($value);
    }
}