<?php

namespace app\service;

use yii\caching\CacheInterface;

/**
 * Class CacheService
 *
 * @package app\service
 */
class CacheService
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }
}
