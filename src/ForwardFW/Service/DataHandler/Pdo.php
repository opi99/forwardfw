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

namespace ForwardFW\Service\DataHandler;

/**
 * Managing DataLoading via PHPs PDO
 */
class Pdo extends \ForwardFW\Controller\DataHandler
{
    private \Pdo $connection;

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return array Data from the connection.
     */
    public function loadFrom($connectionName, array $options)
    {
        $connection = $this->getConnection($connectionName);

        $strQuery = 'SELECT ' . $options['select'] . ' FROM ' . $this->getTableName($options['from'], $connectionName);
        if (isset($options['where'])) {
            $strQuery .= ' WHERE ' . $options['where'];
        }
        if (isset($options['group'])) {
            $strQuery .= ' GROUP BY ' . $options['group'];
        }
        if (isset($options['order'])) {
            $strQuery .= ' ORDER BY ' . $options['order'];
        }
        if (isset($options['limit'])) {
            $strQuery .= ' LIMIT ' . $options['limit'];
        }

        $result = $connection->query($strQuery);

        if ($result === false) {
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return array Empty array
     */
    public function saveTo($connectionName, array $options)
    {
        $connection = $this->getConnection($connectionName);

        $strQuery = 'UPDATE ' . $this->getTableName($options['to'], $connectionName);

        $strQuery .= ' SET ';
        foreach ($options['values'] as $strName => $value) {
            $arSets[] = $strName . '=' . $this->getSqlValue($options['columns'][$strName], $value, $connection);
        }
        $strQuery .= implode(',', $arSets);

        $strQuery .= ' WHERE ' . $options['where'];

        $arResult = array();
        $result = $connection->exec($strQuery);

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        return $arResult;
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     * @param ForwardFW\Callback $idCallback Callback to give id of object creation
     *
     * @return array Empty array
     */
    public function create($connectionName, array $options, \ForwardFW\Callback $idCallback = null)
    {
        $connection = $this->getConnection($connectionName);

        $strQuery = 'INSERT INTO ' . $this->getTableName($options['to'], $connectionName);

        $strQuery .= ' (' . implode(',', array_keys($options['values'])) . ')';
        $strQuery .= ' VALUES (';
        $arValues = array();
        foreach ($options['values'] as $strName => $value) {
            $arValues[] = $this->getSqlValue($options['columns'][$strName], $value, $connection);
        }
        $strQuery .= implode(',', $arValues) . ')';

        $arResult = array();
        $result = $connection->exec($strQuery);

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }

        // @TODO Callback with new ID

        return $arResult;
    }

    /**
     * Truncates Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return array Empty array
     */
    public function truncate($connectionName, array $options)
    {
        $connection = $this->getConnection($connectionName);

        $table = $this->getTableName($options['table'], $connectionName);

        // Delete content of table
        $result = $connection->exec('DELETE FROM ' . $table);

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }

        // Reset auto_inc counter
        $result = $connection->exec('DELETE FROM SQLITE_SEQUENCE WHERE name = \'' . $table . '\'');

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        return [];
    }

    /**
     * Loads and initialize the connection handler.
     *
     * @param string $connectionName Name of connection
     *
     * @return void
     */
    public function initConnection($connectionName)
    {
        try {
            $connection = new \PDO($this->config->getDsn(), $this->config->getUsername(), $this->config->getPassword());
        } catch (\Exception $e) {
            throw new \ForwardFW\Exception\DataHandler(
                'Cannot initialize PDO Connection: '
                . $e->getMessage()
            );
        }

        $ret = $connection->exec('SET NAMES utf8; SET CHARACTER SET utf8');
        $this->connectionCache[$connectionName] = $connection;
    }

    public function getSqlValue($strType, $value, $connection)
    {
        if ($strType === 'integer') {
            return (int) $value;
        }
        return '\'' . $connection->escapeString($value) . '\'';
    }

    /**
     * Returns real table name Prefix or DB dependent changes.
     *
     * @param string $tableName Name of table inside application
     * @param string $connectionName Name of connection
     *
     * @return string Name of table inside DB
     */
    protected function getTableName($tableName, $connectionName)
    {
        $tablePrefix = $this->config->getTablePrefix();

        if ($tablePrefix !== '') {
            return $tablePrefix . '_' . $tableName;
        } else {
            return $tableName;
        }
    }
}
