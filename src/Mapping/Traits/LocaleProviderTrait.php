<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Mapping\Traits;

use CmsLocale\Mapping\LocaleInterface,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection;

trait LocaleProviderTrait
{
    /**
     * @var LocaleInterface[]
     *
     * @Form\Exclude()
     */
    protected $locales = [];

    /**
     * __construct
     *
     * Initializes locales
     */
    public function __construct()
    {
        $this->locales = new ArrayCollection();
    }

    /**
     * @param array|\Traversable $locales
     */
    public function setLocales($locales)
    {
        $this->clearLocales();
        $this->addLocales($locales);
    }

    /**
     * @param array|\Traversable $locales
     */
    public function addLocales($locales)
    {
        foreach ($locales as $locale) {
            $this->addLocale($locale);
        }
    }

    /**
     * @param LocaleInterface $locale
     */
    public function addLocale(\CmsLocale\Mapping\LocaleInterface $locale)
    {
        if (!$this->getLocales()->contains($locale)) {
            $this->getLocales()->add($locale);
            $locale->setRegion($this);
        }
    }

    /**
     * @param array|\Traversable $locales
     */
    public function removeLocales($locales)
    {
        foreach ($locales as $locale) {
            $this->removeLocale($locale);
        }
    }

    /**
     * @param LocaleInterface $locale
     */
    public function removeLocale(\CmsLocale\Mapping\LocaleInterface $locale)
    {
        $this->getLocales()->removeElement($locale);
    }

    /**
     * Removes all locales
     */
    public function clearLocales()
    {
        $this->getLocales()->clear();
    }

    /**
     * @return Collection
     */
    public function getLocales()
    {
        return $this->locales;
    }
}
