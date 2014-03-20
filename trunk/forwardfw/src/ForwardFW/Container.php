<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * PHP version 5
 *
 * @category   Container
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW;

/**
 * This is the basic Container class for ForwardFW Object
 *
 * @category   Container
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
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
     * @return ForwardFW_Object The created Base_Object
     */
    public function createNew()
    {
        return new $this->strObjectName();
    }

    /**
     * Creates a new Object of type $strObjectName, adds it to the list and
     * returns the object
     *
     * @return ForwardFW\Object
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
     * @param ForwardFW\Object $obj The object which should be add
     *
     * @return boolean if $obj could be add to the list
     */
    public function add(Object $obj)
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
     * @param ForwardFW\Object $obj The object that should be removed from list.
     *
     * @return boolean True if object could be removed otherwise false
     */
    public function remove(Object $obj)
    {
        $bWasRemoveable = false;
        if ($this->isUseable($obj)) {
            foreach ($this as $key => $value) {
                if ($value->ID == $obj->ID) {
                    unset($this[$key]);
                    $bWasRemoveable = true;
                }
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
     * @param ForwardFW\Object $obj Object to examine
     *
     * @return boolean True if given object can be managed by this list
     * otherwise false.
     * @TODO: Examine if it is a child of type
     */
    public function isUseable(Object $obj)
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
