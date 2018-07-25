<?php
/**
 * Handle saving to the tc_cleverreach_news table
 */
class tc_cleverreach_oxnewssubscribed extends tc_cleverreach_oxnewssubscribed_parent {

    /**
     * Check if parent save() was successfull
     * Insert/replace an entry in tc_cleverreach_news
     * @return parent::save()
     */
    public function save() {
        $parent_result = parent::save();

        if($parent_result) {
            $db = oxDb::getDb();
            $db->Execute($query = '
                    REPLACE INTO
                        tc_cleverreach_news
                    SET
                        newsid = '.$db->qstr($this->oxnewssubscribed__oxid->value).',
                        userid = '.$db->qstr($this->oxnewssubscribed__oxuserid->value).',
                        tc_cleverreach_last_edit = now(),
                        shopid = ' . $db->qstr(oxRegistry::getConfig()->getShopId()) . ',
                        tc_cleverreach_last_transfer="0000-00-00 00:00:00"
            ');
        }

        return $parent_result;
    }

}


