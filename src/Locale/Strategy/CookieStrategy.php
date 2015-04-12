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

use Zend\Http\Header\Cookie,
    Zend\Http\Header\SetCookie,
    CmsLocale\Locale\Event;

class CookieStrategy extends AbstractStrategy
{
    const COOKIE_NAME = 'cmslocale';

    /**
     * The name of the cookie.
     *
     * @var string
     */
    protected $cookieName;

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options = [])
    {
        if (array_key_exists('cookie_name', $options)) {
            $this->setCookieName($options['cookie_name']);
        }
    }

    /**
     * @param Event $event
     * @return void|string
     */
    public function detect(Event $event)
    {
        $request    = $event->getRequest();
        $cookieName = $this->getCookieName();
        
        if (!$this->isHttpRequest($request) || !$event->hasLocales()) {
            return;
        }
        
        $cookie = $request->getCookie();
        if (!$cookie || !$cookie->offsetExists($cookieName)) {
            return;
        }
        
        $locale  = $cookie->offsetGet($cookieName);
        $locales = $event->getLocales();
        
        if (!$event->hasLocale($locale)) {
            return;
        }
        
        return $locale;
    }

    /**
     * @param Event $event
     * @return void
     */
    public function found(Event $event)
    {
        $locale     = $event->getLocale();
        $request    = $event->getRequest();
        $cookieName = $this->getCookieName();
        
        if (!$this->isHttpRequest($request)) {
            return;
        }
        
        $cookie = $request->getCookie();
        // Omit Set-Cookie header when cookie is present
        if ($cookie instanceof Cookie
            && $cookie->offsetExists($cookieName)
            && $locale === $cookie->offsetGet($cookieName)
        ) {
            return;
        }
        
        $path = '/';
        if (method_exists($request, 'getBasePath')) {
            $path = rtrim($request->getBasePath(), '/') . '/';
        }
        
        $response  = $event->getResponse();
        $setCookie = new SetCookie($cookieName, $locale, null, $path);
        
        $response->getHeaders()->addHeader($setCookie);
    }

    /**
     * @return string
     */
    public function getCookieName()
    {
        if (null === $this->cookieName) {
            return self::COOKIE_NAME;
        }

        return (string) $this->cookieName;
    }

    /**
     * @param string $cookieName
     * @throws Exception\InvalidArgumentException
     * @return void
     */
    public function setCookieName($cookieName)
    {
        if(!preg_match("/^(?!\\$)[!-~]+$/", $cookieName)) {
            throw new Exception\InvalidArgumentException($cookieName . " is not a vaild cookie name.");
        }
        $this->cookieName = $cookieName;
    }
}
