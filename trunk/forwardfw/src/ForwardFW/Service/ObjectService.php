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
 * @category   ServiceManager
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
 */

namespace ForwardFW\Service;

/**
 * This interface defines services which can be started.
 *
 * @category   ServiceManager
 * @package    ForwardFW
 * @subpackage Main
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ObjectService extends AbstractService implements ObjectServiceInterface, Startable
{
    /** @var \ForwardFW\Object[][] Holds the objects per className and id*/
    protected $objects = array();

    public function getObject($className, $id)
    {
        if (isset($this->objects[$className][$id])) {
            $object = $this->objects[$className][$id];
        } else {
            $object = $this->instanciateObject($className, $id);
            $this->setObject($object);
        }

        return $object;
    }

    public function instanciateObject($className, $id)
    {
        $object = new $className();
        $object->setServiceManager($this->getServiceManager());
        if ($object->loadById($id)) {
            return $object;
        }

        return null;
    }

    public function setObject($object)
    {
        $className = get_class($object);
        if (!isset($this->objects[$className])) {
            $this->objects[$className] = array();
        }
        $this->objects[$className][$object->getId()] = $object;
    }

    public function start() {}

    public function stop() {}
}