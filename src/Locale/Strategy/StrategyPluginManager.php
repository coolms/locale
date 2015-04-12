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

use Zend\ServiceManager\AbstractPluginManager;

class StrategyPluginManager extends AbstractPluginManager
{
    /**
     * {@inheritDoc}
     */
    protected $invokableClasses = [
        'cookie'                => 'CmsLocale\Locale\Strategy\CookieStrategy',
        'host'                  => 'CmsLocale\Locale\Strategy\HostStrategy',
        'acceptlanguage'        => 'CmsLocale\Locale\Strategy\HttpAcceptLanguageStrategy',
        'query'                 => 'CmsLocale\Locale\Strategy\QueryStrategy',
        'uripath'               => 'CmsLocale\Locale\Strategy\UriPathStrategy',
        'authenticatedidentity' => 'CmsLocale\Locale\Strategy\AuthenticatedIdentityStrategy',
    ];

    /**
     * Validate the plugin
     *
     * Checks that the helper loaded is an instance of StrategyInterface.
     *
     * @param  mixed    $plugin
     * @return void
     * @throws Exception\InvalidAdapterException if invalid
    */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof StrategyInterface) {
            // plugin is correct
            return;
        }

        throw new Exception\InvalidAdapterException(sprintf(
            'Plugin of type %s is invalid; must implement %s\StrategyInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
