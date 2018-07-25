<?php

/**
 *
 */
class tc_cleverreach_oauth extends oxUbase
{
    const CR_OAUTH_CLIENT_ID     = 'fq5nIUguhs';
    const CR_OAUTH_CLIENT_SECRET = '2WDXSTLf8r2fSh7T4doqxDRC1Axh4PK6';

    /**
     * Cleverreach template name
     *
     * @var string
     * @return bool
     */
    protected $_sThisTemplate = 'tc_cleverreach_oauth.tpl';

    public static function tc_isOAuthTokenValid($shopId = null)
    {
        $shopId         = isset($shopId) ? $shopId : oxRegistry::getConfig()->getShopId();
        $token          = oxRegistry::getConfig()->getShopConfVar('tcCleverReachOAuthToken', $shopId);
        $tokenTimestamp = (int)oxRegistry::getConfig()->getShopConfVar('tcCleverReachOAuthTokenTimestamp', $shopId);

        return $token != '' && $tokenTimestamp != '' && (time() + 15 < $tokenTimestamp);
    }

    /**
     * @param null|string|int $shopId
     */
    public static function resetOAuthToken($shopId = null)
    {
        $shopId = isset($shopId) ? $shopId : oxRegistry::getConfig()->getShopId();

        oxRegistry::getConfig()->saveShopConfVar('str', 'tcCleverReachOAuthToken', '', $shopId);
        oxRegistry::getConfig()->saveShopConfVar('str', 'tcCleverReachOAuthTokenTimestamp', '', $shopId);
    }

    /**
     * @return string
     */
    public static function getOAuthRedirectUrl()
    {
        $shopId = oxRegistry::getConfig()->getShopId();
        return oxRegistry::getConfig()->getShopConfVar('sSSLShopURL') . "?cl=tc_cleverreach_oauth&shp=$shopId";
    }

    /**
     * @return bool|null|string
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     */
    public function render()
    {
        $parent = parent::render();

        $code = oxRegistry::getConfig()->getRequestParameter('code');

        if (isset($code) == false) {

            return false;
        }
        $token_url = "https://rest.cleverreach.com/oauth/token.php";

        //prepare post
        $fields["client_id"]     = $this::CR_OAUTH_CLIENT_ID;
        $fields["client_secret"] = $this::CR_OAUTH_CLIENT_SECRET;

        //must be the same as previous redirect uri
        $fields["redirect_uri"] = self::getOAuthRedirectUrl();
        //tell oauth what we want! we want to trade in our auth code for an access token
        $fields["grant_type"] = "authorization_code";
        $fields["code"]       = $_GET["code"];

        //Trade the Authorize token for an access token
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $token_url);
        curl_setopt($curl, CURLOPT_POST, sizeof($fields));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        $creds = json_decode($result, true);
        $shopId = oxRegistry::getConfig()->getShopId();

        oxRegistry::getConfig()->saveShopConfVar('str', 'tcCleverReachOAuthToken', $creds['access_token'], $shopId);
        oxRegistry::getConfig()->saveShopConfVar('str', 'tcCleverReachOAuthTokenTimestamp', time() + $creds['expires_in'], $shopId);

        if (self::tc_isOAuthTokenValid()) {
            $this->insertProdsearch();
        }

        return $this->_sThisTemplate;
    }


    /**
     * Insert rows into tc_cleverreach_news table
     *
     * @return void
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     */
    protected function insertProdsearch()
    {
        $id         = oxDb::getDB()->getOne("SELECT oxid FROM tc_cleverreach_prodsearch LIMIT 1");
        $prodSearch = oxNew('tc_cleverreach_prodsearch');
        $prodSearch->load($id);

        $baseUrl                                     = self::getDefaultProdsearchUrl();
        $shopId                                      = oxRegistry::getConfig()->getShopId();
        $url                                         = $baseUrl . '&name=' . htmlentities($this->getProdSearchName()) . '&shp=' . htmlentities($shopId);
        $prodSearch->tc_cleverreach_prodsearch__url  = new oxField($url);
        $prodSearch->tc_cleverreach_prodsearch__name = new oxField($this->getProdSearchName());
        $prodSearch->tc_cleverreach_prodsearch__shopid = new oxField($shopId);
        $prodSearch->save();
    }

    /**
     * Builds the default url to use
     *
     * @return string
     */
    protected function getDefaultProdsearchUrl()
    {
        return oxRegistry::getConfig()->getShopUrl() . 'index.php?cl=tc_cleverreach_prodsearch_controller';
    }

    /**
     * @return mixed
     */
    public function getProdSearchName()
    {
        return str_replace(' ', '_', oxRegistry::getConfig()->getActiveShop()->oxshops__oxname->value);
    }
}
