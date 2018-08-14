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