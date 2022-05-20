<?php

namespace App\Wallet\Interfaces;

interface Balance
{
    /**
     * @param Wallet $object
     * @return int|float
     */
    public function getBalance($object);

    /**
     * @param Wallet $object
     * @param int $amount
     * @param string $slug
     * @param string $type
     * @return boolean
     */
    public function incBalance($object, $amount, $slug, $type);

    /**
     * @param Wallet $object
     * @param int $amount
     * @param string $slug
     * @param string $type
     * @return boolean
     */
    public function decBalance($object, $amount, $slug, $type);

    /**
     * @param Wallet $object
     * @param int $amount
     * @param string $slug
     * @param string $type
     * @return boolean
     */
    public function setBalance($object, $amount, $slug, $type);

}
