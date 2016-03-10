<?php
namespace AppBundle\Entity;
use AppBundle\Model\UserInterface;
use AppBundle\Model\UserGatewayInterface;
use Doctrine\ORM\EntityRepository;
/**
 * UserGateway.
 */
class UserGateway extends EntityRepository implements UserGatewayInterface
{

}