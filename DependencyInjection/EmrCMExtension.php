<?php

namespace Emr\CMBundle\DependencyInjection;

use Emr\CMBundle\Configuration\EntityConfig;
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

        $container->setParameter('emr_cm.config_type', $config['type']);

        $this->load(
            $config,
            $container
        );

        if ($config['make_easy_admin_config'])
            $container->prependExtensionConfig('easy_admin', $this->makeEasyAdminConfig($container->get('emr_cm.entity_config')));
    }

    private function makeEasyAdminConfig(EntityConfig $config)
    {
        return [
            'entities' => [
                'PageAdmin' => [
//                    'role_require' => 'ROLE_SUPER_ADMIN',
                    'class' => $config->getPageClass()
                ]
            ]
        ];
    }
}
