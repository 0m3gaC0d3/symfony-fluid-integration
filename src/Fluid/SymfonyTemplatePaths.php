<?php

namespace OmegaCode\FluidIntegration\Fluid;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;
use TYPO3Fluid\Fluid\View\TemplatePaths;

/**
 * Class SymfonyTemplatePaths.
 */
class SymfonyTemplatePaths extends TemplatePaths
{
    const DEFAULT_TEMPLATES_DIRECTORY = '/Resources/views/Templates/';
    const DEFAULT_LAYOUTS_DIRECTORY = '/Resources/views/Layouts/';
    const DEFAULT_PARTIALS_DIRECTORY = '/Resources/views/Partials/';

    /**
     * SymfonyTemplatePaths constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $bundle = $container->get('kernel')->getBundle('FluidBundle');
        $this->addBasePath($bundle->getPath().'/..');
        $this->addBasePath($container->getParameter('kernel.root_dir'));
    }

    /**
     * @param array $path
     *
     * @api
     */
    public function addBasePath($path)
    {
        $this->templateRootPaths[] = $path.self::DEFAULT_TEMPLATES_DIRECTORY;
        $this->layoutRootPaths[] = $path.self::DEFAULT_LAYOUTS_DIRECTORY;
        $this->partialRootPaths[] = $path.self::DEFAULT_PARTIALS_DIRECTORY;
    }

    /**
     * Tries to locate a Template file based on the provided path inside the templates directory.
     *
     * @param string $path
     * @param string $format
     *
     * @return string|null
     *
     * @api
     */
    public function resolveTemplateFile($path, $format = self::DEFAULT_FORMAT)
    {
        if (null !== $this->templatePathAndFilename) {
            return $this->templatePathAndFilename;
        }
        if (!array_key_exists($path, $this->resolvedFiles['templates'])) {
            $templateRootPaths = $this->getTemplateRootPaths();
            try {
                return $this->resolvedFiles['templates'][$path] = $this->resolveFileInPaths($templateRootPaths, $path,
                    $format);
            } catch (InvalidTemplateResourceException $error) {
                $this->resolvedFiles['templates'][$path] = null;
            }
        }
        $identifier = null; // ?

        return isset($this->resolvedFiles[self::NAME_TEMPLATES][$path]) ? $this->resolvedFiles[self::NAME_TEMPLATES][$identifier] : null;
    }
}
