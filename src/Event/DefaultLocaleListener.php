<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Event;

use Locale,
    Zend\EventManager\AbstractListenerAggregate,
    Zend\EventManager\EventManagerInterface,
    Zend\Http\Request as HttpRequest,
    Zend\Mvc\MvcEvent,
    Zend\Stdlib\ResponseInterface;

/**
 * Locale event listener
 *
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class DefaultLocaleListener extends AbstractListenerAggregate
{
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
    	$this->listeners[] = $events->attach(MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap'], PHP_INT_MAX);
    }

    /**
     * Event callback to be triggered on bootstrap
     *
     * @param MvcEvent $e
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {
        if (!$e->getRequest() instanceof HttpRequest) {
            return;
        }

        $app = $e->getApplication();
        $services = $app->getServiceManager();

        $detector = $services->get('CmsLocale\\Locale\\Detector');
        $result = $detector->detect($app->getRequest(), $app->getResponse());

        if ($result instanceof ResponseInterface) {
            /**
             * When the detector returns a response, a adapter has updated the response
             * to reflect the found locale.
             *
             * To redirect the user to this new URI, we short-circuit the route event. There
             * is no option to short-circuit the bootstrap event, so we attach a listener to
             * the route and let the application finish the bootstrap first.
             *
             * The listener is attached at PHP_INT_MAX to return the response as early as
             * possible.
             */
            $em = $app->getEventManager();
            $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($result) {
                return $result;
            }, PHP_INT_MAX);
        }

        $canonicalizedLocale = Locale::canonicalize($result);
        Locale::setDefault($canonicalizedLocale);
    }
}
