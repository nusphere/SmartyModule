<?php

/**
 * @link        https://github.com/MurgaNikolay/SmartyModule for the canonical source repository
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Murga Nikolay <work@murga.kiev.ua>
 * @package     SmartyModule
 */

namespace SmartyModule\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use SmartyModule\Resolver\SmartyViewTemplatePathStack;

class SmartyViewTemplatePathStackFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $templatePathStack = new SmartyViewTemplatePathStack();
        if (is_array($config) && isset($config['view_manager'])) {
            $config = $config['view_manager'];
            if (is_array($config) && isset($config['template_path_stack'])) {
                $templatePathStack->addPaths($config['template_path_stack']);
            }
            if (is_array($config) && isset($config['smarty_default_suffix'])) {
                $templatePathStack->setDefaultSuffix($config['smarty_default_suffix']);
            }
        }

        return $templatePathStack;
    }

    /**
     * Create service
     *
     * @deprecated use invoke mechanism
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, SmartyViewTemplatePathStack::class);
    }
}
