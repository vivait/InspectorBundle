<?php

namespace Vivait\InspectorBundle\DependencyInjection;

use PhpSpec\Util\Filesystem;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Vivait\InspectorBundle\Metadata\Driver\Loader\AnnotationLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class VivaitInspectorExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('vivait_inspector.event_locations', $config['event_locations']);

//        // probably make this configurable...
//        $cacheDirectory = '%kernel.cache_dir%/vivaitinspector_event_annotation';
//        $cacheDirectory = $container->getParameterBag()->resolveValue($cacheDirectory);
//        if (!is_dir($cacheDirectory)) {
//            mkdir($cacheDirectory, 0777, true);
//        }

        // Invalidate the config cache now the config might have changed
        $configcache = $container->getDefinition('vivait_inspector.event.registry.configcache');
        $cacheFile = $container->getParameterBag()->resolveValue($configcache->getArgument(0));

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

}
