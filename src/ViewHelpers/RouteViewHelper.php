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

namespace OmegaCode\FluidIntegration\ViewHelpers;

use Symfony\Component\Routing\Generator\UrlGenerator;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Class RouteViewHelper.
 * @TODO: Add possibility to add GET params. (additionalParameters)
 */
class RouteViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('name', 'string', 'The route name whose route should be generated', true);
        $this->registerArgument('arguments', 'array', 'The route arguments', false, []);
        $this->registerTagAttribute('class', 'string', 'tag classes');
        $this->registerArgument('anchor', 'string', 'The anchor to be added to the URI.', false);
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->renderingContext->getContainer()->get('router');
        $url = $urlGenerator->generate(
            $this->arguments['name'],
            $this->arguments['arguments']
        );
        $url = (!empty($this->arguments['anchor'])) ? $url.'#'.$this->arguments['anchor'] : $url;
        $this->tag->setContent($this->renderChildren());
        $this->tag->addAttribute('href', $url);

        return $this->tag->render();
    }
}
