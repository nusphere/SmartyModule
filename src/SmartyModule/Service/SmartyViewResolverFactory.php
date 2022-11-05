<?php

namespace SmartyModule\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Resolver as ViewResolver;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use SmartyModule\Resolver\SmartyViewResolver;
use SmartyModule\Resolver\SmartyViewTemplateMapResolver;
use SmartyModule\Resolver\SmartyViewTemplatePathStack;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Service
 */
class SmartyViewResolverFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $resolver = new SmartyViewResolver();
        $resolver->attach($container->get(SmartyViewTemplateMapResolver::class));
        $resolver->attach($container->get(SmartyViewTemplatePathStack::class));
        return $resolver;
    }

    /**
     * Create the aggregate view resolver
     *
     * @deprecated use invoke mechanism
     *
     * Creates a Laminas\View\Resolver\AggregateResolver and attaches the template
     * map resolver and path stack resolver
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return ViewResolver\AggregateResolver
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, SmartyViewResolver::class);
    }
}
