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

use Zend\EventManager\EventManagerAwareInterface,
    Zend\EventManager\EventManagerAwareTrait,
    Zend\Stdlib\RequestInterface,
    Zend\Stdlib\ResponseInterface,
    Zend\Uri\Uri,
    CmsLocale\Locale\Strategy\StrategyInterface;

class Detector implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Default locale
     *
     * @var string
     */
    protected $default;

    /**
     * Optional list of available locales
     *
     * @var array
     */
    protected $locales = [];

    /**
     * @param StrategyInterface $strategy
     * @param number $priority
     *
     * @return Detector
     */
    public function addStrategy(StrategyInterface $strategy, $priority = 1)
    {
        $this->getEventManager()->attachAggregate($strategy, $priority);
        return $this;
    }

    /**
     * Get default locale
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default locale
     *
     * @param string $default
     * @return Detector
     */
    public function setDefault($default)
    {
        $this->default = \Locale::canonicalize($default);
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
     * @return Detector
     */
    public function setLocales(array $locales)
    {
        $this->locales = array_map('\Locale::canonicalize', $locales);
        return $this;
    }

    /**
     * Check locales
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
        return in_array(\Locale::canonicalize($locale), $this->getLocales());
    }

    /**
     * Detect available locale
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface|string
     */
    public function detect(RequestInterface $request, ResponseInterface $response = null)
    {
        $event = new Event(Event::EVENT_DETECT, $this);
        $event->setRequest($request);
        $event->setResponse($response);

        if ($this->hasLocales()) {
            $event->setLocales($this->getLocales());
        }

        $eventManager  = $this->getEventManager();
        $results = $eventManager->trigger($event, function($r) {
            return is_string($r);
        });

        if ($results->stopped()) {
            $locale = $results->last();
        } else {
            $locale = $this->getDefault();
        }

        if ($this->hasLocales() && !$this->hasLocale($locale)) {
            $locale = $this->getDefault();
        }

        // Trigger FOUND event only when a response is given
        if ($response instanceof ResponseInterface) {
            $event->setName(Event::EVENT_FOUND);
            $event->setLocale($locale);

            $return = false;

            /**
             * The response will be returned instead of the found locale
             * only in case a adapter returned the response. This is an
             * indication the adapter has updated the response (e.g. with
             * a Location header) and as such, the response must be returned
             * instead of the locale.
             */
            $eventManager->trigger($event, function ($r) use (&$return) {
                if ($r instanceof ResponseInterface) {
                    $return = true;
                }
            });

            if ($return) {
                return $response;
            }
        }

        return $locale;
    }

    /**
     * @param string $locale
     * @param string|\Zend\Uri\Uri $uri
     * @return \Zend\Uri\Uri
     */
    public function assemble($locale, $uri)
    {
        $event = new Event(Event::EVENT_ASSEMBLE, $this);
        $event->setLocale($locale);

        if ($this->hasLocales()) {
            $event->setLocales($this->getLocales());
        }

        if (!$uri instanceof Uri) {
            $uri = new Uri($uri);
        }
        $event->setUri($uri);

        $eventManager = $this->getEventManager();
        $results = $eventManager->trigger($event);

        if (!$results->stopped()) {
            return $uri;
        }

        return $results->last();
    }
}
