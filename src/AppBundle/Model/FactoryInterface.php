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
     * @param mixed $raw
     */
    public function makeOne($raw);
    /**
     * @param mixed $raw
     */
    public function make($raw);
}