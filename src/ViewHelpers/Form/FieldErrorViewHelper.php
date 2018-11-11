<?php

namespace OmegaCode\FluidIntegration\ViewHelpers\Form;

use Symfony\Component\Form\FormInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class FieldErrorViewHelper.
 */
class FieldErrorViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('form', FormInterface::class, 'The form.', true);
        $this->registerArgument('property', 'string', 'The property to look for errors.', true);
    }

    /**
     * @return string
     */
    public function render()
    {
        /** @var \Symfony\Component\Form\FormErrorIterator $errors */
        $iterator = $this->arguments['form']->getErrors(true);
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($iterator as $error) {
            /** @var \Symfony\Component\Validator\ConstraintViolation $cause */
            $cause = $error->getCause();
            preg_match_all("/children\[?(.*)\]|data\.?(.*)/", $cause->getPropertyPath(), $matches, PREG_PATTERN_ORDER);
            $property = empty($matches[1][0]) ? $matches[2][0] : $matches[1][0];
            if ($property === $this->arguments['property']) {
                return "<p class='error'>".$error->getMessage()."</p>";
            }
        }
    }
}
