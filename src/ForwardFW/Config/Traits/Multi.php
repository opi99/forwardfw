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

namespace ForwardFW\Config\Traits;

/**
 * Config for a Application Filter.
 */
trait Multi
{
    protected $arMultiChilds = array();

    protected $strMultiChildType = '';

    public function addMultiChild(\ForwardFW\Config $child)
    {
        if ($child instanceof $this->strMultiChildType) {
            array_push($this->arMultiChilds, $child);
        }
    }

    public function setMultiChilds($arChilds)
    {
        $this->arMultiChilds = $arChilds;
        return $this;
    }

    public function getMultiChilds()
    {
        return $this->arMultiChilds;
    }
}
