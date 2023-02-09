<?php
namespace SmartyModule\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Renderer\PhpRenderer;
use SmartyModule\View\Renderer\SmartyRenderer;


/**
 * Created by IntelliJ IDEA.
 * User: Nikolay
 * Date: 23.01.13
 * Time: 13:39
 */
class SmartyRendererFactory implements  FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $smarty = new \Smarty();
        $config = $container->get('Config');
        if (isset($config['view_manager']) && isset($config['view_manager']['smarty_defaults'])) {
            $smartyOptions = $config['view_manager']['smarty_defaults'];
            if (isset($config['view_manager']['smarty'])) {
                $smartyOptions = array_merge($smartyOptions, $config['view_manager']['smarty']);
            }
            foreach($smartyOptions as $key=>$value) {
                $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                if (method_exists($smarty, $setter)) {
                    $smarty->$setter($value);
                } elseif(property_exists($smarty, $key)) {
                    $smarty->$key = $value;
                }
            }
        }

        if (isset($config['view_manager'])
            && isset($config['view_manager']['smarty_set_path_stack_dirs'])
            && $config['view_manager']['smarty_set_path_stack_dirs']
            && isset($config['view_manager']['template_path_stack'])
        ){
            $smarty->setTemplateDir($config['view_manager']['template_path_stack']);
        }

        // add own smarty plugin files
        if (isset($config['view_manager']) && isset($config['view_manager']['plugin_path_stack'])) {
            $pluginPathStack = $config['view_manager']['plugin_path_stack'];

            if (is_array($pluginPathStack) && count($pluginPathStack) > 0) {
                foreach ($pluginPathStack as $pluginPath) {
                    if (is_dir($pluginPath)) {
                        $smarty->addPluginsDir($pluginPath);
                    }
                }
            }
        }

        $resolver = $container->get('SmartyViewResolver');
        $helpers = $container->get('ViewHelperManager');

        $renderer = new SmartyRenderer();
        $renderer ->setEventManager($container->get('EventManager'));
        $renderer ->setSmarty($smarty);
        $renderer ->setResolver($resolver);
        $renderer ->setHelperPluginManager($helpers);
        return $renderer;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @deprecated use invoke mechanism
     *
     * @return SmartyRenderer
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, SmartyRenderer::class);
    }
}
