<?php

/**
 * Extends newsletter
 * Remove users from cleverreach if they deactivate
 * newsletters in shop
 * @author top concepts
 */
class tc_cleverreach_newsletter extends tc_cleverreach_newsletter_parent
{

    /**
     * Determine user by email address and flag him as single optin user
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     * @return null
     */
    public function singleoptin()
    {
        $email = oxRegistry::getConfig()->getRequestParameter('email');
        $uid   = false;

        if ($email != "") {
            $uid = oxDb::getDb()->getOne('SELECT oxid FROM oxuser WHERE oxusername=' . oxDb::getDb()->qstr(trim($email)));
        }
        $uid = $this->singleoptinUserSave($uid, $email);

        if ($uid != false) {
            $this->singleoptinIfUid($uid);
        }
        //Avoid e-mail sending
        oxRegistry::getSession()->setVariable("blDBOptInMailAlreadyDone", true);

        parent::addme();
        $this->_iNewsletterStatus = 5;
    }

    /**
     * Injects User-Id into POST or GET parameter
     *
     * @param string $uid
     */
    protected function singleoptinIfUid($uid)
    {
        //Inject the uid to the GET or POST
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['uid'] = $uid;
        } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
            $_GET['uid'] = $uid;
        }
    }

    /**
     * If mail does not long belong to a user, create a new one
     *
     * @param string $uid
     * @param string $email
     *
     * @throws oxSystemComponentException
     * @return string
     */
    protected function singleoptinUserSave($uid, $email)
    {
        if (!$uid && $email != "") {
            $oUser                     = oxNew('oxuser');
            $oUser->oxuser__oxusername = new oxField($email, oxField::T_RAW);
            $oUser->oxuser__oxactive   = new oxField(1, oxField::T_RAW);
            $oUser->oxuser__oxrights   = new oxField('user', oxField::T_RAW);
            $oUser->oxuser__oxshopid   = new oxField($this->getConfig()->getShopId(), oxField::T_RAW);
            $oUser->oxuser__oxfname    = new oxField("", oxField::T_RAW);
            $oUser->oxuser__oxlname    = new oxField("", oxField::T_RAW);
            $blUserLoaded              = $oUser->save();
            if ($blUserLoaded) {
                $uid = $oUser->oxuser__oxid->value;
            }
        }

        return $uid;
    }

    /**
     * Loads user and removes him from newsletter group.
     *
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     * @return null
     */
    public function removeme()
    {
        $email = oxRegistry::getConfig()->getRequestParameter('email');
        $uid   = oxRegistry::getConfig()->getRequestParameter('uid');
        if ($email != "") {
            $this->removemeIfEmail($email);
        } else if ($uid != "") {
            //Search DB for the email
            $query = 'SELECT oxusername FROM oxuser WHERE oxid=' . oxDb::getDb()->qstr(trim($uid));
            $email = oxDb::getDb()->getOne($query);
        }

        if (!empty($email) && !empty($uid)) {
            $oUser = oxNew('oxuser');
            $oUser->load($uid);
            $oUser->getNewsSubscription();
            if($oUser->getNewsSubscription() && $oUser->getNewsSubscription()->oxnewssubscribed__oxid->value){

                $db = oxDb::getDb();
                $db->execute(
                    '
                      REPLACE INTO
                          tc_cleverreach_news
                      SET
                          newsid =?,
                          userid =?,
                          tc_cleverreach_last_edit = now(),
                          shopid =?,
                          tc_cleverreach_last_transfer="0000-00-00 00:00:00"
              ',
                    array(
                        $oUser->getNewsSubscription()->oxnewssubscribed__oxid->value,
                        $uid,
                        oxRegistry::getConfig()->getShopId(),
                    )
                );
            }
        }

        parent::removeme();
    }

    /**
     * Get User-Id by email and inject it as POST/GET parameter
     *
     * @param string $email
     * @throws oxConnectionException
     * @return false|mixed|string
     */
    protected function removemeIfEmail($email)
    {
        //Search DB for the user
        $query = 'SELECT oxid FROM oxuser WHERE oxusername=' . oxDb::getDb()->qstr(trim($email));
        $uid   = oxDb::getDb()->getOne($query);

        if ($uid != "") {
            //Inject the uid to the GET or POST
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST['uid'] = $uid;
            } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
                $_GET['uid'] = $uid;
            }
            return $uid;
        }
        return oxRegistry::getConfig()->getRequestParameter('uid');
    }
}