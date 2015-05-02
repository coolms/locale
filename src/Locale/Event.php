<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Locale;

use Zend\EventManager\Event as ZendEvent,
    Zend\Stdlib\RequestInterface,
    Zend\Stdlib\ResponseInterface,
    Zend\Uri\Uri;

class Event extends ZendEvent
{
    const EVENT_DETECT   = 'detect';
    const EVENT_FOUND    = 'found';
    const EVENT_ASSEMBLE = 'assemble';

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var Uri
     */
    protected $uri;

    /**
     * Get request
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set request
     *
     * @param RequestInterface $request
     * @return Event
     */
    public function setRequest(RequestInterface $request)
    {
        $this->setParam('request', $request);
        $this->request = $request;
        return $this;
    }

    /**
     * Get response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response
     *
     * @param ResponseInterface $response
     * @return Event
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->setParam('response', $response);
        $this->response = $response;
        return $this;
    }

    /**
     * Get available locales
     *
     * @return array
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * Set available locales
     *
     * @param array $locales
     * @return Event
     */
    public function setLocales(array $locales)
    {
        $this->setParam('locales', $locales);
        $this->locales = $locales;
        return $this;
    }

    /**
     * Has available locales
     *
     * @return boolean
     */
    public function hasLocales()
    {
        return !! count($this->locales);
    }

    /**
     * Check whether locale is available
     *
     * @param string $locale
     */
    public function hasLocale($locale)
    {
    	return in_array($locale, $this->getLocales());
    }

    /**
     * Get default locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set default locale
     *
     * @param string $locale
     * @return Event
     */
    public function setLocale($locale)
    {
        $this->setParam('locale', $locale);
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get uri for assemble event
     *
     * @return Uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set uri for assemble event
     *
     * @param  Uri $uri
     * @return Event
     */
    public function setUri(Uri $uri)
    {
        $this->setParam('uri', $uri);
        $this->uri = $uri;
        return $this;
    }
}
