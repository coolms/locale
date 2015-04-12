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

class QueryStrategy extends AbstractStrategy
{
    /**
     * Default query key
     * 
     * @var string
     */
    const QUERY_KEY = 'lang';

    /**
     * Query key to use for request
     *
     * @var string
     */
    protected $query_key;

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options = [])
    {
        if (array_key_exists('query_key', $options)) {
            $this->query_key = (string) $options['query_key'];
        }
    }

    /**
     * 
     * 
     * @return string
     */
    protected function getQueryKey()
    {
        if (null === $this->query_key) {
            $this->query_key = self::QUERY_KEY;
        }
        return $this->query_key;
    }

    /**
     * {@inheritdoc}
     */
    public function detect(Event $event)
    {
        $request = $event->getRequest();
        if (!$this->isHttpRequest($request) || !$event->hasLocales()) {
            return;
        }
        $locale = $request->getQuery($this->getQueryKey());
        if ($locale === null || !$event->hasLocale($locale)) {
            return;
        }
        return $locale;
    }

    /**
     * {@inheritDoc}
     */
    public function assemble(Event $event)
    {
        $uri         = $event->getUri();
        $locale      = $event->getLocale();
        $query       = $uri->getQueryAsArray();
        $key         = $this->getQueryKey();
        $query[$key] = $locale;
        $uri->setQuery($query);
        return $uri;
    }
}
