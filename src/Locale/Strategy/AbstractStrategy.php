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

use Zend\EventManager\EventManagerInterface,
    Zend\Http\Request as HttpRequest,
    Zend\Stdlib\RequestInterface,
    CmsLocale\Locale\Event;

abstract class AbstractStrategy implements StrategyInterface
{
    /**
     * Listeners we've registered
     * 
     * @var array
     */
    protected $listeners = [];

    /**
     * Attach "detect", "found" and "assemble" listeners
     * 
     * @param EventManagerInterface $eventManager
     * @param int                   $priority
    */
    public function attach(EventManagerInterface $eventManager, $priority = 1)
    {
        $this->listeners[] = $eventManager->attach(Event::EVENT_DETECT,    [$this, 'detect'],   $priority);
        $this->listeners[] = $eventManager->attach(Event::EVENT_FOUND,     [$this, 'found'],    $priority);
        $this->listeners[] = $eventManager->attach(Event::EVENT_ASSEMBLE,  [$this, 'assemble'], $priority);
    }

    /**
     * Detach all previously attached listeners
     * 
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $eventManager)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($eventManager->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function detect(Event $event)
    {
    }

    public function found(Event $event)
    {
    }

    public function assemble(Event $event)
    {
    }

    protected function isHttpRequest(RequestInterface $request)
    {
        return $request instanceof HttpRequest;
    }
}
