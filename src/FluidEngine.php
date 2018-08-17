<?php
/**
 * Copyright 2018 OmegaCode.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace OmegaCode\FluidIntegration;

use OmegaCode\FluidIntegration\Configuration\Settings;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3Fluid\Fluid\Core\Cache\SimpleFileCache;

/**
 * Class FluidEngine.
 *
 * @TODO: Refactor this class and move methods: load, setRootPaths
 */
class FluidEngine implements EngineInterface
{
    /**
     * @var TemplateView
     */
    private $fluid;

    /**
     * @var TemplateNameParserInterface
     */
    protected $nameParser;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * FluidEngine constructor.
     *
     * @param \App\Kernel $kernel
     * @param Settings    $settings
     */
    public function __construct($kernel, Settings $settings)
    {
        $this->kernel = $kernel;
        $this->container = $kernel->getContainer();
        $this->settings = $settings;
        $this->fluid = $this->setRootPaths(new TemplateView());
        $this->nameParser = new TemplateNameParser();
        $this->setupCache();
    }

    /**
     * Renders a view and returns a Response.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A Response instance
     *
     * @return Response A Response instance
     *
     * @throws \Exception
     */
    public function renderResponse($view, array $parameters = [], Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->render($view, $parameters));

        return $response;
    }

    /**
     * Renders a template.
     *
     * @param string|TemplateReferenceInterface $name       A template name or a TemplateReferenceInterface instance
     * @param array                             $parameters An array of parameters to pass to the template
     *
     * @return string The evaluated template as a string
     *
     * @throws \RuntimeException if the template cannot be rendered
     * @throws \Exception
     */
    public function render($name, array $parameters = [])
    {
        $this->fluid->assignMultiple($parameters);
        $this->fluid->getTemplatePaths()->setTemplatePathAndFilename($this->load($name));

        return $this->fluid->render($name);
    }

    /**
     * Returns true if the template exists.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if the template exists, false otherwise
     *
     * @throws \Exception
     */
    public function exists($name)
    {
        return !empty($this->load($name));
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if this class supports the given template, false otherwise
     */
    public function supports($name)
    {
        $template = $this->nameParser->parse($name);
        $engine = $template->get('engine');

        return 'fluid' === $engine || 'html' === $engine;
    }

    /**
     * @param $name
     *
     * @return null|string
     *
     * @throws \Exception
     */
    protected function load($name)
    {
        $nameParts = explode('/', $name);
        if (2 != count($nameParts)) {
            throw new \InvalidArgumentException(
                "The given template name *$name*  is not inf format CONTROLLER/ACTION.html",
                1534278686
            );
        }
        $controller = $nameParts[0];
        $action = $nameParts[1];

        return $this->fluid->getTemplatePaths()->resolveTemplateFileForControllerAndActionAndFormat(
            $controller,
            $action
        );
    }

    /**
     * Sets and enables the caching of fluid, if the application is in production.
     */
    private function setupCache()
    {
        if (empty($this->settings->getCacheDir()) || 'prod' != $_SERVER['APP_ENV']) {
            return;
        }
        $cacheDirPath = $this->kernel->getRootDir().'/../'.$this->settings->getCacheDir();
        if (!is_dir($cacheDirPath)) {
            mkdir($cacheDirPath);
        }
        $this->fluid->setCache(new SimpleFileCache($cacheDirPath));
    }

    /**
     * @param TemplateView $templateView
     *
     * @return TemplateView
     */
    private function setRootPaths(TemplateView $templateView)
    {
        $templateView->getTemplatePaths()->setTemplateRootPaths(
            [$this->kernel->getRootDir().'/../'.$this->settings->getTemplatesRootPath()]
        );
        $templateView->getTemplatePaths()->setLayoutRootPaths(
            [$this->kernel->getRootDir().'/../'.$this->settings->getLayoutsRootPath()]
        );
        $templateView->getTemplatePaths()->setPartialRootPaths(
            [$this->kernel->getRootDir().'/../'.$this->settings->getPartialsRootPath()]
        );

        return $templateView;
    }
}
