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
 * Interface for a Templater.
 */
interface TemplaterInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application
     */
    public function __construct(
        \ForwardFW\Config\Templater $config,
        \ForwardFW\Controller\ApplicationInterface $application
    );

    /**
     * Sets file to use for templating
     *
     * @param string $strFile Complete path and filename.
     */
    public function setTemplateFile(string $fileName): self;

    /**
     * Sets a var in the template to a value
     *
     * @param string $name Name of template var.
     * @param mixed $mValue Value of template var.
     */
    public function setVar(string $name, $mValue): self;

    /**
     * Returns compiled template for outputing.
     *
     * @return string Content of template after compiling.
     */
    public function getCompiled(): string;

    public function defineBlock($strBlockName);

    public function showBlock($strBlockName);

    public function hideBlock($strBlockName);

    /**
     * The filename ending of template files for this Templater
     */
    public function getTemplateFileEnding(): string;
}
