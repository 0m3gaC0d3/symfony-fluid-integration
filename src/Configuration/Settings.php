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

declare(strict_types=1);

namespace OmegaCode\FluidIntegration\Configuration;

/**
 * Class PathConfiguration.
 */
class Settings
{
    /**
     * @var string
     */
    protected $cacheDir = '';

    /**
     * @var string
     */
    protected $templatesRootPath = '';

    /**
     * @var string
     */
    protected $layoutsRootPath = '';

    /**
     * @var string
     */
    protected $partialsRootPath = '';

    /**
     * Settings constructor.
     *
     * @param string $cacheDir
     * @param string $templatesRootPath
     * @param string $layoutsRootPath
     * @param string $partialsRootPath
     */
    public function __construct(
        string $cacheDir,
        string $templatesRootPath,
        string $layoutsRootPath,
        string $partialsRootPath
    ) {
        $this->cacheDir = $cacheDir ?? '';
        $this->templatesRootPath = $templatesRootPath ?? '';
        $this->layoutsRootPath = $layoutsRootPath ?? '';
        $this->partialsRootPath = $partialsRootPath ?? '';
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    /**
     * @return string
     */
    public function getTemplatesRootPath(): string
    {
        return $this->templatesRootPath;
    }

    /**
     * @return string
     */
    public function getPartialsRootPath(): string
    {
        return $this->partialsRootPath;
    }

    /**
     * @return string
     */
    public function getLayoutsRootPath(): string
    {
        return $this->layoutsRootPath;
    }
}
