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

use CmsCommon\Mapping\Common\IdentifiableInterface,
    CmsCommon\Mapping\Common\NameableInterface,
    CmsCldr\Mapping\LanguageInterface,
    CmsCldr\Mapping\RegionInterface;

interface LocaleInterface extends
    IdentifiableInterface,
    NameableInterface,
    StateableInterface
{
    /**
     * @param LanguageInterface $language
     */
    public function setLanguage(LanguageInterface $language);

    /**
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     * @param RegionInterface $region
     */
    public function setRegion(RegionInterface $region = null);

    /**
     * @return RegionInterface
     */
    public function getRegion();

    /**
     * @return string
     */
    public function getCanonicalName();
}
