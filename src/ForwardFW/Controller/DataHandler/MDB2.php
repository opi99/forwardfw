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

namespace ForwardFW\Controller\DataHandler;

/**
 * Managing DataLoading via PEAR::MDB2
 */
class MDB2 extends \ForwardFW\Controller\DataHandler
{
    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFrom($connectionName, array $options)
    {
        $conMDB2 = $this->getConnection($connectionName);

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

        $arResult = array();
        $resultMDB2 = $conMDB2->query($strQuery);

        if (\MDB2::isError($resultMDB2)) {
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: '
                . $resultMDB2->getMessage()
                . $resultMDB2->getUserinfo()
            );
        }
        while ($arRow = $resultMDB2->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            array_push($arResult, $arRow);
        }
        return $arResult;
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function saveTo($connectionName, array $options)
    {
        $conMDB2 = $this->getConnection($connectionName);

        $strQuery = 'UPDATE ' . $this->getTableName($options['to'], $connectionName);

        $strQuery .= ' SET ';
        foreach ($options['values'] as $strName => $value) {
            $arSets[] = $strName . '=' . $this->getSqlValue($options['columns'][$strName], $value, $conMDB2);
        }
        $strQuery .= implode(',', $arSets);

        $strQuery .= ' WHERE ' . $options['where'];

        $arResult = array();
        $resultMDB2 = $conMDB2->query($strQuery);

        if (\MDB2::isError($resultMDB2)) {
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: '
                . $resultMDB2->getMessage()
                . $resultMDB2->getUserinfo()
            );
        }
        while ($arRow = $resultMDB2->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            array_push($arResult, $arRow);
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
     * @return mixed Data from the connection.
     */
    public function create($connectionName, array $options, \ForwardFW\Callback $idCallback = null)
    {
        $conMDB2 = $this->getConnection($connectionName);

        $strQuery = 'INSERT INTO ' . $this->getTableName($options['to'], $connectionName);

        $strQuery .= ' (' . implode(',', array_keys($options['values'])) . ')';
        $strQuery .= ' VALUES (';
        $arValues = array();
        foreach ($options['values'] as $strName => $value) {
            $arValues[] = $this->getSqlValue($options['columns'][$strName], $value, $conMDB2);
        }
        $strQuery .= implode(',', $arValues) . ')';

        $arResult = array();
        $resultMDB2 = $conMDB2->query($strQuery);

        if (\MDB2::isError($resultMDB2)) {
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: '
                . $resultMDB2->getMessage()
                . $resultMDB2->getUserinfo()
            );
        }
        while ($arRow = $resultMDB2->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            array_push($arResult, $arRow);
        }
        return $arResult;
    }

    /**
     * Truncates Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function truncate($connectionName, array $options)
    {
        $conMDB2 = $this->getConnection($connectionName);

        $strQuery = 'truncate ' . $this->getTableName($options['table'], $connectionName);

        $arResult = array();
        $resultMDB2 = $conMDB2->query($strQuery);

        if (\MDB2::isError($resultMDB2)) {
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: '
                . $resultMDB2->getMessage()
                . $resultMDB2->getUserinfo()
            );
        }
        while ($arRow = $resultMDB2->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            array_push($arResult, $arRow);
        }
        return $arResult;
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
        $options = array('portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE);
//         $options = array_merge($options, $arConfig['options']);
        $conMDB2 = \MDB2::connect($this->config->getDsn(), $options);

        if (\MDB2::isError($conMDB2)) {
            throw new \ForwardFW\Exception\DataHandler(
                'Cannot initialize MDB Connection: '
                . $conMDB2->getMessage()
                . $conMDB2->getUserinfo()
            );
        }

        $ret = $conMDB2->exec('set character set utf8');
        $this->arConnectionCache[$connectionName] = $conMDB2;
    }

    public function getSqlValue($strType, $value, $conMDB2)
    {
        return $conMDB2->quote($value, $strType, true);
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
