<?php

namespace App\Shared;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function buildDoctrineConfig(ContainerBuilder $container): void
    {
        $entityDirsFinder = (new Finder())
            ->in($this->getProjectDir().'/src')
            ->depth(1)
            ->name('Entity')
            ->directories()
        ;

        $mappings = [];
        foreach ($entityDirsFinder as $entityDir) {
            $path = $entityDir->getPath();
            $key = basename($path);
            $dir = '%kernel.project_dir%/src/'.$entityDir->getRelativePathname();

            $mappings[$key] = [
                'type' => 'attribute',
                'dir' => $dir,
                'prefix' => "App\\$key",
            ];
        }

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => $mappings,
            ],
        ]);
    }

    private function buildApiPlatformConfig(ContainerBuilder $container): void
    {
        $apiResourceDirsFinder = (new Finder())
            ->in($this->getProjectDir().'/src')
            ->depth(1)
            ->name('ApiResource')
            ->directories()
        ;

        $paths = [];
        foreach ($apiResourceDirsFinder as $entityDir) {
            $path = $entityDir->getPath();
            $dir = '%kernel.project_dir%/src/'.$entityDir->getRelativePathname();

            $paths[] = $dir;
        }

        $container->prependExtensionConfig('api_platform', [
            'mapping' => [
                'paths' => $paths,
            ],
        ]);
    }

    protected function build(ContainerBuilder $container): void
    {
        $this->buildDoctrineConfig($container);
        if ($container->hasExtension('api_platform')) {
            $this->buildApiPlatformConfig($container);
        }
    }
}
