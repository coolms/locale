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

use Locale,
    Zend\I18n\Translator\Resources,
    Zend\ServiceManager\DelegatorFactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    CmsLocale\Locale\Detector;

/**
 * @author Dmitry Popov <d.popov@altgraphic.com>
 */
class MvcTranslatorDelegatorFactory implements DelegatorFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createDelegatorWithName(
        ServiceLocatorInterface $services,
        $name,
        $requestedName,
        $callback
    ) {
        /* @var $translator \Zend\I18n\Translator\Translator */
        $translator = $callback();

        $locale = Locale::getDefault();

        /* @var $detector Detector */
        $detector = $services->get(Detector::class);
        $defaultLocale = Locale::canonicalize($detector->getDefault());

        $translator->setLocale($locale)
            ->setFallbackLocale($defaultLocale);

        $dirName = $this->getLanguageDirectoryName($locale, $defaultLocale);
        if ($dirName) {
            $translator->addTranslationFile(
                'phpArray',
                sprintf(Resources::getBasePath() . Resources::getPatternForValidator(), $dirName),
                'default',
                $locale
            );
            $translator->addTranslationFile(
                'phpArray',
                sprintf(Resources::getBasePath() . Resources::getPatternForCaptcha(), $dirName),
                'default',
                $locale
            );
        }

        return $translator;
    }

    /**
     * @param string $locale
     * @param string $fallbackLocale
     * @return string|null
     */
    protected function getLanguageDirectoryName($locale, $fallbackLocale = null)
    {
        $basePath = Resources::getBasePath() . $locale;
        if (is_dir($basePath)) {
            return $locale;
        }

        $lang = Locale::getPrimaryLanguage($locale);
        $basePath = Resources::getBasePath() . $lang;

        if (!is_dir($basePath)) {
            if ($fallbackLocale) {
                return $this->getLanguageDirectoryName($fallbackLocale);
            }
        } else {
            return $lang;
        }
    }
}
