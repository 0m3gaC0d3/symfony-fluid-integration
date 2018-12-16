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

use Symfony\Component\HttpFoundation\Session\Session;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class FlashMessageViewHelper.
 *
 * Example usage:
 * <ul>
 *     <sf:flashMessage identifier="types">
 *         <f:for each="{types}" key="type" as="messages" >
 *             <f:for each="{messages}" as="message" >
 *                 <li>{type}: {message}</li>
 *             </f:for>
 *         </f:for>
 *     </sf:flashMessage>
 * </ul>
 */
class FlashMessageViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('identifier', 'string', 'Message container variable name.', true, 'types');
        $this->registerArgument('type', 'string', 'The type of the flash messages (empty means all).', false, null);
    }

    /**
     * Renders the view helper.
     */
    public function render()
    {
        $type = $this->arguments['type'] ?? null;
        $identifier = $this->arguments['identifier'] ?? null;
        $session = $this->renderingContext->getContainer()->get('request_stack')->getCurrentRequest()->getSession();
        $messages = is_null($type) ? $this->getAllMessages($session) : $this->getMessagesOfType($session, $type);
        $this->templateVariableContainer->add($identifier, $messages);

        return $this->renderChildren();
    }

    /**
     * @param Session $session
     *
     * @return array
     */
    protected function getAllMessages(Session $session): array
    {
        return $session->getFlashBag()->all() ?? [];
    }

    /**
     * @param Session $session
     * @param string  $type
     *
     * @return array
     */
    protected function getMessagesOfType(Session $session, string $type): array
    {
        return [$type => $session->getFlashBag()->get($type) ?? []];
    }
}
