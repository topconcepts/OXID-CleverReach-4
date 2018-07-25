<?php

/**
 * Transfer functions used by the cleverreach cronjobs
 */
class tc_cleverreach_transfer
{

    /**
     * Enthält die ID der CleverReach Liste
     *
     * @var int
     */
    protected $listId;

    /**
     * Enthält den API Key von CleverReach
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Art der Übertragung (API|CSV)
     *
     * @var string
     */
    protected $transferType = 'API';

    /**
     * Shop aus dem die Daten übertragen werden
     *
     * @var string
     */
    protected $shopId = '';

    /**
     * Liste aller Shops
     *
     * @var array
     */
    public $shopList = array();

    /**
     * Soap Object
     *
     * @var array
     */
    public $soapObjs = array();

    /**
     * Rest Api Handler
     *
     * @var array
     */
    public $restByShop = array();

    /**
     * Offset for query limitations
     *
     * @var int
     */
    protected $offset = 0;

    protected $allShops;


    /**
     * Check if there's transfer data available
     * Load shop list on object creation
     * @param null $shopId
     * @param null $transferType
     * @param bool $allShops
     * @throws oxConnectionException
     */
    public function __construct($shopId = null, $transferType = null, $allShops = false)
    {

        $this->allShops = $allShops;

        if (isset($shopId) === true) {
            $this->shopId = $shopId;
        } else {
            $this->shopId = oxRegistry::getConfig()->getShopId();
        }

        if ($transferType !== null) {
            $this->transferType = $transferType;
        }

        if ($this->hasDataResetParameter() || $this->hasAllDataParameter()) {
            $this->resetTransferedData($allShops);
        }

        if ($this->transferType === 'API' && !$this->hasDataResetParameter()) {
            $this->loadShopList();
        }
    }

    /**
     * Ist der Parameter zum zurücksetzten der Transferdaten vorhanden
     *
     * @return bool
     */
    public function hasDataResetParameter()
    {
        return (is_array($_SERVER['argv']) && in_array('reset', $_SERVER['argv']) === true);
    }

    public function hasAllDataParameter()
    {
        return (is_array($_SERVER['argv']) && in_array('all', $_SERVER['argv']) === true);
    }

    /**
     * Load Shop Lists and Configurations of the key & list for the REST connection
     */
    protected function loadShopList()
    {
        $sql = '
            SELECT
                oxid
            FROM
                oxshops';

        if($this->shopId && !$this->allShops)
        {
            $sql .= ' WHERE oxid = '. oxDb::getDb()->qstr($this->shopId);
        }

        $result = oxDb::getDb(oxDb::FETCH_MODE_ASSOC)->execute($sql);

        if ($result != false && $result->recordCount() > 0) {
            while ($result->EOF === false) {

                $this->restByShop[$result->fields['oxid']] = oxNew('tc_cleverreach_rest_api', $result->fields['oxid']);
                $this->shopList[]                          = $result->fields['oxid'];

                $result->moveNext();
            }
        }
    }

    /**
     * Checks if transfer is possible
     *
     * @return bool
     */
    public function transferPossible()
    {
        return (count($this->shopList) > 0) ? true : false;
    }

    /**
     * Startet den Datentransfer
     *
     * @param false|int $limit
     * @param bool $trueTotalRows
     *
     * @param bool $complete
     * @return array |array
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     * @throws tc_cleverreach_exception
     */
    public function run($limit, $complete)
    {
        $config     = oxRegistry::getConfig();
        $collection = tc_cleverreach_collection::factory($this->shopId);
        if (!$complete) {
            $this->offset = 0;
        }
        $collection->withFullList($complete);
        $collection->fetch($limit, $this->offset);
        $totalRows = $collection->getTotalRows();

        if ($this->getTransferType() === 'API') {
            if ($complete) {
                if ($totalRows - $this->offset > 0) {
                    return array($totalRows - $this->offset, (array)$this->restByShop[$this->shopId]->receiverAddBatch($collection));
                }
            } else if ($totalRows > 0) {
                return array($totalRows, (array)$this->restByShop[$this->shopId]->receiverAddBatch($collection));
            }
        } else {
            if ($complete) {
                if ($totalRows - $this->offset > 0) {
                    $csvHandler = tc_cleverreach_csv::getInstance();
                    $suffix     = 'user_' . $this->getShopId();
                    $csvHandler->setFileSuffix($suffix);

                    // Erstellt eine CSV Datei
                    return array($totalRows - $this->offset, $csvHandler->createCSVFile($collection));
                }
            } else if ($totalRows > 0) {
                $csvHandler = tc_cleverreach_csv::getInstance();
                $suffix     = 'user_' . $this->getShopId();
                $csvHandler->setFileSuffix($suffix);

                // Erstellt eine CSV Datei
                return array($totalRows, $csvHandler->createCSVFile($collection));
            }
        }

        return array(0, true);
    }

    /**
     * Transfer fertig
     */
    public function transferComplete()
    {
        oxRegistry::getConfig()->saveShopConfVar('int', 'tc_cleverreach_last_transfer', date('Y-m-d H:i:s'));
    }

    /**
     * Setzt alle Übertragungsstatus zurück
     * @param bool $allShops
     * @throws oxConnectionException
     */
    public function resetTransferedData($allShops = false)
    {
        $shopWhere = '';
        if(!$allShops)
        {
            $shopWhere = 'WHERE shopid = ' . oxDb::getDb()->qstr($this->shopId);
        }

        $sqlNews = '
            UPDATE
                tc_cleverreach_news
            SET
                tc_cleverreach_last_transfer = 0
            ';

        $sqlNews = $sqlNews.$shopWhere;

        oxDb::getDb()->execute($sqlNews);

        $sqlUser = '
            UPDATE
                tc_cleverreach_user
            SET
                tc_cleverreach_last_transfer = 0
            ';

        $sqlUser = $sqlUser.$shopWhere;

        oxDb::getDb()->execute($sqlUser);

        $sqlOrder = '
            UPDATE
                tc_cleverreach_order
            SET
                tc_cleverreach_send = 0
            ';

        $sqlOrder = $sqlOrder.$shopWhere;
        oxDb::getDb()->execute($sqlOrder);

        echo "Alle Transferdaten der Nutzer und Bestellungen auf 0\n\n";
    }

    /**
     * Setzt die Listen ID
     *
     * @param int $listId
     */
    public function setListId($listId)
    {
        $this->listId = $listId;
    }

    /**
     * Setzt den Api Key
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Setzt den Übertragungstypen
     *
     * @param string $transferType
     */
    public function setTransferType($transferType)
    {
        $this->transferType = $transferType;
    }

    /**
     * Setzt die Shop ID
     *
     * @param string $shopId
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * Liefert die Listen ID
     *
     * @return int
     */
    public function getListId()
    {
        if ($this->getShopId() != '') {
            return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_list_id', $this->getShopId());
        }

        return $this->listId;
    }

    /**
     * Liefert den Api Key
     *
     * @return string
     */
    public function getApiKey()
    {
        if ($this->getShopId() != '') {
            return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_api_key', $this->getShopId());
        }

        return $this->apiKey;
    }

    /**
     * Liefert den Übertragungstypen
     *
     * @return string
     */
    public function getTransferType()
    {
        return $this->transferType;
    }

    /**
     * Liefert die ShopId
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * Liefert die Liste aller Shops
     *
     * @return array
     */
    public function getShopList()
    {
        return $this->shopList;
    }

    /**
     * Set offset from where to start
     * the bunch of queries
     *
     * @param $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
}
