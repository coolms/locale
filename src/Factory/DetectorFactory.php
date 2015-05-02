<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Factory;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    CmsLocale\Factory\Exception\StrategyConfigurationException,
    CmsLocale\Locale\Detector;

class DetectorFactory implements FactoryInterface
{
    /**
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Detector
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \CmsLocale\Options\ModuleOptionsInterface */
        $options = $serviceLocator->get('CmsLocale\\Options\\ModuleOptions');

        $detector = new Detector;
        $eventManager = $serviceLocator->get('EventManager');
        $detector->setEventManager($eventManager);

        $this->addAdapters($detector, $options->getStrategies(), $serviceLocator);

        if ($default = $options->getDefault()) {
            $detector->setDefault($default);
        }

        if ($locales = $options->getLocales()) {
            $detector->setLocales($locales);
        }

        return $detector;
    }

    /**
     * @param Detector $detector
     * @param array $strategies
     * @param ServiceLocatorInterface $serviceLocator
     * @throws AdapterConfigurationException
     */
    protected function addAdapters(Detector $detector, array $strategies, ServiceLocatorInterface $serviceLocator)
    {
        $plugins = $serviceLocator->get('CmsLocale\\Locale\\Strategy\\StrategyPluginManager');

        foreach ($strategies as $strategy) {
            if (is_string($strategy)) {
                $class = $plugins->get($strategy);
                $detector->addStrategy($class);
            } elseif (is_array($strategy)) {
                $name = $strategy['name'];
                $class = $plugins->get($name);

                if (array_key_exists('options', $strategy) && method_exists($class, 'setOptions')) {
                    $class->setOptions($strategy['options']);
                }

                $priority = array_key_exists('priority', $strategy) ? $strategy['priority'] : 1;
                $detector->addAdapter($class, $priority);

            } else {
                throw new StrategyConfigurationException(
                    'Adapter configuration must be a string or an array'
                );
            }
        }
    }
}
