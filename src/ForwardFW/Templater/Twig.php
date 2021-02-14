<?php

/**
 * This file is part of ForwardFW a web application framework.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace ForwardFW\Templater;

/**
 * Class to use Twig as Templater.
 */
class Twig extends \ForwardFW\Controller implements \ForwardFW\Templater\TemplaterInterface
{
    /**
     * @var \Twig\Environment The Twig instance
     */
    private $twigEnvironment = null;

    /**
     * @var \Twig\Template The loaded template
     */
    private $twigTemplate = null;

    /**
     * @var array The array of vars for the template
     */
    private $templateVars = [];

    /**
     * @var array Blocks to show
     */
    private $arShowBlocks = array();

    /**
     * Constructor
     *
     * @param ForwardFW\Config\Templater $config The twig configuration
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     */
    public function __construct(
        \ForwardFW\Config\Templater $config,
        \ForwardFW\Controller\ApplicationInterface $application
    ) {
        parent::__construct($application);

        $compilePath = $config->getCompilePath();

        if (!is_dir($compilePath)) {
            if (!@mkdir($compilePath, 0770, true)) {
                $error = error_get_last();
                throw new \Exception($error['message'] . "\n" . 'Path: ' . $compilePath);
            }
        }

        $twigLoader = new \Twig\Loader\FilesystemLoader($config->getTemplatePath());
        $this->twigEnvironment = new \Twig\Environment(
            $twigLoader,
            [
                'cache'      => $compilePath,
                'debug'      => true,
                'autoescape' => false,
            ]
        );
        $this->twigEnvironment->addExtension(new \Twig\Extension\DebugExtension());
    }

    /**
     * Sets file to use for templating
     *
     * @param string $fileName Complete path and filename.
     */
    public function setTemplateFile($fileName): self
    {
        $this->twigTemplate = $this->twigEnvironment->load($fileName);
        return $this;
    }

    /**
     * Sets a var in the template to a value
     *
     * @param string $varName Name of template var.
     * @param mixed  $value  Value of template var.
     */
    public function setVar($varName, $value): self
    {
        $this->templateVars[$varName] = $value;
        return $this;
    }

    /**
     * Returns compiled template for outputing.
     */
    public function getCompiled(): string
    {
        $result = $this->twigTemplate->render(
            $this->templateVars
        );
        return $result;
    }

    public function getTwigEnvironment(): \Twig\Environment
    {
        return $this->twigEnvironment;
    }

    /**
     * @TODO Not combined with Twig
     */
    public function defineBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    public function showBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 1;
    }

    public function hideBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    public function twigBlock($params, $content, &$smarty, &$repeat)
    {
        $name = $params['name'];
        if (isset($this->arShowBlocks[$name]) && $this->arShowBlocks[$name] == 1) {
            return $content;
        }
        return '';
    }

    public function twigTexter($params, &$smarty)
    {
        $strTextKey = $params['key'];

        $texter = ForwardFW_Texter::factory($this->strApplicationName);
        $result = $texter->getText($strTextKey);

        return $result;
    }

    /**
     * The filename ending of template files for this Templater
     */
    public function getTemplateFileEnding(): string
    {
        return 'twig';
    }
}
