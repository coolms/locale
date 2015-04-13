<?php
/**
 * CoolMS2 Locale Module (http://www.coolms.com/)
 *
 * @link      http://github.com/coolms/locale for the canonical source repository
 * @copyright Copyright (c) 2006-2015 Altgraphic, ALC (http://www.altgraphic.com)
 * @license   http://www.coolms.com/license/new-bsd New BSD License
 * @author    Dmitry Popov <d.popov@altgraphic.com>
 */

namespace CmsLocale;

return [
    'controllers' => [
        'invokables' => [
            'CmsLocale\Controller\Index' => 'CmsLocale\Controller\IndexController',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . 'Driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . 'Driver',
                ],
            ],
        ],
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    'CmsLocale\Mapping\LocaleInterface' => 'CmsLocale\Entity\Locale',
                ],
            ],
        ],
    ],
    'listeners' => [
        'CmsLocale\EventListener\DefaultLocaleListener'
            => 'CmsLocale\EventListener\DefaultLocaleListener',
    ],
    'router' => [
        'routes' => [
            'cms-admin' => [
                'child_routes' => [
                    'locale' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/locale[/:controller[/:action[/:id]]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z\-]*',
                                'action' => '[a-zA-Z\-]*',
                                'id' => '[a-zA-Z0-9\-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'CmsLocale\Controller',
                                'controller' => 'Admin',
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'CmsLocale\Options\ModuleOptions' => 'CmsLocale\Options\ModuleOptionsInterface',
        ],
        'invokables' => [
            'CmsLocale\EventListener\DefaultLocaleListener'
                => 'CmsLocale\EventListener\DefaultLocaleListener',
            'CmsLocale\Locale\Strategy\StrategyPluginManager'
                => 'CmsLocale\Locale\Strategy\StrategyPluginManager',
        ],
        'factories'  => [
            'CmsLocale\Locale\Detector'                 => 'CmsLocale\Factory\DetectorFactory',
            'CmsLocale\Options\ModuleOptionsInterface'  => 'CmsLocale\Factory\ModuleOptionsFactory',
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => __NAMESPACE__,
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __NAMESPACE__ => __DIR__ . '/../view',
        ],
    ],
];
