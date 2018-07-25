<?php

/**
 * Product search admin area of clever reach module
 *
 * @class tc_cleverreach_prodsearch_admin
 */
class tc_cleverreach_prodsearch_admin extends oxAdminDetails
{

    /**
     * Cleverreach template name
     *
     * @var string
     */
    protected $_sThisTemplate = 'tc_cleverreach_prodsearch_admin.tpl';

    /**
     * View object
     *
     * @var null
     */
    protected $searchObj = null;

    /**
     * Setting one product search object
     *
     * @return string
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     */
    public function render()
    {
        if (tc_cleverreach_oauth::tc_isOAuthTokenValid(oxRegistry::getConfig()->getShopId())) {
            $search = oxNew('tc_cleverreach_prodsearch');
            $id     = oxDb::getDB()->getOne("SELECT oxid FROM tc_cleverreach_prodsearch LIMIT 1");
            $search->load($id);
            $this->searchObj = $search;
        }
        parent::render();

        return $this->_sThisTemplate;

    }

    /**
     * Get the product search object
     *
     * @return tc_cleverreach_prodsearch
     */
    public function getEditObj()
    {
        return $this->searchObj;
    }

    /**
     * Gets the url
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->searchObj->tc_cleverreach_prodsearch__url->value;
    }

    /**
     * Gets the name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->searchObj->tc_cleverreach_prodsearch__name->value;
    }

    /**
     * Gets the password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->searchObj->tc_cleverreach_prodsearch__password->value;
    }

    /**
     * Builds the default url to use
     *
     * @return string
     */
    public function getDefaultUrl()
    {
        return $this->getViewConfig()->getConfig()->getShopUrl() . 'index.php?cl=tc_cleverreach_prodsearch_controller';
    }

    /**
     * Fnc that saves the product search
     *
     * @return bool
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     */
    public function saved()
    {

//        $edit = oxRegistry::getConfig()->getRequestParameter('editval');
//
//        if (isset($edit['name']) === false || $edit['name'] === '') {
//            $msg = oxRegistry::getLang()->translateString('TC_CLEVERREACH_PRODSEARCH_ERROR_NONAME');
//            oxRegistry::get('oxutilsview')->addErrorToDisplay($msg);
//
//            return false;
//        }
        // the current module will only allow one product search
        // later features might get multiple product searches
        $id         = oxDb::getDB()->getOne("SELECT oxid FROM tc_cleverreach_prodsearch LIMIT 1");
        $prodSearch = oxNew('tc_cleverreach_prodsearch');
        $prodSearch->load($id);
        $shopId                                          = oxRegistry::getConfig()->getShopId();
        $url                                             = $this->getDefaultUrl() . '&name=' . htmlentities($edit['name']) . '&shp=' . htmlentities($shopId);
        $prodSearch->tc_cleverreach_prodsearch__url      = new oxField($url);
        $prodSearch->tc_cleverreach_prodsearch__name     = new oxField($edit['name']);
        $prodSearch->tc_cleverreach_prodsearch__password = new oxField($edit['password']);
        $prodSearch->tc_cleverreach_prodsearch__shopid   = new oxField(oxRegistry::getConfig()->getShopId());
        $prodSearch->save();

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
     * Check if the OAuth token is saved compare it's timestamp with current time to see if it's still valid
     * @param null|int|string $shopId
     * @return bool
     */
    public function tc_isOAuthTokenValid($shopId = null)
    {
        return tc_cleverreach_oauth::tc_isOAuthTokenValid($shopId);
    }
}