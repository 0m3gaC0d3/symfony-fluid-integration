<?php

namespace OmegaCode\FluidIntegration;

use OmegaCode\FluidIntegration\Fluid\RenderingContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TYPO3Fluid\Fluid\View\TemplatePaths;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * Class TemplateViewFactory.
 */
class TemplateViewFactory
{
    /**
     * @param ContainerInterface $container
     * @param TemplatePaths      $templatePaths
     *
     * @return TemplateView
     */
    public function createTemplateView(ContainerInterface $container, $templatePaths)
    {
        $templateView = new TemplateView();
        $renderingContext = new RenderingContext($templateView);
        $renderingContext->setTemplatePaths($templatePaths);
        $renderingContext->setContainer($container);
        $templateView->setRenderingContext($renderingContext);

        return $templateView;
    }
}
