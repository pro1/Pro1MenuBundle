<?php
/**
 * @author Dyllon Veitch <dyllon.v@pro1solutions.net>
 */

namespace Pro1\MenuBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class Pro1MenuExtension extends Extension
{
    /**
     * Loads the pro1_menu configuration.
     *
     * @param array $configs The configurations being loaded
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (!isset($config['menu'])) {
            throw new \InvalidArgumentException('A menu must be configured');
        }

        $container->setParameter('pro1_menu.menu.configuration', $config['menu']);
    }
}
