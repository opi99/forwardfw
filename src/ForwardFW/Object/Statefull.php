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

namespace ForwardFW\Object;

/**
 * A basic object/model inside ForwardFW, which can autoload its data fields.
 */
class Statefull extends \ForwardFW\ObjectAbstract
{
    /**
     * @var mixed Content of id
     */
    protected $id = 0;

    /**
     * @var string Name of the field in data array, which holds the ID
     */
    protected $strIdFieldName = 'id';

    /**
     * @var \ForwardFW\Repository Repository this data will be saved.
     */
    protected $repository = null;

    /**
     * Constructur
     *
     * @param \ForwardFW\Repository $repository The repository this object belongs to.
     * @param string $strIdFieldName Name of the ID field in data
     */
    public function __construct($repository, $strIdFieldName = 'id')
    {
        $this->repository = $repository;
        $this->strIdFieldName = $strIdFieldName;
    }

    /**
     * Loads the model data out of an array as data set
     *
     * @param array &$arRow The array with data to read out
     *
     * @return void
     */
    public function loadByArray($arRow)
    {
        $this->id = $arRow[$this->strIdFieldName];
    }

    /**
     * Saves the model data into an array as data set
     *
     * @param array &$arRow The array into which the data will be written
     *
     * @return void
     */
    public function saveToArray(&$arRow)
    {
        $arRow[$this->strIdFieldName] = $this->id;
    }

    /**
     * Returns the id of model.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets id of model.
     *
     * @param mixed $id Id of model.
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Clones the statfull object and resets the id, so it can be saved as new one.
     */
    public function __clone()
    {
        $this->id = 0;
    }
}
