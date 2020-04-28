<?php

namespace App\Service\Cache;

use App\Entity\Configuration\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * ConfigurationCacheBuilder
 */
class ConfigurationCacheBuilder
{
    /**
     * startSeo
     *
     * @var string
     */
    protected $startSeo = 'CONF_SEO_';

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
        $item = $this->cache->getItem('caching_value_' . md5($name));

        if (!$item->isHit()) {

            if (!$config = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $name, 'enabled' => 1])) {
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
        if (!$config = $this->em->getRepository(Configuration::class)->findOneBy(['name' => $name])) {
            return false;
        }

        $config->setValue($value);

        $item = $this->cache->getItem('caching_value_' . md5($name));

        if ($item->isHit()) {
            $this->cache->deleteItem('caching_value_' . md5($name));
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
        if (!$this->cache->hasItem('caching_value_' . md5($name))) {
            return false;
        }

        $this->cache->deleteItem('caching_value_' . md5($name));

        return true;
    }

    /**
     * getSeoValue
     *
     * @param  mixed $value
     * @return void
     */
    public function getSeoValue($value = '')
    {
        $robots = ( $this->get($this->startSeo . $value . '_NO_INDEXATION') == 0 
            && $this->get($this->startSeo . 'INDEXATION') == 1)
            ? 'index,follow' 
            : 'noindex,nofollow';

        return [
            'title' => $this->get($this->startSeo . $value . '_TITLE'),
            'description' => $this->get($this->startSeo . $value . '_DESCRIPTION'),
            'index' => $robots,
        ];
    }
}
