<?php
/**
 * Backend administration panel for reset
 *
 * @author nkuersten
 */
class tc_cleverreach_manual_reset extends oxAdminDetails {

    /**
     * Name of the template file
     * @var string
     */
    protected $_sThisTemplate = 'tc_cleverreach_manual_reset.tpl';


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
