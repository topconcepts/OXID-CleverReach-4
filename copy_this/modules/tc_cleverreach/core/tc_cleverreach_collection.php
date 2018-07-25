<?php

/**
 * tc_cleverreach_collection
 *
 * Class that extends from Iterator and perform the queries to the database
 * @class tc_cleverreach_collection
 */
class tc_cleverreach_collection extends ArrayIterator
{

    /**
     * A class constant that points to the table in mysql DB
     * @var string
     */
    const LAST_TRANSFER = 'tc_cleverreach_last_transfer';

    /**
     * A class constant that points to the table in mysql DB
     * @var string
     */

    const LAST_EDIT = 'tc_cleverreach_last_edit';

    /**
     * A class constant that points to the table in mysql DB
     * @var string
     */
    const ORDER_SEND = 'tc_cleverreach_send';

    /**
     * Id of the shop
     *
     * @var string|integer
     */
    protected $shopId;

    /**
     * Total rows (uses lazy read)
     *
     * @var integer
     */
    protected $totalRows = null;

    /**
     * Stores the results of the query groupping by user
     *
     * @var array
     */
    protected $byUser;

    /**
     * Timestamp pointing to now
     *
     * @var string
     */
    public $now;

    /**
     * Elements pending to transfer
     *
     * @var array
     */
    public $toTransfer = array(array(), array());

    protected $fullList = false;

    /**
     * getFilteredIterator()
     * Get filtered iterator to iterate between the results
     *
     * @return tc_cleverreach_collection_filter
     * @throws oxSystemComponentException
     */
    public function getFilteredIterator()
    {
        return oxNew(
            $this->getIteratorClassName(),
            $this
        );
    }

    /**
     * getUserFilteredIterator()
     * Get filtered iterator (by User) to iterate between the results
     *
     * @return array
     * @throws oxSystemComponentException
     */
    public function getUserFilteredIterator()
    {
        $return = array();
        foreach ($this->byUser as $iterator) {
            $return[] = oxNew(
                $this->getIteratorClassName(),
                $iterator
            );
        }

        return ($return);
    }

    /**
     * setTransfer()
     * Execute the pending updates on the DB to set the transfers for the moved records
     */
    public function setTransfer()
    {
        $db = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $oxidArray = array();
        foreach ($this->toTransfer[0] as $oxids) {
            $oxidArray[] = $db->qstr($oxids);
        }

        if (count($oxidArray) > 0) {

            $sql = '
                UPDATE
                    tc_cleverreach_user user
                SET
                    user.' . self::LAST_TRANSFER . ' = FROM_UNIXTIME(' . $this->now . '+1) ,
                    user.' . self::LAST_EDIT . ' = FROM_UNIXTIME(' . $this->now . ')
                WHERE
                    user.shopid = ' . oxDB::getDb()->qstr($this->shopId) . '
                AND
                    user.userid IN (
            ';
            $sql .= implode(" , ", $oxidArray) . ')';

            $db->execute($sql);

            $sql = '
                UPDATE
                    tc_cleverreach_news news
                SET
                    news.' . self::LAST_TRANSFER . ' = FROM_UNIXTIME(' . $this->now . '+1) ,
                    news.' . self::LAST_EDIT . ' = FROM_UNIXTIME(' . $this->now . ')
                WHERE
                    news.shopid = ' . oxDB::getDb()->qstr($this->shopId) . '
                AND
                    news.userid IN (
            ';
            $sql .= implode(" , ", $oxidArray) . ')';
            $db->execute($sql);
        }


        $ordersArray = array();
        foreach ($this->toTransfer[1] as $order) {
            $ordersArray[] = $db->qstr($order);
        }

        if (count($ordersArray) > 0) {
            $sql = '
                UPDATE
                    tc_cleverreach_order `order`
                SET
                    `order`.tc_cleverreach_send = 1
                WHERE
                    `order`.orderid IN (
            ';
            $sql .= implode(" , ", $ordersArray) . ")";
            $db->execute($sql);
        }

        $this->toTransfer = array(array(), array());
    }

    /**
     * addTransferred($userId)
     * Mark the specific $document as exported
     *
     * @param $key
     */
    public function addTransferred($key)
    {
        if (!in_array($this[$key]->oxuser__oxid, $this->toTransfer[0])) {
            $this->toTransfer[0][] = $this[$key]->oxuser__oxid;
        }

        if ($this[$key]->oxorder__oxid !== null && !in_array($this[$key]->oxorder__oxid, $this->toTransfer[1])) {
            $this->toTransfer[1][] = $this[$key]->oxorder__oxid;
        }
    }

    /**
     * Get the query to get the data.
     *
     * @param mixed $limit Can be false or an integer (false means no limit)
     *
     * @param int $offset
     * @return string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getDataQuery($limit, $offset)
    {
        $addOrders = oxRegistry::getConfig()->getShopConfVar(tc_cleverreach_config::SETTING_ORDER_FLAG, $this->shopId);

        $sql = '
                SELECT SQL_CALC_FOUND_ROWS
                    user.userid AS user__userid,
                    news.newsid AS newsid,
                    oxuser.oxid as oxuser__oxid,
                    oxuser.oxusername as oxuser__oxusername,
                    oxuser.oxsal as oxuser__oxsal,
                    oxuser.oxfname as oxuser__oxfname,
                    oxuser.oxlname as oxuser__oxlname,
                    oxuser.oxcreate as oxuser__oxcreate,
                    oxuser.oxcompany as oxuser__oxcompany,
                    oxuser.oxstreet as oxuser__oxstreet,
                    oxuser.oxstreetnr as oxuser__oxstreetnr,
                    oxuser.oxcity as oxuser__oxcity,
                    oxuser.oxzip as oxuser__oxzip,
                    oxuser.oxbirthdate as oxuser__oxbirthdate,
                    oxuser.oxshopid as oxuser__oxshopid,
                    oxuser.oxcountryid as oxuser__oxcountryid,

                    oxnewssubscribed.oxid as oxnewssubscribed__oxid,
                    oxnewssubscribed.oxdboptin as oxnewssubscribed__oxdboptin,
                    oxnewssubscribed.oxsal as oxnewssubscribed__oxsal,
                    oxnewssubscribed.oxfname as oxnewssubscribed__oxfname,
                    oxnewssubscribed.oxlname as oxnewssubscribed__oxlname,
                    oxnewssubscribed.oxsubscribed as oxnewssubscribed__oxsubscribed,
                    oxnewssubscribed.oxemail as oxnewssubscribed__oxemail,

                    oxcountry.oxid as oxcountry__oxid,
                    oxcountry.oxtitle as oxcountry__oxtitle,
                    oxcountry.oxisoalpha2 as oxcountry__oxisoalpha2,';
        if ($addOrders) {
            $sql .= '
                    crorder.orderid AS orderid,
                    oxorder.oxid as oxorder__oxid,
                    oxorder.oxorderdate as oxorder__oxorderdate,
                    oxorder.oxordernr as oxorder__oxordernr,
                  
                    oxorderarticles.oxid as oxorderarticles__oxid,
                    oxorderarticles.oxtitle as oxorderarticles__oxtitle,
                    oxorderarticles.oxamount as oxorderarticles__oxamount,
                    oxorderarticles.oxartnum as oxorderarticles__oxartnum,
                    oxorderarticles.oxprice as oxorderarticles__oxprice,';
        }
        $sql .= '
                    oxshops.oxid as oxshops__oxid,
                    oxshops.oxname as oxshops__oxname
                FROM
                    `tc_cleverreach_news` news
                JOIN
                    `oxnewssubscribed` ON news.newsid = `oxnewssubscribed`.oxid
                JOIN
                    `tc_cleverreach_user` user ON news.userid = user.userid
                JOIN
                        `oxuser` ON `oxuser`.oxid = user.userid';
        if ($addOrders) {
            $sql .= '
                LEFT JOIN
                    `tc_cleverreach_order` crorder
                    LEFT JOIN
                        `oxorder`
                        LEFT JOIN
                            `oxorderarticles`
                        ON `oxorder`.oxid = `oxorderarticles`.oxorderid
                    ON `oxorder`.oxid = crorder.orderid
                ON news.userid = crorder.userid';
            if (!$this->fullList) {
                $sql .= ' AND crorder.tc_cleverreach_send=0 ';
            }
        }
        $sql .= '
                 LEFT JOIN
                     `oxcountry` ON `oxcountry`.oxid = `oxuser`.oxcountryid
                 JOIN
                     `oxshops` ON `oxshops`.oxid = `oxuser`.`oxshopid`
                 WHERE
                     user.shopid = ' . oxDB::getDb()->qstr($this->shopId) . '
                 AND
                     news.shopid = ' . oxDB::getDb()->qstr($this->shopId) . '
                 AND (
                       `oxnewssubscribed`.`oxdboptin` = 1
                        OR
                       `oxnewssubscribed`.`oxunsubscribed` != "0000-00-00 00:00:00"
                     )';
        if (!$this->fullList) {
            if ($addOrders) {
                $sql .= '
                AND (
                  crorder.tc_cleverreach_send=0
                OR ((
                      news.' . self::LAST_EDIT . ' >= news.' . self::LAST_TRANSFER . '
                      OR
                      user.' . self::LAST_EDIT . ' >= user.' . self::LAST_TRANSFER . '
                    )
                AND crorder.orderid IS NULL))';
            } else {
                $sql .= '
                AND (
                      news.' . self::LAST_EDIT . ' >= news.' . self::LAST_TRANSFER . '
                      OR
                      user.' . self::LAST_EDIT . ' >= user.' . self::LAST_TRANSFER . '
                    )';
            }
        }

        $sql .= '
        AND user.userid not like "%dummy%" ORDER BY user.userid
        LIMIT ' . $limit . ' OFFSET ' . $offset;

        return $sql;
    }

    /**
     * getIteratorClassName()
     * Get the classname of the iterator
     *
     * @return string
     */
    public function getIteratorClassName()
    {
        $filter = 'tc_cleverreach_collection_filter';
        if (oxRegistry::getConfig()->getConfigParam('tc_cleverreach_collection_filter')) {
            $filter = oxRegistry::getConfig()->getConfigParam('tc_cleverreach_collection_filter');
        }

        return $filter;
    }

    /**
     * getTotalRows()
     * Return the total rows (stored in $totalRows property) if null make the query to the DB.
     *
     * @return string
     * @throws oxConnectionException
     */
    public function getTotalRows()
    {
        return $this->totalRows;
    }

    /**
     * addElement(stdClass $element)
     * Add element to he object, including byUser property
     */
    public function addElement(stdClass $element)
    {
        $this->append($element);
        if (!array_key_exists($element->user__userid, $this->byUser)) {
            $this->byUser[$element->user__userid]      = oxNew(__CLASS__, $this->shopId);
            $this->byUser[$element->user__userid]->now = $this->now;
        }
        $this->byUser[$element->user__userid]->append($element);
    }

    /**
     * factory($shopId, $limit)
     * Factory that returns the object (use this instead of the constructor)
     * ! Legacy stuff, but kept.
     *
     * @param integer $shopId Shop ID
     *
     * @return tc_cleverreach_collection
     * @throws oxSystemComponentException
     */
    static function factory($shopId)
    {
        $obj = oxNew(__CLASS__, $shopId);

        return ($obj);
    }

    /**
     * Fetches a datasets for the given limit/offset
     * Procomputes the limit
     *
     * @param bool $limit
     * @param int $offset
     * @param bool $trueTotalRows
     *
     * @return tc_cleverreach_collection
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     */
    public function fetch($limit = false, $offset = 0, $trueTotalRows = true)
    {

        $sql                  = $this->getDataQuery($limit, $offset);
        $collection_resultset = oxDb::getDb()->execute($sql);
        $this->totalRows = oxDb::getDb()->getOne("select found_rows() as num;");

        if ($trueTotalRows === false) {
            $this->totalRows = $collection_resultset->RecordCount();
        }


        while ($collection_resultset->EOF === false) {

            //Creating Objects instead of Arrays to prevent memory wasting unnecessary
            $newElement = oxNew('stdClass');
            $row        = $collection_resultset->FetchRow();
            for ($i = 0; $i < $collection_resultset->FieldCount(); $i++) {
                $fields                 = $collection_resultset->FetchField($i);
                $fieldname              = strtolower($fields->name);
                $newElement->$fieldname = $row[$i];
            }
            $this->addElement($newElement);
        }

        return ($this);
    }

    /**
     * Setter for whole list
     *
     * @param $boolean
     */
    public function withFullList($boolean)
    {
        $this->fullList = $boolean;
    }

    /**
     * Constructor of the collection
     * This function have all the queries to the db to get the data
     */
    function __construct($shopId)
    {
        $this->shopId = $shopId;
        $this->now    = time();
        $this->byUser = array();
        parent::__construct(array());
    }
}
