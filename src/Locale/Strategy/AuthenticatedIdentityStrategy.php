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

use Zend\Authentication\AuthenticationServiceInterface,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorAwareTrait,
    CmsLocale\Locale\Event,
    CmsLocale\Mapping\LocalizableInterface;

class AuthenticatedIdentityStrategy extends AbstractStrategy implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var AuthenticationServiceInterface
     */
    protected $authenticationService;

    /**
     * __construct
     *
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(AuthenticationServiceInterface $authenticationService = null)
    {
        if (null !== $authenticationService) {
            $this->setAuthenticationService($authenticationService);
        }
    }

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options = [])
    {
        if (!empty($options['authentication_service'])) {
            $strategies = $this->getServiceLocator();
            $services   = $strategies->getServiceLocator();
            $this->setAuthenticationService($services->get($options['authentication_service']));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function detect(Event $event)
    {
        if (!($authService = $this->getAuthenticationService())) {
            return;
        }

        if ($lookup = $event->hasLocales()) {
            $locales = $event->getLocales();
        }

        if ($authService->hasIdentity()) {
            $identity = $authService->getIdentity();
            if ($identity instanceof LocalizableInterface && $identity->getLocale()) {
                $locale = $identity->getLocale()->getCanonicalName();
                if (!$lookup) {
                    return $locale;
                }
                if (\Locale::lookup($locales, $locale)) {
                    return $locale;
                }
            }
        }
    }

    /**
     * @return AuthenticationServiceInterface
     */
    public function getAuthenticationService()
    {
        if (null === $this->authenticationService) {
            $strategies     = $this->getServiceLocator();
            $parentLocator  = $strategies->getServiceLocator();
            if ($parentLocator->has('Zend\\Authentication\\AuthenticationServiceInterface')) {
                $authenticationService = $parentLocator->get('Zend\\Authentication\\AuthenticationServiceInterface');
                $this->setAuthenticationService($authenticationService);
            }
        }

        return $this->authenticationService;
    }

    /**
     * @param AuthenticationServiceInterface $authenticationService
     * @return self
     */
    public function setAuthenticationService(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;

        return $this;
    }
}
