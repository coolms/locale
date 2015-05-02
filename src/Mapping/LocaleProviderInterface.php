<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Mapping;

interface LocaleProviderInterface
{
    /**
     * @param array|\Traversable $locales
     */
    public function setLocales($locales);

    /**
     * @param array|\Traversable $locales
     */
    public function addLocales($locales);

    /**
     * @param LocaleInterface $locale
     */
    public function addLocale(LocaleInterface $locale);

    /**
     * @param array|\Traversable $locales
     */
    public function removeLocales($locales);

    /**
     * @param LocaleInterface $locale
     */
    public function removeLocale(LocaleInterface $locale);

    /**
     * Removes all locales
     */
    public function clearLocales();

    /**
     * @return LocaleInterface[]
     */
    public function getLocales();
}
