<?php
declare(encoding = "utf-8");
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
 * @subpackage Object
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

require_once 'ForwardFW/Controller/DataLoader.php';
require_once 'ForwardFW/Object.php';

/**
 * A object/model that can load themself from DB.
 *
 * @category   Object
 * @package    ForwardFW
 * @subpackage Object
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Object_Sql extends ForwardFW_Object
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
     * constructor
     *
     * @param string $_strIdFieldName  Fieldname which holds the object id.
     * @param string $_strTablePrefix  Prefix der Tabellennamen im Projekt.
     * @param string $_strDBConnection Name of the DB connection to use.
     *
     * @return new instance
     */
    function __construct(
        $_strIdFieldName = 'ID',
        $_strTablePrefix = '',
        $_strDBConnection = 'default'
    ) {
        parent::__construct($_strIdFieldName);

        $this->strTablePrefix  = $_strTablePrefix;
        $this->strDBConnection = $_strDBConnection;

        $this->strTableName = ForwardFW_Object_Sql::resolveTableName(
            $this->strTablePrefix, get_class($this)
        );
    }

    /**
     * Returns the name of table out of prefix and object name cobined with
     * an underscore and lowercased.
     *
     * @param string $_strTablePrefix Prefix der Tabellennamen im Projekt.
     * @param string $_strObjectName  Name of the object
     *
     * @return string The table name.
     */
    static function resolveTableName($_strTablePrefix, $_strObjectName)
    {
        $strResult = (_$strTablePrefix != '' ? $_strTablePrefix.'_' : '');
        $strResult.= substr($_strObjectName, strrpos($_strObjectName, '_') + 1);
        return strtolower($strResult);
    }

    /**
     * Loads a the object from DB by the given ID.
     *
     * @param mixed $ID The ID for the object.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    function loadByID($ID)
    {
        return $this->loadByWhereClause($this->strIdFieldName.'='.$ID);
    }

    /**
     * Loads the object by where clause. Only first hit will be selected.
     *
     * @param string $strWhereClause The SQL where clause to search for this object.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    function loadByWhereClause($strWhereClause)
    {
        $dataLoader = ForwardFW_Controller_DataLoader::getInstance(
            $this->strApplicationName
        );
        $arResult = &$dataLoader->loadFromDB(
            $this->strDBConnection,
            '*',
            $this->strTableName,
            $strWhereClause, '', '', '1'
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
    function save()
    {
        $arToSave = array();
        $this->saveToArray($arToSave);

        $strWhereClause = $this->strIdFieldName.' = '.$this->getId();

        $dataLoader = ForwardFW_Controller_DataLoader::getInstance(
            $this->strApplicationName
        );
        $bResult = &$dataLoader->saveToDB(
            $this->strDBConnection,
            $arToSave,
            $this->strTableName,
            $strWhereClause
        );

        return $bResult;
    }
}

?>