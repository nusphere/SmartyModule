<?php
namespace SmartyModule\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SmartyModule\View\Renderer\SmartyRenderer;
use SmartyModule\View\Strategy\SmartyStrategy;


/**
 * Created by IntelliJ IDEA.
 * User: Nikolay
 * Date: 23.01.13
 * Time: 13:39
 */
class SmartyStrategyFactory implements  FactoryInterface {


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $smartyRenderer = $container->get(SmartyRenderer::class);
        $smartyStrategy = new SmartyStrategy($smartyRenderer);
        return $smartyStrategy;
    }

    /**
     * Create service
     *
     * @deprecated use invoke mechanism
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return SmartyStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
       return $this($serviceLocator, SmartyStrategy::class);
    }
}
