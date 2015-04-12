<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Locale\Strategy;

use CmsLocale\Locale\Event;

class HttpAcceptLanguageStrategy extends AbstractStrategy
{
    /**
     * {@inheritDoc}
     */
    public function detect(Event $event)
    {
        $request = $event->getRequest();
        if (!$this->isHttpRequest($request)) {
            return;
        }

        $lookup = $event->hasLocales();
        if ($lookup) {
            $locales = $event->getLocales();
        }

        $headers = $request->getHeaders();
        if ($headers->has('Accept-Language')) {
            foreach ($headers->get('Accept-Language')->getPrioritized() as $locale) {
                $locale = $locale->getLanguage();
                if (!$lookup) {
                    return $locale;
                }
                if (\Locale::lookup($locales, $locale)) {
                    return $locale;
                }
            }
        }
    }
}
