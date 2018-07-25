<?php

/**
 * Administration View for cleverreach options
 *
 * @author nkuersten
 */
class tc_cleverreach_config extends oxAdminDetails
{

    /**
     * Constant string for export path
     *
     * @var string
     */
    const SETTING_EXPORT_PATH = 'tc_cleverreach_exportpath';

    /**
     * Constant string for order options
     *
     * @var string
     */
    const SETTING_ORDER_FLAG = 'tc_cleverreach_with_orders';

    /**
     * Name of the template file
     * @var string
     */
    protected $_sThisTemplate = 'tc_cleverreach_config.tpl';

    /**
     * @throws oxSystemComponentException
     * @return string
     */
    public function render()
    {

        $parent = parent::render();

        if ($this->tc_isOAuthTokenValid()) {
            $this->checkIfCurrentListValid();
        }

        return $parent;
    }

    /**
     *
     * @throws Exception
     */
    public function createList()
    {
        $config     = oxRegistry::getConfig();
        $restClient = oxNew('tc_cleverreach_rest_api');
        $result     = $restClient->createList(oxRegistry::getConfig()->getRequestParameter('list-name'));
        $config->saveShopConfVar('str', 'tc_cleverreach_list_id', $result->id, $config->getShopId());
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

    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return object
     */
    public function getAccountId()
    {
        return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_account_id');
    }

    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return object
     */
    public function getLogin()
    {
        return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_login');
    }

    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return object
     */

    public function getPassword()
    {
        return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_password');
    }

    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return string
     */

    public function getOAuthClientId()
    {
        $oAuth = oxRegistry::get("tc_cleverreach_oauth");

        return $oAuth::CR_OAUTH_CLIENT_ID;
    }


    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return string
     */

    public function getOAuthClientSecret()
    {
        $oAuth = oxRegistry::get("tc_cleverreach_oauth");

        return $oAuth::CR_OAUTH_CLIENT_SECRET;
    }

    /**
     * A very simple getter.
     * We use this getter instead of writing data directly into $this->_aViewData
     *
     * @return object
     */

    public function getListId($shopId = null)
    {
        $shopId = $shopId !== null ? $shopId : oxRegistry::getConfig()->getShopId();

        return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_list_id', $shopId);
    }

    /**
     * Check if the OAuth token is saved compare it's timestamp with current time to see if it's still valid
     * @param null|int|string $shopId
     * @return bool
     */
    public function tc_isOAuthTokenValid($shopId = null)
    {
        return tc_cleverreach_oauth::tc_isOAuthTokenValid($shopId);
    }

    /**
     * @return string
     */
    public function tc_getOAuthUrl()
    {
        $clientid = $this->getOAuthClientId();
        $rdu      = urlencode(tc_cleverreach_oauth::getOAuthRedirectUrl());

        return 'https://rest.cleverreach.com/oauth/authorize.php?client_id=' . $clientid . '&grant=basic&response_type=code&redirect_uri=' . $rdu;
    }

    /**
     * @param null|string|int $shopId
     */
    public function resetOAuthToken($shopId = null)
    {
        tc_cleverreach_oauth::resetOAuthToken($shopId);
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
     * @throws \Exception
     * @return bool|mixed
     */
    public function getLists()
    {
        $restClient = oxNew('tc_cleverreach_rest_api');
        return $restClient->getAllLists();
    }

    /**
     * @throws oxSystemComponentException
     */
    protected function checkIfCurrentListValid()
    {
        $restClient = oxNew('tc_cleverreach_rest_api');
        $result = $restClient->isListValid($this->getListId());

        if (!$result) {
            oxRegistry::getConfig()->saveShopConfVar('str', 'tc_cleverreach_list_id', null);
        }
    }

    public function select_list()
    {
        oxRegistry::getConfig()->saveShopConfVar('str', 'tc_cleverreach_list_id', oxRegistry::getConfig()->getRequestParameter('selectlist'));
    }

    /**
     * @throws \Exception
     */
    public function getListNameById()
    {
        $restClient = oxNew('tc_cleverreach_rest_api');
        $result = $restClient->getListById($this->getListId());

        return $result->name;
    }

    public function disconnectList()
    {
        oxRegistry::getConfig()->saveShopConfVar('str', 'tc_cleverreach_list_id', null);
    }

    /**
     * Sets a view value to view object
     *
     * @return null
     */
    public function tc_getLastTransfer()
    {
        return oxRegistry::getConfig()->getShopConfVar('tc_cleverreach_last_transfer');
    }


}
