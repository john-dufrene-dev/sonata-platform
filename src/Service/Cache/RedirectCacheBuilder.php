<?php

namespace App\Service\Cache;

use App\Entity\Redirect\Redirect;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * RedirectCacheBuilder
 */
class RedirectCacheBuilder
{
    /**
     * em
     *
     * @var mixed
     */
    protected $em;

    /**
     * cache
     *
     * @var mixed
     */
    protected $cache;

    /**
     * __construct
     *
     * @param  mixed $config
     * @param  mixed $cache
     * @return void
     */
    public function __construct(EntityManagerInterface $em,  AdapterInterface $cache)
    {
        $this->cache = $cache;
        $this->em = $em;
    }
    
    /**
     * get
     *
     * @param  mixed $source
     * @return array
     */
    public function get($source): ?array
    {
        $item = $this->cache->getItem('redirecting_value_' . md5($source));

        if(null == $item->get()) {
            return null;
        }

        if (!$item->isHit()) {

            if (!$config = $this->em->getRepository(Redirect::class)->findRedirect($source)) {
                return null;
            }

            $add = [];
            $add['destination'] = $config['destination'];
            $add['code']        = $config['httpCode'];

            $item->set($add);
            $this->cache->save($item);
        }

        $value = $item->get();

        return $value;
    }
    
    /**
     * update
     *
     * @param  mixed $source
     * @param  mixed $destination
     * @param  mixed $code
     * @return bool
     */
    public function update($source, $destination, $code = 302): ?bool
    {
        if (!$config = $this->em->getRepository(Redirect::class)->findRedirect($source)) {
            return false;
        }

        if(null == $destination) {
            return false;
        }

        $item = $this->cache->getItem('redirecting_value_' . md5($source));

        if ($item->isHit()) {
            $this->cache->deleteItem('redirecting_value_' . md5($source));
        }

        $add = [];
        $add['destination'] = $destination;
        $add['code']        = $code;

        $item->set($add);
        $this->cache->save($item);

        return true;
    }

    /**
     * remove
     *
     * @param  mixed $name
     * @return bool
     */
    public function remove($source): ?bool
    {
        if (!$this->cache->hasItem('redirecting_value_' . md5($source))) {
            return false;
        }

        $this->cache->deleteItem('redirecting_value_' . md5($source));

        return true;
    }
}
