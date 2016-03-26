<?php
namespace AppBundle\Model;
/**
 * FactoryInterface.
 */
interface FactoryInterface
{
    /**
     * @param array $array
     */
    public function makeAll(array $array);
    /**
     * @param type $raw
     */
    public function makeOne($raw);
    /**
     * @param type $raw
     */
    public function make($raw);
}