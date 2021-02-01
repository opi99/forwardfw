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

namespace ForwardFW\Controller\Repository;

/**
 * A object/model that can load themself from DB.
 */
abstract class Sql extends \ForwardFW\Controller\Repository
{
    /**
     * @var string Name of the table, which is mostly a prefix and the object name in lowercase.
     */
    protected $strTableName = '';

    /**
     * @var string Prefix for the table name
     */
    protected $strTablePrefix = '';

    /**
     * @var string Name of the connection which should be used for this object to load/save.
     */
    protected $strDBConnection = '';

    /**
     * constructor
     *
     * @param string $strIdFieldName  Fieldname which holds the object id.
     * @param string $strTablePrefix  Prefix der Tabellennamen im Projekt.
     * @param string $strDBConnection Name of the DB connection to use.
     *
     * @return new instance
     */
    public function __construct(
        $strIdFieldName = 'id',
        $strTablePrefix = '',
        $strDBConnection = 'default'
    ) {
        parent::__construct($strIdFieldName);

        $this->strTablePrefix  = $strTablePrefix;
        $this->strDBConnection = $strDBConnection;

        $this->strTableName = self::resolveTableName(
            $this->strTablePrefix,
            get_class($this)
        );
    }

    public function setApplication(\ForwardFW\Controller\ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Returns the name of table out of prefix and object name cobined with
     * an underscore and lowercased.
     *
     * @param string $strTablePrefix Prefix der Tabellennamen im Projekt.
     * @param string $strObjectName  Name of the object
     *
     * @return string The table name.
     */
    public static function resolveTableName($strTablePrefix, $strObjectName)
    {
        $result = ($strTablePrefix != '' ? $strTablePrefix . '_' : '');
        $result .= substr($strObjectName, strrpos($strObjectName, '\\') + 1);
        return strtolower($result);
    }

    /**
     * Loads a the object from DB by the given ID.
     *
     * @param mixed $id The ID for the object.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadById($id)
    {
        return $this->loadByWhereClause($this->strIdFieldName . '=' . $id);
    }

    /**
     * Loads the object by where clause. Only first hit will be selected.
     *
     * @param string $strWhereClause The SQL where clause to search for this object.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadByWhereClause($strWhereClause)
    {
        $dataHandler = \ForwardFW\Controller\DataHandler::getInstance(
            $this->application
        );
        $arResult = $dataHandler->loadFrom(
            $this->strDBConnection,
            array(
                'select' => '*',
                'from' => $this->strTableName,
                'where' => $strWhereClause,
                'limit' => '1',
            )
        );
        if ($arResult !== false && count($arResult) == 1) {
            $this->loadByArray($arResult[0]);
            return true;
        }
        return false;
    }

    /**
     * Saves the object to DB. If ID is set, then the DB will be updated. Otherwise
     * an insert statement will create it. The new ID will saved in object.
     *
     * @return boolean True if object was saveable otherwise false.
     */
    public function save()
    {
        $arToSave = array();
        $this->saveToArray($arToSave);

        unset($arToSave[$this->strIdFieldName]);

        $dataHandler = \ForwardFW\Controller\DataHandler::getInstance(
            $this->application
        );

        $id = $this->getId();
        if (empty($id)) {
            $isOk = $dataHandler->create(
                $this->strDBConnection,
                array(
                    'columns' => $this->getTableConfig(),
                    'to'      => $this->strTableName,
                    'values'  => $arToSave,
                )
            );
        } else {
            $isOk = $dataHandler->saveTo(
                $this->strDBConnection,
                array(
                    'columns' => $this->getTableConfig(),
                    'to'      => $this->strTableName,
                    'values'  => $arToSave,
                    'where'   => $this->strIdFieldName . ' = ' . $this->getId(),
                )
            );
        }
        return $isOk;
    }

    public function getTableConfig()
    {
        return array(
            'id' => 'integer',
        );
    }
}
