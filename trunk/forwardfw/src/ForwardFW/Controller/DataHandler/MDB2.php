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
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller/DataHandler
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.7
 */

namespace ForwardFW\Controller\DataHandler;

/**
 * Managing DataLoading via PEAR::MDB2
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller/DataHandler
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class MDB2 extends \ForwardFW\Controller\DataHandler
{
    /**
     * @var array Prefix for tables
     */
    private $arTablePrefix = array();

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFrom($strConnection, array $arOptions)
    {
        $conMDB2 = $this->getConnection($strConnection);

        $strQuery = 'SELECT ' . $arOptions['select'] . ' FROM ';
        if (isset($this->arTablePrefix[$strConnection])) {
            $strQuery .= '`' . $this->arTablePrefix[$strConnection]
                .'_' . $arOptions['from'] . '`';
        } else {
            $strQuery .= '`' . $arOptions['from'] . '`';
        }
        if (isset($arOptions['where'])) {
            $strQuery .= ' WHERE ' . $arOptions['where'];
        }
        if (isset($arOptions['group'])) {
            $strQuery .= ' GROUP BY ' . $arOptions['group'];
        }
        if (isset($arOptions['order'])) {
            $strQuery .= ' ORDER BY ' . $arOptions['order'];
        }
        if (isset($arOptions['limit'])) {
            $strQuery .= ' LIMIT ' . $arOptions['limit'];
        }

        $arResult = array();
        $resultMDB2 = $conMDB2->query($strQuery);

        if (\MDB2::isError($resultMDB2)) {
            $this->application->getResponse()->addError($resultMDB2->getMessage() . $resultMDB2->getUserinfo());
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
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function saveTo($strConnection, array $options)
    {
        $conMDB2 = $this->getConnection($strConnection);

        $strQuery = 'UPDATE ';
        if (isset($tablePrefix['default'])) {
            $strQuery .= $tablePrefix['default']
                . '_' . $options['to'];
        } else {
            $strQuery .= $options['to'];
        }
        $strQuery .= ' SET ';
        foreach ($options['values'] as $strName => $value) {
            $arSets[] = $strName . '=' . $this->getSqlValue($options['columns'][$strName], $value, $conMDB2);
        }
        $strQuery .= implode(',', $arSets);

        $strQuery .= ' WHERE ' . $options['where'];

        $arResult = array();
        $resultMDB2 = $conMDB2->query($strQuery);

        if (\MDB2::isError($resultMDB2)) {
            $this->application->getResponse()->addError($resultMDB2->getMessage() . $resultMDB2->getUserinfo());
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
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function create($strConnection, array $options)
    {
        $conMDB2 = $this->getConnection($strConnection);

        $strQuery = 'INSERT INTO ';
        if (isset($tablePrefix['default'])) {
            $strQuery .= $tablePrefix['default']
                . '_' . $options['to'];
        } else {
            $strQuery .= $options['to'];
        }

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
            $this->application->getResponse()->addError($resultMDB2->getMessage() . $resultMDB2->getUserinfo());
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
     * @param string $strConnection Name of connection
     *
     * @return void
     */
    public function initConnection($strConnection)
    {
        $arConfig = $this->getConfigParameter($strConnection);

        if (isset($arConfig['prefix'])) {
            $this->arTablePrefix[$strConnection] = $arConfig['prefix'];
        }
        $options = array('portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE);
        $options = array_merge($options, $arConfig['options']);
        $conMDB2 = \MDB2::connect($arConfig['dsn'], $options);

        if (\MDB2::isError($conMDB2)) {
            $this->application->getResponse()->addError(
                $conMDB2->getMessage() . $conMDB2->getUserinfo()
            );
            throw new \ForwardFW\Exception\DataHandler(
                'Cannot initialize MDB Connection: '
                . $conMDB2->getMessage()
                . $conMDB2->getUserinfo()
            );
        }

        $ret = $conMDB2->exec('set character set utf8');
        $this->arConnectionCache[$strConnection] = $conMDB2;
    }

    public function getSqlValue($strType, $value, $conMDB2)
    {
        return $conMDB2->quote($value, $strType, true);
    }
}
