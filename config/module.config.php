<?php

use SmartyModule\Resolver\SmartyViewTemplatePathStack;
use SmartyModule\Resolver\SmartyViewResolver;
use SmartyModule\Resolver\SmartyViewTemplateMapResolver;
use SmartyModule\Service\SmartyStrategyFactory;
use SmartyModule\Service\SmartyRendererFactory;
use SmartyModule\Service\SmartyViewTemplatePathStackFactory;
use SmartyModule\Service\SmartyViewTemplateMapResolverFactory;
use SmartyModule\Service\SmartyViewResolverFactory;
use SmartyModule\View\Renderer\SmartyRenderer;
use SmartyModule\View\Strategy\SmartyStrategy;

/**
 * @link        https://github.com/MurgaNikolay/SmartyModule for the canonical source repository
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Murga Nikolay <work@murga.kiev.ua>
 * @package     SmartyModule
 */


$dataDir = __DIR__ . '/../../../../data';
if (!is_dir($dataDir)) {
    $dataDir = __DIR__ . '/../../../data';
}

return array(
    'view_manager' => array(
        'smarty_default_suffix' => 'tpl',

        /**
         * set this to true, to inject all path stack directories into smarty
         * this will allow you to include templates with paths relative to path stack directories
         */
        'smarty_set_path_stack_dirs' => false,

        /**
         * add additional folders for your own plugin files, they will be added to the plugin folder stack
         */
        'plugin_path_stack' => [],

        /**
         * Register the view strategy with the view manager. This is required!
         */
        'strategies' => array(
            'SmartyStrategy'
        ),
        'smarty_defaults' => array(
            'compile_dir' => $dataDir . '/SmartyModule/templates_c',
            //'error_reporting' => E_ERROR
        ),
    ),
    'service_manager' => [
        'aliases' => [
            'SmartyViewResolver' => SmartyViewResolver::class,
            'SmartyViewTemplateMapResolver' => SmartyViewTemplateMapResolver::class,
            'SmartyViewTemplatePathStack' => SmartyViewTemplatePathStack::class,
            'SmartyRenderer' => SmartyRenderer::class,
            'SmartyStrategy' => SmartyStrategy::class
        ],
        'factories' => [
            SmartyViewResolver::class            => SmartyViewResolverFactory::class,
            SmartyViewTemplateMapResolver::class => SmartyViewTemplateMapResolverFactory::class,
            SmartyViewTemplatePathStack::class   => SmartyViewTemplatePathStackFactory::class,
            SmartyRenderer::class                => SmartyRendererFactory::class,
            SmartyStrategy::class                => SmartyStrategyFactory::class,
        ],
    ],
);
