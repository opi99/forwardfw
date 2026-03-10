<?php

declare(strict_types=1);

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

namespace ForwardFW\DataHandling;

use ForwardFW\Service\AbstractService;

/**
 * Helper functions to work with entities
 */
class EntityHelper
{
    public static function getterForProperty(object $entity, string $fieldName)
    {
        $methodPart = str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));
        $accessors = [
            'get',
            'has',
            'is',
        ];

        foreach ($accessors as $accessor) {
            $method = $accessor . $methodPart;
            if (!method_exists($entity, $method)) {
                continue;
            }

            if (!is_callable([$entity, $method])) {
                continue;
            }

            return $method;
        }

        throw new \Exception('No getter for field: ' . $fieldName);
    }

    public static function setterForProperty(object $entity, string $fieldName)
    {
        $methodPart = str_replace(' ', '', ucwords(str_replace('_', ' ', $fieldName)));
        $accessors = [
            'set',
        ];

        foreach ($accessors as $accessor) {
            $method = $accessor . $methodPart;
            if (!method_exists($entity, $method)) {
                continue;
            }

            if (!is_callable([$entity, $method])) {
                continue;
            }

            return $method;
        }

        throw new \Exception('No setter for field: "' . $fieldName . '" in object "' . get_class($entity) . '"');
    }
}
