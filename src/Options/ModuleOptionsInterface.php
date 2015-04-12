<?php 
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Options;

interface ModuleOptionsInterface
{
    /**
     * @param string $locale
     * @return static
     */
    public function setDefault($locale);

    /**
     * @return string
     */
    public function getDefault();

    /**
     * @param array|string $locales
     * @return static
     */
    public function setLocales($locales);

    /**
     * @return array
     */
    public function getLocales();

    /**
     * @param array|string $strategies
     * @return static
     */
    public function setStrategies($strategies);

    /**
     * @return array
     */
    public function getStrategies();
}
