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

class HostStrategy extends AbstractStrategy
{
    const LOCALE_KEY           = ':locale';
    const REDIRECT_STATUS_CODE = 302;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @var bool
     */
    protected $redirect_to_canonical;

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options = [])
    {
        if (array_key_exists('domain', $options)) {
            $this->domain = (string) $options['domain'];
        }
        if (array_key_exists('aliases', $options)) {
            $this->aliases = (array) $options['aliases'];
        }
        if (array_key_exists('redirect_to_canonical', $options)) {
            $this->redirect_to_canonical = (bool) $options['redirect_to_canonical'];
        }
    }

    /**
     * Get domain name
     * 
     * @return string
     */
    protected function getDomain()
    {
        return $this->domain;
    }

    /**
     * Get domain name/locale aliases
     * 
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
        if (!$this->isHttpRequest($request) || !$event->hasLocales()) {
            return;
        }

        $domain = $this->getDomain();
        if (null !== $domain) {
            throw new Exception\InvalidArgumentException(
                'The Host adapter must be configured with a domain option'
            );
        }
        if (strpos($domain, self::LOCALE_KEY)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'The domain %s must contain a locale key part "%s"', $domain, self::LOCALE_KEY
            ));
        }

        $host    = $request->getUri()->getHost();
        $pattern = str_replace(self::LOCALE_KEY, '([a-zA-Z-_.]+)', $domain);
        $pattern = sprintf('/%s/', $pattern);
        $result  = preg_match($pattern, $host, $matches);

        if (!$result) {
            return;
        }

        $locale = $matches[1];
        $aliases = $this->getAliases();
        if ($aliases && array_key_exists($locale, $aliases)) {
            $locale = $aliases[$locale];
        }

        if (!$event->hasLocale($locale)) {
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
        if (!$this->isHttpRequest($request) || !$event->hasLocales()) {
            return;
        }

        $locale = $event->getLocale();
        if (null === $locale) {
            return;
        }

        // By default, use the alias to redirect to
        if (!$this->redirectToCanonical()) {
            $locale = $this->getAliasForLocale($locale);
        }

        $host = str_replace(self::LOCALE_KEY, $locale, $this->getDomain());
        $uri  = $request->getUri();
        if ($host === $uri->getHost()) {
            return;
        }

        $uri->setHost($host);

        $response = $event->getResponse();
        $response->setStatusCode(self::REDIRECT_STATUS_CODE);
        $response->getHeaders()->addHeaderLine('Location', $uri->toString());

        return $response;
    }

    /**
     * Helper method.
     * 
     * @param string $locale
     * @return string|void
     */
    protected function getAliasForLocale($locale)
    {
        foreach ($this->getAliases() as $alias => $item) {
            if ($item === $locale) {
                return $alias;
            }
        }
    }
}
