<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Wolf Utz <utz@riconet.de>, riconet
 *      Created on: 17.10.18 19:18
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace OmegaCode\FluidIntegration\ViewHelpers\Form;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

/**
 * Class CsfrTokenViewHelper.
 */
class CsfrTokenViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('type', 'string', 'The type of the token.', true);
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        /** @var CsrfTokenManager $tokenProvider */
        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken($this->arguments['type'])->getValue();

        return (string) $token;
    }
}
