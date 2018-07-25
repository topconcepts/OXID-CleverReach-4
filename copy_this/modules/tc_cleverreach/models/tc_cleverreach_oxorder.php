<?php

if(true === false){
    class tc_cleverreach_oxorder extends oxOrder
    {

    }
}

/**
 * Handle tc_cleverreac_order saving
 *
 * @class tc_cleverreach_oxorder
 */
class tc_cleverreach_oxorder extends tc_cleverreach_oxorder_parent {

    /**
     * Check if parent save() was successful
     * and insert/replace into tc_cleverreach_order
     * @return parent::save()
     */
    public function save() {
        $parent_result = parent::save();

        if ($parent_result) {
            $db = oxDb::getDb();
            $db->Execute('
                    REPLACE INTO
                        tc_cleverreach_order
                    SET
                        orderid = '.$db->qstr($this->oxorder__oxid->value).',
                        userid  = '.$db->qstr($this->oxorder__oxuserid->value).',
                        shopid = ' . $db->qstr(oxRegistry::getConfig()->getShopId()) . ',
                        tc_cleverreach_send=0
            ');

            $oUser = $this->getOrderUser();
            $db->Execute('
                    REPLACE INTO
                        tc_cleverreach_user
                    SET
                        userid = '.$db->qstr($oUser->oxuser__oxid->value).',
                        shopid = '.$db->qstr($oUser->oxuser__oxshopid->value).',
                        tc_cleverreach_last_edit = now()
            ');
        }

        return $parent_result;
    }

}
