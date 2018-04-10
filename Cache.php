<?php

namespace Cache;


class Cache
{

    const TABLE_NAME = 'tblcache';

    /**
	 * The database interface handler
	 *
	 * @var clDB $DB
	 */
	protected $DB;

    /**
     * The status of the object
     *
     * @var bool
     */
    public $isvalid;

    /**
     * The table row containing all data of a single record
     *
     * @var array
     */
    public $row;

    /**
     * The primary key for the datbase row
     *
     * @var int $id
     */
    public $id;

    /**
     * The key of the data to be stored
     *
     * @var string $key_name
     */
    public $key_name;

    /**
     * The data to be stored
     *
     * @var string $value
     */
    public $value;

    /**
     * The time stamp of data expiration
     *
     * @var int $expiration
     */
    public $expiration;


    /**
     * Cache Constructor
     *
     * @param clDB $DB
     * @param int $id
     */
    public function __construct(clDB $DB, $id = 0)
    {
    	$this->DB = $DB;

        $_id = intval($id);
        if ($_id > 0) {
            $this->id = $_id;
            $this->refresh();
        } else {
            $this->id = 0;
            $this->isvalid = false;
        }
    }

    public function refresh()
    {
        // reset the validity of the object
        $this->isvalid = false;

        if ($this->id > 0) {
            $row = $this->DB->GetTableRow(static::TABLE_NAME, 'id', $this->id);
            if (isvalid($row)) {
                $this->isvalid = true;
                $this->row = $row;

                $this->key_name = $row['key_name'];
                $this->value = $row['value'];
                $this->expiration = $row["expiration"];
            } else {
                $this->key_name = '';
                $this->value = '';
                $this->expiration = 0;
            }
        }

        return $this->isvalid;
    }

    /**
     * Save/update a record to the database
     *
     * @return bool
     */
    public function save()
    {
        $fieldArray = array(
            'key_name' => $this->key_name,
            'value' => $this->value,
            'expiration' => $this->expiration
        );

        if ($this->isvalid) {

            // update the existing row in the database
            return boolval($this->DB->UpdateTableRow(self::TABLE_NAME, $fieldArray, 'id=' . intval($this->id)));
        } else {

            // add a new row to the database
            $id = $this->DB->InsertTableRow(self::TABLE_NAME, $fieldArray);
            if (!$id) {
                return false;
            }
            $this->id = $id;
            return true;
        }
    }


    /**
     * delete a single record
     *
     * @return bool
     */
    public function delete($id)
    {
        $whereclause = 'id = ' . intval($id);
        return boolval($this->DB->deletetablerows(self::TABLE_NAME, $whereclause));
    }


    /**
     * delete all expired records
     *
     * @return bool
     */
    public function deleteExpired()
    {
        $whereclause = 'expiration <= ' . time();
        return boolval($this->DB->deletetablerows(self::TABLE_NAME, $whereclause));
    }


    /**
     * delete all records of the table
     *
     * @return bool
     */
    public function deleteAll()
    {
        $whereclause = 'id > 0';
        return boolval($this->DB->deletetablerows(self::TABLE_NAME, $whereclause));
    }

    /**
     * find a record for a given key name
     *
     * @return array|bool
     */
    public function findByKeyName($keyName)
    {
        $sql = "Select * from " . self::TABLE_NAME . " where key_name = '" . $this->DB->SQLSafe($keyName) . "'";
        return $this->DB->gettablerowSQL($sql);

    }
}
