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
 * @category   Object
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
 */

namespace ForwardFW\Object;


/**
 * A basic object/model inside ForwardFW, which can autoload its data fields.
 *
 * @category   Object
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Statefull extends \ForwardFW\Object
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
}
