<?php

namespace Ken\View\PlatesEngine;

use Ken\View\BaseEngine;
use League\Plates\Engine;

/**
 * @author Juliardi <ardi93@gmail.com>
 */
class PlatesEngine extends BaseEngine
{
    /**
     * @var \League\Plates\Engine
     */
    protected $engine;

    /**
     * @var \Ken\TwigEngine\FunctionGenerator
     */
    protected $functionGenerator;

    public function __construct($config)
    {
        parent::__construct($config);
        if (!isset($config['functionGenerator'])) {
            $config['functionGenerator'] = __NAMESPACE__.'\FunctionGenerator';
        }
        $this->initFunctionGenerator($config['functionGenerator']);
        $this->initEngine();
    }

    /**
     * Inits custom function generator.
     */
    protected function initFunctionGenerator($generatorClass)
    {
        $this->functionGenerator = $generatorClass::build();
    }

    /**
     * {@inheritdoc}
     */
    protected function initEngine()
    {
        $this->engine = new Engine($this->viewPath, $this->getFileExtension());

        $this->registerCustomFunctions();
    }

    /**
     * Registers custom functions.
     */
    protected function registerCustomFunctions()
    {
        $functionList = $this->functionGenerator->getFunctionList();

        foreach ($functionList as $function) {
            if (isset($function['name']) && isset($function['callable'])) {
                $this->engine->registerFunction($function['name'], $function['callable']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render($view, array $params = [])
    {
        $view = $this->suffixExtension($view);

        echo $this->engine->render($view, $params);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFileExtension()
    {
        return 'php';
    }
}
