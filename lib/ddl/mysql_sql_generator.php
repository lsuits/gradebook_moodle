<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Mysql specific SQL code generator.
 *
 * @package    core_ddl
 * @copyright  1999 onwards Martin Dougiamas     http://dougiamas.com
 *             2001-3001 Eloy Lafuente (stronk7) http://contiento.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/ddl/sql_generator.php');

/**
 * This class generate SQL code to be used against MySQL
 * It extends XMLDBgenerator so everything can be
 * overridden as needed to generate correct SQL.
 *
 * @package    core_ddl
 * @copyright  1999 onwards Martin Dougiamas     http://dougiamas.com
 *             2001-3001 Eloy Lafuente (stronk7) http://contiento.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mysql_sql_generator extends sql_generator {

    // Only set values that are different from the defaults present in XMLDBgenerator

    /** @var string Used to quote names. */
    public $quote_string = '`';

    /** @var string To define the default to set for NOT NULLs CHARs without default (null=do nothing).*/
    public $default_for_char = '';

    /** @var bool To specify if the generator must use some DEFAULT clause to drop defaults.*/
    public $drop_default_value_required = true;

    /** @var string The DEFAULT clause required to drop defaults.*/
    public $drop_default_value = null;

    /** @var string To force primary key names to one string (null=no force).*/
    public $primary_key_name = '';

    /** @var string Template to drop PKs. 'TABLENAME' and 'KEYNAME' will be replaced from this template.*/
    public $drop_primary_key = 'ALTER TABLE TABLENAME DROP PRIMARY KEY';

    /** @var string Template to drop UKs. 'TABLENAME' and 'KEYNAME' will be replaced from this template.*/
    public $drop_unique_key = 'ALTER TABLE TABLENAME DROP KEY KEYNAME';

    /** @var string Template to drop FKs. 'TABLENAME' and 'KEYNAME' will be replaced from this template.*/
    public $drop_foreign_key = 'ALTER TABLE TABLENAME DROP FOREIGN KEY KEYNAME';

    /** @var bool True if the generator needs to add extra code to generate the sequence fields.*/
    public $sequence_extra_code = false;

    /** @var string The particular name for inline sequences in this generator.*/
    public $sequence_name = 'auto_increment';

    public $add_after_clause = true; // Does the generator need to add the after clause for fields

    /** @var string Characters to be used as concatenation operator.*/
    public $concat_character = null;

    /** @var string The SQL template to alter columns where the 'TABLENAME' and 'COLUMNSPECS' keywords are dynamically replaced.*/
    public $alter_column_sql = 'ALTER TABLE TABLENAME MODIFY COLUMN COLUMNSPECS';

    /** @var string SQL sentence to drop one index where 'TABLENAME', 'INDEXNAME' keywords are dynamically replaced.*/
    public $drop_index_sql = 'ALTER TABLE TABLENAME DROP INDEX INDEXNAME';

    /** @var string SQL sentence to rename one index where 'TABLENAME', 'OLDINDEXNAME' and 'NEWINDEXNAME' are dynamically replaced.*/
    public $rename_index_sql = null;

    /** @var string SQL sentence to rename one key 'TABLENAME', 'OLDKEYNAME' and 'NEWKEYNAME' are dynamically replaced.*/
    public $rename_key_sql = null;

    /**
     * Reset a sequence to the id field of a table.
     *
     * @param xmldb_table|string $table name of table or the table object.
     * @return array of sql statements
     */
    public function getResetSequenceSQL($table) {

        if ($table instanceof xmldb_table) {
            $tablename = $table->getName();
        } else {
            $tablename = $table;
        }

        // From http://dev.mysql.com/doc/refman/5.0/en/alter-table.html
        $value = (int)$this->mdb->get_field_sql('SELECT MAX(id) FROM {'.$tablename.'}');
        $value++;
        return array("ALTER TABLE $this->prefix$tablename AUTO_INCREMENT = $value");
    }

    /**
     * Given one correct xmldb_table, returns the SQL statements
     * to create it (inside one array).
     *
     * @param xmldb_table $xmldb_table An xmldb_table instance.
     * @return array An array of SQL statements, starting with the table creation SQL followed
     * by any of its comments, indexes and sequence creation SQL statements.
     */
    public function getCreateTableSQL($xmldb_table) {
        // first find out if want some special db engine
        $engine = null;
        if (method_exists($this->mdb, 'get_dbengine')) {
            $engine = $this->mdb->get_dbengine();
        }

        $sqlarr = parent::getCreateTableSQL($xmldb_table);

        if (!$engine) {
            // we rely on database defaults
            return $sqlarr;
        }

        // let's inject the engine into SQL
        foreach ($sqlarr as $i=>$sql) {
            if (strpos($sql, 'CREATE TABLE ') === 0) {
                $sqlarr[$i] .= " ENGINE = $engine";
            }
        }

        return $sqlarr;
    }

    /**
     * Given one correct xmldb_table, returns the SQL statements
     * to create temporary table (inside one array).
     *
     * @param xmldb_table $xmldb_table The xmldb_table object instance.
     * @return array of sql statements
     */
    public function getCreateTempTableSQL($xmldb_table) {
        $this->temptables->add_temptable($xmldb_table->getName());
        $sqlarr = parent::getCreateTableSQL($xmldb_table); // we do not want the engine hack included in create table SQL
        $sqlarr = preg_replace('/^CREATE TABLE (.*)/s', 'CREATE TEMPORARY TABLE $1', $sqlarr);
        return $sqlarr;
    }

    /**
     * Given one correct xmldb_table, returns the SQL statements
     * to drop it (inside one array).
     *
     * @param xmldb_table $xmldb_table The table to drop.
     * @return array SQL statement(s) for dropping the specified table.
     */
    public function getDropTableSQL($xmldb_table) {
        $sqlarr = parent::getDropTableSQL($xmldb_table);
        if ($this->temptables->is_temptable($xmldb_table->getName())) {
            $sqlarr = preg_replace('/^DROP TABLE/', "DROP TEMPORARY TABLE", $sqlarr);
            $this->temptables->delete_temptable($xmldb_table->getName());
        }
        return $sqlarr;
    }

    /**
     * Given one XMLDB Type, length and decimals, returns the DB proper SQL type.
     *
     * @param int $xmldb_type The xmldb_type defined constant. XMLDB_TYPE_INTEGER and other XMLDB_TYPE_* constants.
     * @param int $xmldb_length The length of that data type.
     * @param int $xmldb_decimals The decimal places of precision of the data type.
     * @return string The DB defined data type.
     */
    public function getTypeSQL($xmldb_type, $xmldb_length=null, $xmldb_decimals=null) {

        switch ($xmldb_type) {
            case XMLDB_TYPE_INTEGER:    // From http://mysql.com/doc/refman/5.0/en/numeric-types.html!
                if (empty($xmldb_length)) {
                    $xmldb_length = 10;
                }
                if ($xmldb_length > 9) {
                    $dbtype = 'BIGINT';
                } else if ($xmldb_length > 6) {
                    $dbtype = 'INT';
                } else if ($xmldb_length > 4) {
                    $dbtype = 'MEDIUMINT';
                } else if ($xmldb_length > 2) {
                    $dbtype = 'SMALLINT';
                } else {
                    $dbtype = 'TINYINT';
                }
                $dbtype .= '(' . $xmldb_length . ')';
                break;
            case XMLDB_TYPE_NUMBER:
                $dbtype = $this->number_type;
                if (!empty($xmldb_length)) {
                    $dbtype .= '(' . $xmldb_length;
                    if (!empty($xmldb_decimals)) {
                        $dbtype .= ',' . $xmldb_decimals;
                    }
                    $dbtype .= ')';
                }
                break;
            case XMLDB_TYPE_FLOAT:
                $dbtype = 'DOUBLE';
                if (!empty($xmldb_decimals)) {
                    if ($xmldb_decimals < 6) {
                        $dbtype = 'FLOAT';
                    }
                }
                if (!empty($xmldb_length)) {
                    $dbtype .= '(' . $xmldb_length;
                    if (!empty($xmldb_decimals)) {
                        $dbtype .= ',' . $xmldb_decimals;
                    } else {
                        $dbtype .= ', 0'; // In MySQL, if length is specified, decimals are mandatory for FLOATs
                    }
                    $dbtype .= ')';
                }
                break;
            case XMLDB_TYPE_CHAR:
                $dbtype = 'VARCHAR';
                if (empty($xmldb_length)) {
                    $xmldb_length='255';
                }
                $dbtype .= '(' . $xmldb_length . ')';
                break;
            case XMLDB_TYPE_TEXT:
                $dbtype = 'LONGTEXT';
                break;
            case XMLDB_TYPE_BINARY:
                $dbtype = 'LONGBLOB';
                break;
            case XMLDB_TYPE_DATETIME:
                $dbtype = 'DATETIME';
        }
        return $dbtype;
    }

    /**
     * Given one xmldb_table and one xmldb_field, return the SQL statements needed to add its default
     * (usually invoked from getModifyDefaultSQL()
     *
     * @param xmldb_table $xmldb_table The xmldb_table object instance.
     * @param xmldb_field $xmldb_field The xmldb_field object instance.
     * @return array Array of SQL statements to create a field's default.
     */
    public function getCreateDefaultSQL($xmldb_table, $xmldb_field) {
        // Just a wrapper over the getAlterFieldSQL() function for MySQL that
        // is capable of handling defaults
        return $this->getAlterFieldSQL($xmldb_table, $xmldb_field);
    }

    /**
     * Given one correct xmldb_field and the new name, returns the SQL statements
     * to rename it (inside one array).
     *
     * @param xmldb_table $xmldb_table The table related to $xmldb_field.
     * @param xmldb_field $xmldb_field The instance of xmldb_field to get the renamed field from.
     * @param string $newname The new name to rename the field to.
     * @return array The SQL statements for renaming the field.
     */
    public function getRenameFieldSQL($xmldb_table, $xmldb_field, $newname) {
        // NOTE: MySQL is pretty different from the standard to justify this overloading.

        // Need a clone of xmldb_field to perform the change leaving original unmodified
        $xmldb_field_clone = clone($xmldb_field);

        // Change the name of the field to perform the change
        $xmldb_field_clone->setName($newname);

        $fieldsql = $this->getFieldSQL($xmldb_table, $xmldb_field_clone);

        $sql = 'ALTER TABLE ' . $this->getTableName($xmldb_table) . ' CHANGE ' .
               $xmldb_field->getName() . ' ' . $fieldsql;

        return array($sql);
    }

    /**
     * Given one xmldb_table and one xmldb_field, return the SQL statements needed to drop its default
     * (usually invoked from getModifyDefaultSQL()
     *
     * Note that this method may be dropped in future.
     *
     * @param xmldb_table $xmldb_table The xmldb_table object instance.
     * @param xmldb_field $xmldb_field The xmldb_field object instance.
     * @return array Array of SQL statements to create a field's default.
     *
     * @todo MDL-31147 Moodle 2.1 - Drop getDropDefaultSQL()
     */
    public function getDropDefaultSQL($xmldb_table, $xmldb_field) {
        // Just a wrapper over the getAlterFieldSQL() function for MySQL that
        // is capable of handling defaults
        return $this->getAlterFieldSQL($xmldb_table, $xmldb_field);
    }

    /**
     * Returns the code (array of statements) needed to add one comment to the table.
     *
     * @param xmldb_table $xmldb_table The xmldb_table object instance.
     * @return array Array of SQL statements to add one comment to the table.
     */
    function getCommentSQL ($xmldb_table) {
        $comment = '';

        if ($xmldb_table->getComment()) {
            $comment .= 'ALTER TABLE ' . $this->getTableName($xmldb_table);
            $comment .= " COMMENT='" . $this->addslashes(substr($xmldb_table->getComment(), 0, 60)) . "'";
        }
        return array($comment);
    }

    /**
     * Given one object name and it's type (pk, uk, fk, ck, ix, uix, seq, trg).
     *
     * (MySQL requires the whole xmldb_table object to be specified, so we add it always)
     *
     * This is invoked from getNameForObject().
     * Only some DB have this implemented.
     *
     * @param string $object_name The object's name to check for.
     * @param string $type The object's type (pk, uk, fk, ck, ix, uix, seq, trg).
     * @param string $table_name The table's name to check in
     * @return bool If such name is currently in use (true) or no (false)
     */
    public function isNameInUse($object_name, $type, $table_name) {

        // Calculate the real table name
        $xmldb_table = new xmldb_table($table_name);
        $tname = $this->getTableName($xmldb_table);

        switch($type) {
            case 'ix':
            case 'uix':
                // First of all, check table exists
                $metatables = $this->mdb->get_tables();
                if (isset($metatables[$tname])) {
                    // Fetch all the indexes in the table
                    if ($indexes = $this->mdb->get_indexes($tname)) {
                        // Look for existing index in array
                        if (isset($indexes[$object_name])) {
                            return true;
                        }
                    }
                }
                break;
        }
        return false; //No name in use found
    }


    /**
     * Returns an array of reserved words (lowercase) for this DB
     * @return array An array of database specific reserved words
     */
    public static function getReservedWords() {
        // This file contains the reserved words for MySQL databases
        // from http://dev.mysql.com/doc/refman/6.0/en/reserved-words.html
        $reserved_words = array (
            'accessible', 'add', 'all', 'alter', 'analyze', 'and', 'as', 'asc',
            'asensitive', 'before', 'between', 'bigint', 'binary',
            'blob', 'both', 'by', 'call', 'cascade', 'case', 'change',
            'char', 'character', 'check', 'collate', 'column',
            'condition', 'connection', 'constraint', 'continue',
            'convert', 'create', 'cross', 'current_date', 'current_time',
            'current_timestamp', 'current_user', 'cursor', 'database',
            'databases', 'day_hour', 'day_microsecond',
            'day_minute', 'day_second', 'dec', 'decimal', 'declare',
            'default', 'delayed', 'delete', 'desc', 'describe',
            'deterministic', 'distinct', 'distinctrow', 'div', 'double',
            'drop', 'dual', 'each', 'else', 'elseif', 'enclosed', 'escaped',
            'exists', 'exit', 'explain', 'false', 'fetch', 'float', 'float4',
            'float8', 'for', 'force', 'foreign', 'from', 'fulltext', 'grant',
            'group', 'having', 'high_priority', 'hour_microsecond',
            'hour_minute', 'hour_second', 'if', 'ignore', 'in', 'index',
            'infile', 'inner', 'inout', 'insensitive', 'insert', 'int', 'int1',
            'int2', 'int3', 'int4', 'int8', 'integer', 'interval', 'into', 'is',
            'iterate', 'join', 'key', 'keys', 'kill', 'leading', 'leave', 'left',
            'like', 'limit', 'linear', 'lines', 'load', 'localtime', 'localtimestamp',
            'lock', 'long', 'longblob', 'longtext', 'loop', 'low_priority', 'master_heartbeat_period',
            'master_ssl_verify_server_cert', 'match', 'mediumblob', 'mediumint', 'mediumtext',
            'middleint', 'minute_microsecond', 'minute_second',
            'mod', 'modifies', 'natural', 'not', 'no_write_to_binlog',
            'null', 'numeric', 'on', 'optimize', 'option', 'optionally',
            'or', 'order', 'out', 'outer', 'outfile', 'overwrite', 'precision', 'primary',
            'procedure', 'purge', 'raid0', 'range', 'read', 'read_only', 'read_write', 'reads', 'real',
            'references', 'regexp', 'release', 'rename', 'repeat', 'replace',
            'require', 'restrict', 'return', 'revoke', 'right', 'rlike', 'schema',
            'schemas', 'second_microsecond', 'select', 'sensitive',
            'separator', 'set', 'show', 'smallint', 'soname', 'spatial',
            'specific', 'sql', 'sqlexception', 'sqlstate', 'sqlwarning',
            'sql_big_result', 'sql_calc_found_rows', 'sql_small_result',
            'ssl', 'starting', 'straight_join', 'table', 'terminated', 'then',
            'tinyblob', 'tinyint', 'tinytext', 'to', 'trailing', 'trigger', 'true',
            'undo', 'union', 'unique', 'unlock', 'unsigned', 'update',
            'upgrade', 'usage', 'use', 'using', 'utc_date', 'utc_time',
            'utc_timestamp', 'values', 'varbinary', 'varchar', 'varcharacter',
            'varying', 'when', 'where', 'while', 'with', 'write', 'x509',
            'xor', 'year_month', 'zerofill'
        );
        return $reserved_words;
    }
}
