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

use CmsCommon\Mapping\Common\StateableInterface as BaseStateableInterface;

interface StateableInterface extends BaseStateableInterface
{
    const LOCALE_NOT_ACTIVE = 0;
    const LOCALE_ACTIVE     = 1;
}
