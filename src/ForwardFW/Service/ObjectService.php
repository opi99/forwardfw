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

namespace ForwardFW\Service;

/**
 * This interface defines services which can be started.
 */
class ObjectService extends AbstractService implements ObjectServiceInterface, Startable
{
    /** @var \ForwardFW\Object[][] Holds the objects per className and id*/
    protected $objects = array();

    public function getObjects($className, $ids)
    {
        $objects = array();
        $missing = array();

        foreach ($ids as $id) {
            $object = $this->getObject($className, $id, false);
            if ($object === null) {
                $missing[] = $id;
            } else {
                $objects[] = $object;
            }
        }

        if (count($missing)) {
            // Load Container
            $classNameContainer = str_replace('\\Object\\', '\\Container\\', $className);
            $container = new $classNameContainer();
            $container->setServiceManager($this->getServiceManager());
            $container->loadByIds($missing);
            foreach ($container as $object) {
                $objects[] = $object;
                $this->setObject($object);
            }
        }

        return $objects;
    }

    public function getObject($className, $id, $autoload = true)
    {
        $object = null;
        if (isset($this->objects[$className][$id])) {
            $object = $this->objects[$className][$id];
        } elseif ($autoload) {
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

    public function start()
    {
    }

    public function stop()
    {
    }
}
