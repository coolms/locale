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
        'aliaeses' => [
            'CmsLocale\Controller\Admin' => 'CmsLocale\Mvc\Controller\AdminController',
        ],
        'invokables' => [
            'CmsLocale\Mvc\Controller\AdminController' => 'CmsLocale\Mvc\Controller\AdminController',
        ],
    ],
    'listeners' => [
        'CmsLocale\Event\DefaultLocaleListener' => 'CmsLocale\Event\DefaultLocaleListener',
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
            'CmsLocale\Options\ModuleOptionsInterface' => 'CmsLocale\Options\ModuleOptions',
        ],
        'delegators' => [
            'MvcTranslator' => [
                'CmsLocale\Factory\MvcTranslatorDelegatorFactory'
                    => 'CmsLocale\Factory\MvcTranslatorDelegatorFactory',
            ],
        ],
        'factories'  => [
            'CmsLocale\Locale\Detector' => 'CmsLocale\Factory\DetectorFactory',
            'CmsLocale\Options\ModuleOptions' => 'CmsLocale\Factory\ModuleOptionsFactory',
        ],
        'invokables' => [
            'CmsLocale\Event\DefaultLocaleListener' => 'CmsLocale\Event\DefaultLocaleListener',
            'CmsLocale\Locale\Strategy\StrategyPluginManager'
                => 'CmsLocale\Locale\Strategy\StrategyPluginManager',
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
                'text_domain' => __NAMESPACE__,
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __NAMESPACE__ => __DIR__ . '/../view',
        ],
    ],
];
