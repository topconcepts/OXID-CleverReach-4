<?php

/**
 * Backend administration panel for export
 *
 * @author nkuersten
 */
class tc_cleverreach_manual_api extends oxAdminDetails
{

    /**
     * Name of the template file
     * @var string
     */
    protected $_sThisTemplate = 'tc_cleverreach_manual_api.tpl';

    /**
     * Sets a view value to view object
     *
     * @return null
     */
    public function tc_getLastTransfer()
    {
        return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_last_transfer');
    }

    /**
     * A very simple getter.
     * We use this setter instead of writing data directly into $this->_aViewData.
     *
     * @return string
     */
    public function tc_getShopBasePath()
    {
        return realpath(getShopBasePath() . '../') . '/';
    }

    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return string
     */

    public function getOAuthClientId()
    {
        return tc_cleverreach_oauth::CR_OAUTH_CLIENT_ID;
    }


    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return string
     */

    public function getOAuthClientSecret()
    {
        return tc_cleverreach_oauth::CR_OAUTH_CLIENT_SECRET;
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
     * Check if the OAuth token is saved compare it's timestamp with current time to see if it's still valid
     * @param int $shopId
     * @return bool
     */
    public function tc_isOAuthTokenValid($shopId = null)
    {
        return tc_cleverreach_oauth::tc_isOAuthTokenValid($shopId);

    }

    public function tc_getOAuthUrl()
    {
        $clientid = $this->getOAuthClientId();
        $rdu      = urlencode(tc_cleverreach_oauth::getOAuthRedirectUrl());

        return 'https://rest.cleverreach.com/oauth/authorize.php?client_id=' . $clientid . '&grant=basic&response_type=code&redirect_uri=' . $rdu;
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
