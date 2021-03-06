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
 * Class to use Smarty as Templater.
 */
class Smarty extends \ForwardFW\Controller implements \ForwardFW\Templater\TemplaterInterface
{
    /**
     * @var Smarty The smarty instance
     */
    private $smarty = null;

    /**
     * @var array Blocks to show
     */
    private $arShowBlocks = array();

    /**
     * Constructor
     *
     * @param ForwardFW\Config\Templater $config The smarty configuration
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     *
     * @return void
     */
    public function __construct(
        \ForwardFW\Config\Templater $config,
        \ForwardFW\Controller\ApplicationInterface $application
    ) {
        parent::__construct($application);

        $strCompilePath = $config->getCompilePath();

        if (!is_dir($strCompilePath)) {
            if (!@mkdir($strCompilePath, 0770, true)) {
                $error = error_get_last();
                throw new \Exception($error['message'] . "\n" . 'Path: ' . $strCompilePath);
            }
        }

        $this->smarty = new \Smarty();
        $this->smarty->setCompileDir($strCompilePath);
        $this->smarty->registerPlugin('block', 'block', array(&$this, 'smartyBlock'));
        $this->smarty->registerPlugin('function', 'texter', array(&$this, 'smartyTexter'));

        $this->strTemplatePath = $config->getTemplatePath();
    }

    /**
     * Sets file to use for templating
     *
     * @param string $strFile Complete path and filename.
     *
     * @return ForwardFW_Templater_Smarty The instance.
     */
    public function setTemplateFile($strFile)
    {
        $this->strFile = $strFile;
        return $this;
    }

    /**
     * Sets a var in the template to a value
     *
     * @param string $strName Name of template var.
     * @param mixed  $mValue  Value of template var.
     *
     * @return ForwardFW_Templater_Smarty The instance.
     */
    public function setVar($strName, $mValue)
    {
        $this->smarty->assign($strName, $mValue);
        return $this;
    }

    /**
     * Returns compiled template for outputing.
     *
     * @return string Content of template after compiling.
     */
    public function getCompiled()
    {
        // Catch Exceptions and clear output cache
        try {
            $result = $this->smarty->fetch(
                $this->strTemplatePath . '/' . $this->strFile
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
