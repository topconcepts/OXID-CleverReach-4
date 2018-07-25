<?php

/**
 * Admin functions for data transfer
 */
class tc_cleverreach_transfer_manual extends oxAdminList
{

    /**
     * Name of the template file
     *
     * @var string
     */
    protected $_sThisTemplate = 'tc_cleverreach_transfer_manual.tpl';

    /**
     * Wie viele Datensätze sollen pro Aufruf übertragen werden
     *
     * @var int
     */
    protected $timer = 1000;


    /**
     * Values for template meta refresh url
     *
     * @var array
     */
    protected $metaRefreshValues = array(

        'full'      => null,
        'iReceiver' => null,
        'function'  => null,
        'transfer'  => null,
        'refresh'   => null,
        'iStart'    => null,
        'end'       => null,
        'offset'    => null,

    );

    /**
     * Holds a tc specific error messages as string
     *
     * @var string
     */
    protected $tcError = null;

    /**
     * Holds general exception messages as string
     *
     * @var string
     */
    protected $error = null;

    /**
     * Setzt Template Variablen für den Start der Übertragung
     *
     * @return bool
     * @throws oxSystemComponentException
     */
    public function transfer_start()
    {
        $config = oxRegistry::getConfig();
        $config->saveShopConfVar('bool', 'tc_cleverreach_with_orders', (boolean)$config->getRequestParameter('tc_cleverreach_with_orders'));

        $transfer = oxNew('tc_cleverreach_transfer');
        $transfer->setShopId($config->getShopId());

        // Daten sind fehlerhaft
        if (!$transfer->transferPossible()) {
            $lang          = oxRegistry::getLang();
            $msg           = $lang->translateString('TC_CLEVERREACH_ERROR_NO_KEY');
            $this->tcError = $msg;

            return false;
        }

        // add full flag, to check for full list export
        $full = (int)oxRegistry::getConfig()->getRequestParameter('tc_cleverreach_fulllist');

        $this->metaRefreshValues['full']      = $full;
        $this->metaRefreshValues['iReceiver'] = "???";
        $this->metaRefreshValues['function']  = 'transfer';
        $this->metaRefreshValues['transfer']  = 'user';
        $this->metaRefreshValues['refresh']   = 0;
        $this->metaRefreshValues['iStart']    = 0;
    }

    /**
     * Setzt Template Variablen für den Start der Übertragung
     *
     * @throws oxSystemComponentException
     * @return void
     */
    public function transfer_start_csv()
    {
        $config     = oxRegistry::getConfig();
        $exportPath = $config->getRequestParameter('tc_cleverreach_exportpath');

        $config->saveShopConfVar('str', 'tc_cleverreach_exportpath', $exportPath);

        $str = oxStr::getStr();

        // add an slash to end of path
        if ($str->strlen($exportPath) > 0 && $str->substr($exportPath, -1) !== '/') {
            $exportPath .= '/';
        }

        if (realpath(getShopBasePath() . $exportPath) === false) {
            $target  = getShopBasePath() . $exportPath;
            $langKey = oxRegistry::getLang()->translateString('TC_CLEVERREACH_ERROR_PATHINVALID');
            $langKey = sprintf($langKey, $target);
            oxRegistry::get('oxUtilsView')->addErrorToDisplay($langKey);
        }

        $config->saveShopConfVar('str', 'tc_cleverreach_exportpath', $exportPath);

        $transfer = oxNew('tc_cleverreach_transfer', null, 'CSV');
        $transfer->setTransferType('CSV');
        $transfer->setShopId($config->getShopId());

        // add full flag, to check for full list export
        $full = (int)$config->getRequestParameter('tc_cleverreach_fulllist');

        $this->metaRefreshValues['full']      = $full;
        $this->metaRefreshValues['iReceiver'] = "???";
        $this->metaRefreshValues['function']  = 'transfer_csv';
        $this->metaRefreshValues['transfer']  = 'user';
        $this->metaRefreshValues['refresh']   = 0;
        $this->metaRefreshValues['iStart']    = 0;
    }

    /**
     * Setzt den Übertragen Status zurück
     * @throws oxSystemComponentException
     * @throws oxConnectionException
     */
    public function transfer_start_reset()
    {
        $transfer = oxNew('tc_cleverreach_transfer');

        ob_start();
        $transfer->resetTransferedData();
        ob_end_clean();
        $this->metaRefreshValues['transfer'] = 'reset';
        $this->metaRefreshValues['refresh']  = 2;
        $this->metaRefreshValues['end']      = true;
    }

    /**
     * Startet den Datentransfer und stetzt View Variablen
     *
     * @return bool|void
     * @throws oxSystemComponentException
     */
    public function transfer()
    {
        $transfer = oxNew('tc_cleverreach_transfer');

        $complete = (boolean)oxRegistry::getConfig()->getRequestParameter('full');
        $offset   = (int)oxRegistry::getConfig()->getRequestParameter('offset');

        $transfer->setOffset($offset);

        try {
            list($count, $transferResult) = $transfer->run($this->getTimer(), $complete);
        } catch (tc_group_not_found_exception $e) {
            $this->error                              = $e->getMessage();
            $this->_aViewData['blShowListResetPopUp'] = true;

            return;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();

            return;
        }

        if (is_array($transferResult) === true && (count($transferResult) === 0 || $transferResult[0] === false)) {
            $this->metaRefreshValues['end'] = true;
            // Transfer fertig
            $this->transferComplete();

            return;
        }

        // Daten sind fehlerhaft
        if ($transferResult === false) {
            $lang          = oxRegistry::getLang();
            $msg           = $lang->translateString('TC_CLEVERREACH_ERROR_NO_KEY');
            $this->tcError = $msg;

            return false;

            // Alle Daten übertragen
        } elseif ($transferResult === true) {
            $this->metaRefreshValues['end'] = true;
            // Transfer fertig
            $this->transferComplete();

            return;
            // Anzeige ? Nutzer|Bestellungen
        } elseif (is_array($transferResult) === true) {
            $transferType = 'user';
            $iReceiver    = $count;

            // add full flag, to check for full list export
            $full                                 = (int)oxRegistry::getConfig()->getRequestParameter('full');
            $this->metaRefreshValues['full']      = $full;
            $this->metaRefreshValues['function']  = 'transfer';
            $this->metaRefreshValues['transfer']  = $transferType;
            $this->metaRefreshValues['iReceiver'] = $iReceiver;
            $this->metaRefreshValues['refresh']   = 0;
            $this->metaRefreshValues['offset']    = $this->getTimer() + (int)oxRegistry::getConfig()->getRequestParameter('offset');
        }
    }

    /**
     * Startet den Datentransfer und stetzt View Variablen
     *
     * @return bool|void
     * @throws oxSystemComponentException
     */
    public function transfer_csv()
    {
        $transfer = oxNew('tc_cleverreach_transfer', null, 'CSV');

        $config   = oxRegistry::getConfig();
        $complete = (boolean)$config->getRequestParameter('full');
        $offset   = (int)$config->getRequestParameter('offset');

        // when we use the full export, we have to work with offsets, otherwise 0
        if ($complete === false) {
            $offset = 0;
        }

        $transfer->setOffset($offset);

        try {
            list($count, $transferResult) = $transfer->run($this->getTimer(), $complete);
        } catch (Exception $e) {
            $this->error = $e->getMessage();

            return;
        }

        // Daten sind fehlerhaft
        if ($transferResult === false) {
            $lang          = oxRegistry::getLang();
            $msg           = $lang->translateString('TC_CLEVERREACH_ERROR_NO_KEY');
            $this->tcError = $msg;

            return false;

            // Alle Daten übertragen
        } elseif ($transferResult === true) {
            $this->metaRefreshValues['end'] = true;

            return;

            // Anzeige ? Nutzer|Bestellungen
        } elseif (is_array($transferResult) === true) {

            $transferType = 'user';
            $iReceiver    = $count;

            // add full flag, to check for full list export
            $full                                 = (int)$config->getRequestParameter('full');
            $this->metaRefreshValues['full']      = $full;
            $this->metaRefreshValues['function']  = 'transfer_csv';
            $this->metaRefreshValues['transfer']  = $transferType;
            $this->metaRefreshValues['iReceiver'] = $iReceiver;
            $this->metaRefreshValues['refresh']   = 0;
            $this->metaRefreshValues['offset']    = $this->getTimer() + (int)$config->getRequestParameter('offset');
        }
    }

    /**
     * Setzt die Anzahl der Datenübertragungen
     *
     * @param integer $timer
     *
     * @return void
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;
    }

    /**
     * Gibt die Anzahl der Datenübertragungen zurück
     *
     * @return int
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * Returns starting count
     *
     * @return integer
     */
    public function getStart()
    {
        return $this->metaRefreshValues['iStart'];
    }

    /**
     * Returns the function fnc name
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->metaRefreshValues['function'];
    }

    /**
     * Returns receiver count
     *
     * @return integer
     */
    public function getReceiver()
    {
        return $this->metaRefreshValues['iReceiver'];
    }

    /**
     * Returns the transfer type
     *
     * @return mixed
     */
    public function getTransfer()
    {
        return $this->metaRefreshValues['transfer'];
    }

    /**
     * Getter for shop id
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->metaRefreshValues['shopid'];
    }

    /**
     * Getter for full option check
     *
     * @return boolean
     */
    public function getFull()
    {
        return $this->metaRefreshValues['full'];
    }

    /**
     * Getter for offset
     *
     * @return integer
     */
    public function getOffset()
    {
        return $this->metaRefreshValues['offset'];
    }

    /**
     * Getter for refresh status
     *
     * @return integer
     */
    public function getRefresh()
    {
        return $this->metaRefreshValues['refresh'];
    }

    /**
     * Getter for refresh status
     *
     * @return integer
     */
    public function getEnd()
    {
        return $this->metaRefreshValues['end'];
    }

    /**
     * Getter for oxid internal exceptions
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Getter for top concepts exceptions
     *
     * @return string
     */
    public function getTcError()
    {
        return $this->tcError;
    }

    /**
     *
     */
    public function resetList()
    {
        oxRegistry::getConfig()->saveShopConfVar('str', 'tc_cleverreach_list_id', null);
    }

    /**
     * Transfer fertig
     */
    public function transferComplete()
    {
        oxRegistry::getConfig()->saveShopConfVar('int', 'tc_cleverreach_last_transfer', date('Y-m-d H:i:s'));
    }
}
