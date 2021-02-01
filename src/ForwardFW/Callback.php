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

namespace ForwardFW;

/**
 * Config for a Cache.
 */
class Callback
{
    /**
     * @var Callback The callback function configuration
     */
    public $callback = null;

    /**
     * @var array Array with the parameters for function call.
     */
    public $arParameters = null;

    /**
     * Constructor with validation of $callback
     *
     * @param Callback $callback     The callback function.
     * @param array    $arParameters Parameters for function call.
     *
     * @return void
     * @throws ForwardFW\Exception\Callback
     */
    public function __construct($callback, array $arParameters = null)
    {
        if (is_callable($callback, true)) {
            $this->callback = $callback;
            $this->setParameters($arParameters);
        } else {
            throw new \Exception\Callback('This is no possible callback function');
        }
    }

    /**
     * Sets the parameters array for callback.
     *
     * @param array $arParameters Parameters for function call.
     *
     * @return ForwrdFW\Callback
     */
    public function setParameters(array $arParameters = null)
    {
        $this->arParameters = $arParameters;

        return $this;
    }

    /**
     * Gets the parameters array for callback.
     *
     * @return array|null The parameters.
     */
    public function getParameters()
    {
        return $this->arParameters;
    }

    /**
     * Do the callback
     *
     * @return mixed Depends on function call.
     */
    public function doCallback()
    {
        if (is_null($this->arParameters)) {
            return call_user_func($this->callback);
        } else {
            return call_user_func_array($this->callback, $this->arParameters);
        }
    }
}
