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
 * @subpackage List
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

require_once 'ForwardFW/Controller/DataLoader.php';
require_once 'ForwardFW/List.php';
require_once 'ForwardFW/Object/Sql.php';

/**
 * A list of models that can load themself from DB.
 *
 * @category   Object
 * @package    ForwardFW
 * @subpackage List
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_List_Sql extends ForwardFW_List
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
     * @param string $_strTablePrefix  Prefix der Tabellennamen im Projekt.
     * @param string $_strDBConnection Name of the DB connection to use.
     *
     * @return new instance
     */
    function __construct($_strTablePrefix = '', $_strDBConnection = 'default')
    {
        parent::__construct();

        $this->strTablePrefix  = $_strTablePrefix;
        $this->strDBConnection = $_strDBConnection;

        $this->strTableName = ForwardFW_Object_Sql::resolveTableName(
            $this->strTablePrefix, $this->strObjectName
        );
    }

    /**
     * Loads all data from given table.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    function loadAll()
    {
        return $this->loadByWhereClause('');
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
    function loadByWhereClause(
        $strWhereClause, $strGroupBy = '', $strOrderBy = '', $strLimit = ''
    ) {
        $objDataLoader = ForwardFW_Controller_DataLoader::getInstance(
            $this->strApplicationName
        );
        $arResult = $objDataLoader->loadFromDB(
            $this->strDBConnection,
            '*',
            $this->strTableName,
            $strWhereClause,
            $strGroupBy,
            $strOrderBy,
            $strLimit
        );
        if ($arResult !== false) {
            $this->loadByArray($arResult);
            return true;
        }
        return false;
    }
}
?>