<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 * 
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Locale\Strategy\Exception;

use CmsLocale\Exception\ExceptionInterface;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}