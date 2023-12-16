<?php

namespace SmartyModuleTest;

use ApplicationTest\Bootstrap;
use PHPUnit\Framework\TestCase;
use SmartyModule\Resolver\SmartyViewResolver;
use SmartyModule\Resolver\SmartyViewTemplateMapResolver;
use SmartyModule\Resolver\SmartyViewTemplatePathStack;
use SmartyModule\View\Renderer\SmartyRenderer;
use SmartyModule\View\Strategy\SmartyStrategy;
use Laminas\View\Resolver\ResolverInterface;
use Laminas\View\Resolver\TemplateMapResolver;
use Laminas\View\Resolver\TemplatePathStack;

final class ModuleTest extends TestCase
{
    public function testModuleProvidesAliases(): void
    {
        $sm = Bootstrap::getServiceManager();

        $this->assertTrue($sm->has('SmartyViewResolver'));
        $this->assertTrue($sm->has('SmartyViewTemplateMapResolver'));
        $this->assertTrue($sm->has('SmartyViewTemplatePathStack'));
        $this->assertTrue($sm->has('SmartyRenderer'));
        $this->assertTrue($sm->has('SmartyStrategy'));
    }

    public function testModuleProvidesFactories(): void
    {
        $sm = Bootstrap::getServiceManager();

        $this->assertTrue($sm->has(SmartyViewResolver::class));
        $this->assertTrue($sm->has(SmartyViewTemplateMapResolver::class));
        $this->assertTrue($sm->has(SmartyViewTemplatePathStack::class));
        $this->assertTrue($sm->has(SmartyRenderer::class));
        $this->assertTrue($sm->has(SmartyStrategy::class));
    }

    /**
     * @dataProvider provideServices
     */
    public function testCanCreateService(string $serviceName, string $expectedClassName): void
    {
        $sm = Bootstrap::getServiceManager();

        $service = $sm->get($serviceName);
        $this->assertInstanceOf($expectedClassName, $service);
    }

    public static function provideServices(): array
    {
        return [
            [
                'SmartyViewResolver',
                ResolverInterface::class
            ],
            [
                'SmartyViewTemplateMapResolver',
                TemplateMapResolver::class
            ],
            [
                'SmartyViewTemplatePathStack',
                TemplatePathStack::class
            ],
            [
                'SmartyRenderer',
                SmartyRenderer::class
            ],
            [
                'SmartyStrategy',
                SmartyStrategy::class
            ],
            [
                SmartyViewResolver::class,
                ResolverInterface::class
            ],
            [
                SmartyViewResolver::class,
                SmartyViewResolver::class
            ],
            [
                SmartyViewTemplateMapResolver::class,
                TemplateMapResolver::class
            ],
            [
                SmartyViewTemplateMapResolver::class,
                SmartyViewTemplateMapResolver::class
            ],
            [
                SmartyViewTemplatePathStack::class,
                TemplatePathStack::class
            ],
            [
                SmartyViewTemplatePathStack::class,
                SmartyViewTemplatePathStack::class
            ],
            [
                SmartyRenderer::class,
                SmartyRenderer::class
            ],
            [
                SmartyStrategy::class,
                SmartyStrategy::class
            ],
        ];
    }
}
