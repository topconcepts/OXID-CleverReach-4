<?php

/**
 * Backend administration panel for export
 *
 * @author nkuersten
 */
class tc_cleverreach_manual_csv extends oxAdminDetails
{
    /**
     * Name of the template
     * @var string
     */
    protected $_sThisTemplate = 'tc_cleverreach_manual_csv.tpl';

    /**
     * A very simple getter.
     * We use this setter instead of writing data directly into $this->_aViewData
     *
     * @return string
     */
    public function tc_shopBasePath()
    {
        return realpath(getShopBasePath() . '../') . '/';
    }

    /**
     * Returns the complete path for export files
     *
     * @return string
     */
    public function getCompletePath()
    {
        $realpath = realpath(getShopBasePath() . $this->tc_getCsvPath());
        if ($realpath !== false) {
            return $realpath;
        }

        return false;
    }

    /**
     * A very simple getter.
     * We use this setter instead of writing data directly into $this->_aViewData
     *
     * @return object
     */
    public function tc_getCsvPath()
    {
        return oxRegistry::getConfig()->getShopConfVar(tc_cleverreach_config::SETTING_EXPORT_PATH);
    }

    /**
     * get flag for orders
     *
     * @return string
     */
    public function getOptionToggleOrders()
    {
        return oxRegistry::getConfig()->getShopConfVar(tc_cleverreach_config::SETTING_ORDER_FLAG);
    }

    /**
     * Get Klarna module url with added path
     *
     * @param string $sPath
     * @return string
     */
    public function getCleverreachModuleUrl($sPath = '')
    {
        $sUrl = str_replace(
            rtrim($this->getConfig()->getConfigParam('sShopDir'), '/'),
            rtrim($this->getConfig()->getCurrentShopUrl(false), '/'),
            $this->getConfig()->getConfigParam('sShopDir') . 'modules/tc_cleverreach/' . $sPath
        );

        return $sUrl;
    }
}
