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

namespace ForwardFW\Config\Filter\RequestResponse\View;

/**
 * Config for a Application Filter.
 */
class SimpleView extends \ForwardFW\Config\Filter\RequestResponse
{
    /**
     * @var string Class of application to call
     */
    protected $executionClassName = 'ForwardFW\\Filter\\RequestResponse\\View\\SimpleView';

    /**
     * @var string Name of file to output
     */
    protected $fileName = '';

    /**
     * Sets name of file to output.
     *
     * @param string $fileName Name of file
     * @return ForwardFW\Config\Filter\RequestResponse\View\SimpleView
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Get name of file to output.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
}
