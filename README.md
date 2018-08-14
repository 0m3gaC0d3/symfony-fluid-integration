# Symfony 4 - Fluid integration

## ! This is currently work in progress. !

## Description
Integrates the template engine fluid into symfony 4 projects.

## Setup
Open the file ``config/services.yaml`` and add the following:
````yaml
services:
  templating.engine.fluid:
    public: true
    autowire: false
    class: OmegaCode\FluidIntegration\FluidEngine
    arguments:
    - '@kernel'
````
Next, open the file ``config/packages/framework.yaml`` and add the following:
````yaml
framework:
  templating:
    engines: ['fluid', 'twig', 'php']
````
Finally open the file ``config/bundles.php`` and add the bundle:
````php
<?php
return [
    ...
    OmegaCode\FluidIntegration\OmegaCodeFluidIntegrationBundle::class => ['all' => true],
    ...
];
```` 
You are now able to render fluid templates in your controller:
````php
class DemoController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function indexAction()
    {
        return $this->render('demo/index.html', [
            'var' => 'val'
        ]);
    }
}
```` 
Currently the root paths are declared as follows:
* templates: ``res/private/templates``
* partials: ``res/private/partials``
* layouts: ``res/private/layouts``

This will be configurable in later versions.