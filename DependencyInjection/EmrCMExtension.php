<?php

namespace Emr\CMBundle\DependencyInjection;

use Emr\CMBundle\Configuration\EntityConfig;
use Emr\CMBundle\EasyAdmin\EasyAdminConfiguration;
use Emr\CMBundle\EasyAdmin\EasyAdminEntityNaming;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @todo
 * Paket @annotation_reader servisine bağımlı halde olması gerekiyor.
 * Fakat servisler bu aşamada hazır olmadığından bağımlılık yapılamıyor.
 * Bu yüzden paket kendi içinde yeni bir annotation reader oluşturuyor.
 *
 * @link https://github.com/symfony/symfony/issues/27078
 */
class EmrCMExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $container->getExtensionConfig($this->getAlias()));

        $container->setParameter('emr_cm.config', $config);
        $container->setParameter('emr_cm.config_type', $config['type']);
        $container->setParameter('emr_cm.easy_admin_settings', $config['easy_admin_settings']);

        $this->load(
            $config,
            $container
        );

        if ($config['make_easy_admin_config'])
            $container->prependExtensionConfig('easy_admin', $this->makeEasyAdminConfig($config['easy_admin_settings'], $container));
    }

    private function makeEasyAdminConfig(array $settings, ContainerBuilder $container)
    {
        $configurator = new EasyAdminConfiguration($settings, $container->get('emr_cm.entity_config'), $container->get('emr_cm.easy_admin_entity_naming'));

        return $configurator->getFullConfiguration();
    }
}
