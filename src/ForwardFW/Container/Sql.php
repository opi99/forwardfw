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

namespace ForwardFW\Container;

/**
 * A list of models that can load themself from DB.
 */
class Sql extends \ForwardFW\Container
{
    /**
     * Name of the table, which is mostly a prefix and the object name
     * in lowercase.
     *
     * @var string
     */
    protected $strTableName = '';

    /**
     * Prefix for the table name
     *
     * @access private
     * @var string
     */
    protected $strTablePrefix = '';

    /**
     * Name of the connection which should be used for this object to load/save.
     *
     * @access private
     * @var string
     */
    protected $strDBConnection = '';

    /**
     * The application this container is running in.
     *
     * @access private
     * @var \ForwardFW\Controller\ApplicationInterface
     */
    protected $application = null;

    /**
     * constructor
     *
     * @param string $strTablePrefix  Prefix der Tabellennamen im Projekt.
     * @param string $strDBConnection Name of the DB connection to use.
     *
     * @return new instance
     */
    public function __construct($strTablePrefix = '', $strDBConnection = 'default')
    {
        parent::__construct();

        $this->strTablePrefix  = $strTablePrefix;
        $this->strDBConnection = $strDBConnection;

        $this->strTableName = \ForwardFW\Object\Statefull\Sql::resolveTableName(
            $this->strTablePrefix,
            $this->strObjectName
        );
    }

    public function setServiceManager(\ForwardFW\ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Creates a new object of type and returns this object.
     *
     * @return ForwardFW\Object
     */
    public function createNew()
    {
        $obj = parent::createNew();
        $obj->setServiceManager($this->getServiceManager());
        return $obj;
    }

    /**
     * Loads all data from given table.
     *
     * @param boolean $bHidden If true then also return the hidden objects
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadAll($bHidden = false)
    {
        return $this->loadByWhereClause(
            $this->buildWhereClause($bHidden)
        );
    }

    /**
     * Returns count of all objects in DB
     *
     * @param string  $strWhereClause Where clause for the select.
     * @param boolean $bHidden        If true then also count the hidden objects
     *
     * @return integer
     */
    public function countAll($bHidden = false)
    {
        return $this->countByWhereClause(
            $this->buildWhereClause($bHidden)
        );
    }

    /**
     * Returns where clouse depending with deleted and hidden fields.
     *
     * @param boolean $bHidden If true then also count the hidden objects
     *
     * @return string
     */
    public function buildWhereClause($bHidden, array $arFields = null)
    {
        $dataHandler = $this->serviceManager->getService('ForwardFW\\Controller\\DataHandlerInterface');

        if (null !== $arFields) {
            foreach ($arFields as $strField => $value) {
                if ($value !== null) {
                    if (is_string($value)) {
                        $arWhere[] = $strField . '=' . $dataHandler->quoteString($this->strDBConnection, $value);
                    } elseif (is_integer($value) || is_float($value)) {
                        $arWhere[] = $strField . '=' . $value;
                    } else {
                        throw new \Exception('Invalid Value');
                    }
                }
            }
        }

        if (!$bHidden) {
            $arWhere[] = 'hidden=0';
        }
        $arWhere[] = 'deleted=0';

        return implode(' AND ', $arWhere);
    }

    /**
     * Returns count of objects in DB
     *
     * @param string $strWhereClause Where clause for the select.
     *
     * @return integer
     */
    public function countByWhereClause($strWhereClause)
    {
        $dataHandler = $this->serviceManager->getService('ForwardFW\\Controller\\DataHandlerInterface');

        $arResult = $dataHandler->loadFrom(
            $this->strDBConnection,
            array(
                'select' => 'count(id)',
                'from'   => $this->strTableName,
                'where'  => $strWhereClause,
            )
        );

        $row = reset($arResult);
        return (int) reset($row);
    }

    public function loadByIds($ids)
    {
        return $this->loadByWhereClause(/*$this->strIdFieldName . '*/ 'id in (' . implode(',', $ids) . ')');
    }

    /**
     * Loads all data from given table by given where clause.
     *
     * @param string $strWhereClause Where clause for the select.
     * @param string $strGroupBy     Group by clause for the select.
     * @param string $strOrderBy     Order by clause for the select.
     * @param string $strLimit       Limit clause for the select.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadByWhereClause(
        $strWhereClause = null,
        $strGroupBy = null,
        $strOrderBy = null,
        $strLimit = null,
        $tableAs = null
    ) {
        $dataHandler = $this->serviceManager->getService('ForwardFW\\Controller\\DataHandlerInterface');

        $arResult = $dataHandler->loadFrom(
            $this->strDBConnection,
            array(
                'select' => '*',
                'from'   => $this->strTableName . ($tableAs ? ' as ' . $tableAs : ''),
                'where'  => $strWhereClause,
                'group'  => $strGroupBy,
                'order'  => (is_null($strOrderBy) ? $this->strOrderBy : $strOrderBy),
                'limit'  => $strLimit,
            )
        );
        if ($arResult !== false) {
            $this->loadByArray($arResult);
            return true;
        }
        return false;
    }

    /**
     * Saves all elements in this container to the database.
     *
     * @return void
     */
    public function save()
    {
        foreach ($this as $value) {
            $value->save();
        }
    }

    /**
     * Truncates complete given table.
     *
     * @return boolean True if table was truncated otherwise false.
     */
    public function truncate()
    {
        $dataHandler = $this->serviceManager->getService('ForwardFW\\Controller\\DataHandlerInterface');

        $arResult = $dataHandler->truncate(
            $this->strDBConnection,
            array(
                'table' => $this->strTableName,
            )
        );
        if ($arResult !== false) {
            return true;
        }
        return false;
    }
}
