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

use Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorAwareTrait,
    Zend\Uri\Uri,
    Zend\Mvc\Router\Http\TreeRouteStack,
    Zend\Mvc\Router\RouteInterface,
    CmsLocale\Locale\Event;

class UriPathStrategy extends AbstractStrategy implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const REDIRECT_STATUS_CODE = 302;

    /**
     * @var bool
     */
    protected $redirect_when_found = true;

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @var bool
     */
    protected $redirect_to_canonical;

    /**
     * Set options
     * 
     * @param array $options
     * 
     * @return void
     */
    public function setOptions(array $options = [])
    {
        if (array_key_exists('redirect_when_found', $options)) {
            $this->redirect_when_found = (bool) $options['redirect_when_found'];
        }
        if (array_key_exists('aliases', $options)) {
            $this->aliases = (array) $options['aliases'];
        }
        if (array_key_exists('redirect_to_canonical', $options)) {
            $this->redirect_to_canonical = (bool) $options['redirect_to_canonical'];
        }
    }

    /**
     * @return RouteInterface
     */
    protected function getRouter()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('router');
    }

    /**
     * @return bool
     */
    protected function redirectWhenFound()
    {
        return $this->redirect_when_found;
    }

    /**
     * @return array
     */
    protected function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @return boolean
     */
    protected function redirectToCanonical()
    {
        return $this->redirect_to_canonical;
    }

    /**
     * {@inheritDoc}
     */
    public function detect(Event $event)
    {
        $request = $event->getRequest();
        if (!$this->isHttpRequest($request)) {
            return;
        }
        
        $base = $this->getBasePath();
        $locale = $this->getFirstSegmentInPath($request->getUri(), $base);
        if (!$locale) {
            return;
        }
        
        $aliases = $this->getAliases();
        if ($aliases && array_key_exists($locale, $aliases)) {
            $locale = $aliases[$locale];
        }
        
        if (!$event->hasLocales() || !$event->hasLocale($locale)) {
            return;
        }
        
        return $locale;
    }

    /**
     * {@inheritDoc}
     */
    public function found(Event $event)
    {
        $request = $event->getRequest();
        if (!$this->isHttpRequest($request)) {
            return;
        }
        
        $locale = $event->getLocale();
        if (null === $locale) {
            return;
        }
        
        if (!$this->redirectToCanonical() && $this->getAliases()) {
            $alias = $this->getAliasForLocale($locale);
            if (null !== $alias) {
                $locale = $alias;
            }
        }
        
        $base  = $this->getBasePath();
        $found = $this->getFirstSegmentInPath($request->getUri(), $base);
        
        $this->getRouter()->setBaseUrl($base . '/' . $locale);
        if ($locale === $found || !$this->redirectWhenFound()) {
            return;
        }
        
        $uri  = $request->getUri();
        $path = $uri->getPath();
        
        if (!$found || ($event->hasLocales() && !$event->hasLocale($found))) {
            $path = '/' . $locale . $path;
        } else {
            $path = str_replace($found, $locale, $path);
        }
        
        $uri->setPath($path);
        
        $response = $event->getResponse();
        $response->setStatusCode(self::REDIRECT_STATUS_CODE);
        $response->getHeaders()->addHeaderLine('Location', $uri->toString());
        
        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function assemble(Event $event)
    {
        $uri     = $event->getUri();
        $base    = $this->getBasePath();
        $locale  = $event->getLocale();
        
        $current = $this->getFirstSegmentInPath($uri, $base);
        
        if (!$this->redirectToCanonical() && $this->getAliases()) {
            $alias = $this->getAliasForLocale($locale);
            if (null !== $alias) {
                $locale = $alias;
            }
        }
        
        $path = $uri->getPath();
        
        // Last part of base is now always locale, remove that
        $parts = explode('/', trim($base, '/'));
        array_pop($parts);
        $base  = implode('/', $parts);
        
        if ($base) {
            $path = substr($path, strlen($base));
        }
        $parts = explode('/', trim($path, '/'));
        
        // Remove first part
        array_shift($parts);
        
        $path = $base . '/' . $locale . '/' . implode('/', $parts);
        $uri->setPath($path);
        
        return $uri;
    }

    /**
     * Helper method.
     * 
     * @param Uri $uri
     * @param string $base
     * 
     * @return string
     */
    protected function getFirstSegmentInPath(Uri $uri, $base = null)
    {
        $path = $uri->getPath();
        
        if ($base) {
            $path = substr($path, strlen($base));
        }
        
        $parts  = explode('/', trim($path, '/'));
        $locale = array_shift($parts);
        
        return $locale;
    }

    /**
     * Helper method.
     * 
     * @param string $locale
     * 
     * @return void|string
     */
    protected function getAliasForLocale($locale)
    {
        foreach ($this->getAliases() as $alias => $item) {
            if ($item === $locale) {
                return $alias;
            }
        }
    }

    /**
     * Helper method.
     * 
     * @return Ambigous <NULL, string>
     */
    protected function getBasePath()
    {
        $base   = null;
        $router = $this->getRouter();
        if ($router instanceof TreeRouteStack) {
            $base = $router->getBaseUrl();
        }
        return $base;
    }
}
