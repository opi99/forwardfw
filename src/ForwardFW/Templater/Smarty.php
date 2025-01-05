<?php

declare(strict_types=1);

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
 * Class to use Smarty as Templater.
 */
class Smarty extends \ForwardFW\Controller implements \ForwardFW\Templater\TemplaterInterface
{
    /** @var \Smarty\Smarty The smarty instance */
    private \Smarty\Smarty $smarty;

    /** @var array Blocks to show */
    private array $arShowBlocks = [];

    /** @var string Name of the template file */
    private string $fileName;

    private \ForwardFW\Config\Templater $config;

    /**
     * @param ForwardFW\Config\Templater $config The smarty configuration
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     */
    public function __construct(
        \ForwardFW\Config\Templater $config,
        \ForwardFW\Controller\ApplicationInterface $application
    ) {
        parent::__construct($application);

        $this->config = $config;

        $compilePath = $config->getCompilePath();

        if (!is_dir($compilePath)) {
            if (!@mkdir($compilePath, 0770, true)) {
                $error = error_get_last();
                throw new \Exception($error['message'] . "\n" . 'Path: ' . $compilePath);
            }
        }

        $this->smarty = new \Smarty\Smarty();
        $this->smarty->setCompileDir($compilePath);
        $this->smarty->registerPlugin('block', 'block', array(&$this, 'smartyBlock'));
        $this->smarty->registerPlugin('function', 'texter', array(&$this, 'smartyTexter'));
    }

    /**
     * Sets file to use for templating
     */
    public function setTemplateFile(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Sets a var in the template to a value
     *
     * @param string $name Name of template var.
     * @param mixed $mValue Value of template var.
     */
    public function setVar(string $name, $mValue): self
    {
        $this->smarty->assign($name, $mValue);
        return $this;
    }

    /**
     * Returns compiled template for outputing.
     *
     * @return string Content of template after compiling.
     */
    public function getCompiled(): string
    {
        // Catch Exceptions and clear output cache
        try {
            $result = $this->smarty->fetch(
                $this->config->getTemplatePath() . '/' . $this->fileName
            );
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
        return $result;
    }

    /**
     * Defines blocks in template.
     * Deprecated or should be used for template engines without conditions?
     */
    public function defineBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    /**
     * Shows block in template.
     * Deprecated or should be used for template engines without conditions?
     */
    public function showBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 1;
    }

    /**
     * Hide block in template.
     * Deprecated or should be used for template engines without conditions?
     */
    public function hideBlock($strBlockName)
    {
        $this->arShowBlocks[$strBlockName] = 0;
    }

    /**
     * Block implementation for smarty.
     * Deprecated or should be used for template engines without conditions?
     */
    public function smartyBlock($params, $content, &$smarty, &$repeat)
    {
        $name = $params['name'];
        if (isset($this->arShowBlocks[$name]) && $this->arShowBlocks[$name] == 1) {
            return $content;
        }
        return '';
    }

    /**
     * Texter implementation for smarty.
     * More later, if it exists.
     */
    public function smartyTexter($params, &$smarty)
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
        return 'tpl';
    }
}
