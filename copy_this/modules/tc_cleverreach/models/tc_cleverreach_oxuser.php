<?php
/**
 * Handle tc_cleverreach_user saving
 */
class tc_cleverreach_oxuser extends tc_cleverreach_oxuser_parent {

    /**
     * Check if parent save() was successful
     * and insert/replace into tc_cleverreach_user
     * @return parent::save()
     */
    public function save() {
        $parent_result = parent::save();

        if($parent_result) {
            $db = oxDb::getDb();
            $news = $db->Execute($query = '
                    REPLACE INTO
                        tc_cleverreach_user
                    SET
                        userid = '.$db->qstr($this->oxuser__oxid->value).',
                        shopid = '.$db->qstr($this->oxuser__oxshopid->value).',
                        tc_cleverreach_last_edit = now()
            ');
        }

        return $parent_result;
    }

}
