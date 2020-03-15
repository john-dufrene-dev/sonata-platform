<?php

namespace App\Service;

use App\Entity\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * ConfigurationBuilder
 */
class ConfigurationBuilder
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
     * @param  mixed $name
     * @return string
     */
    public function get($name): ?string
    {
        $item = $this->cache->getItem('caching_value_'.md5($name));

        if (!$item->isHit()) {

            if(!$config = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $name, 'enabled' => 1])) {
                return null;
            }
            
            $item->set($config->getValue());
            $this->cache->save($item);
        }

        $value = $item->get();

        return $value;
    }
        
    /**
     * update
     *
     * @param  mixed $name
     * @param  mixed $value
     * @return bool
     */
    public function update($name, $value = null): ?bool
    {
        if(!$config = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $name])) {
            return false;
        }

        $config->setValue($value);

        $item = $this->cache->getItem('caching_value_'.md5($name));

        if ($item->isHit()) {
            $this->cache->deleteItem('caching_value_'.md5($name));
        }

        $item->set($config->getValue());
        $this->cache->save($item);

        $this->em->persist($config);
        $this->em->flush();

        return true;
    }
    
    /**
     * remove
     *
     * @param  mixed $name
     * @return bool
     */
    public function remove($name): ?bool
    {
        if (!$this->cache->hasItem('caching_value_'.md5($name))) {
            return false;
        }

        $this->cache->deleteItem('caching_value_'.md5($name));

        return true;
    }
}
