<?php 
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * Turn off strict options mode
     *
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $default = 'en';

    /**
     * @var array|string
     */
    protected $locales = ['en', 'en-US', 'en-GB'];

    /**
     * @var array|string
     */
    protected $strategies = [];

    /**
     * {@inheritDoc}
     */
    public function setDefault($locale)
    {
        $this->default = (string) $locale;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDefault()
    {
        return $this->default;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setLocales($locales)
    {
        $this->locales = (array) $locales;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLocales()
    {
        return $this->locales;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setStrategies($strategies)
    {
        $this->strategies = (array) $strategies;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getStrategies()
    {
        return $this->strategies;
    }
}
