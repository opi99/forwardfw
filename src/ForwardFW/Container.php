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
 * This is the basic Container class for ForwardFW Object
 */
class Container extends \ArrayObject
{
    /**
    * Name of the object this list will manage
    *
    * @var string
    */
    protected $strObjectName = '';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->strObjectName
            = preg_replace('/\\\\Container\\\\/', '\\Object\\', get_class($this));
    }

    /**
     * Creates a new object of type $strObjectName which won't be added to list
     * and returns this object.
     *
     * @return ForwardFW\ObjectAbstract The created object
     */
    public function createNew()
    {
        return new $this->strObjectName();
    }

    /**
     * Creates a new Object of type $strObjectName, adds it to the list and
     * returns the object
     *
     * @return ForwardFW\ObjectAbstract The created object
     */
    public function createNewToList()
    {
        $obj = $this->createNew();
        $this->append($obj);
        return $obj;
    }

    /**
     * Adds object to the list
     *
     * @param ForwardFW\ObjectAbstract $obj The object which should be add
     *
     * @return boolean if $obj could be add to the list
     */
    public function add(ObjectAbstract $obj)
    {
        $bIsUseable = false;
        if ($this->isUseable($obj)) {
            $this->append($obj);
            $bIsUseable = true;
        }
        return $bIsUseable;
    }

    /**
     * Removes given Object from List and returns state if it was in list.
     *
     * @param ForwardFW\ObjectAbstract $obj The object that should be removed from list.
     *
     * @return boolean True if object could be removed otherwise false
     * @TODO Only possible for statefull objects
     */
    public function remove(ObjectAbstract $obj)
    {
        $bWasRemoveable = false;
        if ($this->isUseable($obj)) {
            foreach ($this as $key => $value) {
                if ($value->getId() == $obj->ID) {
                    unset($this[$key]);
                    $bWasRemoveable = true;
                }
            }
        }
        return $bWasRemoveable;
    }

    /**
     * Removes given Object from List and returns state if it was in list.
     *
     * @param mixed $id Id of the object to remove
     *
     * @return boolean True if object could be removed otherwise false
     * @TODO Only possible for statefull objects
     */
    public function removeById($id)
    {
        $bWasRemoveable = false;
        foreach ($this as $key => $value) {
            if ($value->getId() == $id) {
                unset($this[$key]);
                $bWasRemoveable = true;
            }
        }

        return $bWasRemoveable;
    }
    /**
     * Creates a new object of the List type, loads it with the array information
     * and add it to the list.
     *
     * @param array $arObject An Array which have fill up Data for the object
     *
     * @return void
     */
    public function addObjectByArray($arObject)
    {
        $obj = $this->createNew();
        $obj->loadByArray($arObject);
        $this->append($obj);
    }

    /**
     * Loads an array to this list. The array needs to hold arrays with the data
     * of the object.
     *
     * @param array $arData the array with the objects for this list.
     *
     * @return void
     */
    public function loadByArray($arData)
    {
        if (is_array($arData)) {
            foreach ($arData as $arObject) {
                $this->addObjectByArray($arObject);
            }
        }
    }

    /**
     * Examines if the given object is from typet this list will hold.
     *
     * @param ForwardFW\ObjectAbstract $obj Object to examine
     *
     * @return boolean True if given object can be managed by this list
     * otherwise false.
     * @TODO: Examine if it is a child of type
     */
    public function isUseable(ObjectAbstract $obj)
    {
        if ($this->strObjectName === get_class($obj)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sorts the array
     *
     * @return void
     */
    public function sort()
    {
        $this->uasort('strcmp');
    }

    /**
     * Returns the Name of the objects this list will manage
     *
     * @return string Name of the objects
     */
    public function getObjectName()
    {
        return $this->strObjectName;
    }
}
