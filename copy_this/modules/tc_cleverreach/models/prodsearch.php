<?php

/**
 * active record object for product search
 *
 * @class tc_cleverreach_prodsearch
 */
class tc_cleverreach_prodsearch extends oxi18n
{

    /**
     * Class name
     *
     * @var string
     */
    protected $_sClassName = 'tc_cleverreach_prodsearch';

    /**
     * clever reach api object
     *
     * @var tc_cleverreach_rest_api
     */
    protected $api = null;

    /**
     * Initialize object
     * @throws oxSystemComponentException
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('tc_cleverreach_prodsearch');

        $config = oxRegistry::getConfig();
        $shopId = $config->getShopId();

        $this->api = oxNew('tc_cleverreach_rest_api', $shopId);
    }

    /**
     * Current Module only supports a single product search element in shop
     */
    public function loadCurrentProdSearch()
    {
        $id = oxDB::getDB()->getOne("SELECT oxid FROM tc_cleverreach_prodsearch LIMIT 1");
        $this->load($id);
    }

    /**
     * After successful parent save
     * we try registering the search to CR backend
     *
     * @return bool|string
     */
    public function save()
    {
        $ret = parent::save();

        if ($ret !== false) {

            try {
                $result = $this->api->clientRegisterMyProductSearch(
                    array(
                        "name"     => $this->tc_cleverreach_prodsearch__name->value,
                        "url"      => $this->tc_cleverreach_prodsearch__url->value,
                        "password" => $this->tc_cleverreach_prodsearch__password->value,
                    )
                );
            } catch (\Exception $e) {
                $apiError = json_decode($this->api->error);
            }

            $utils = oxRegistry::get('oxutilsview');
            $duplicate = oxRegistry::getLang()->translateString('TC_CLEVERREACH_PRODSEARCH_EXISTS');
            $success   = oxRegistry::getLang()->translateString('TC_CLEVERREACH_PRODSEARCH_SUCCESS');
            $success   = sprintf($success, $this->tc_cleverreach_prodsearch__name->value);

            if ($result !== true) {
                switch ($apiError->error->code) {
                    case 409:
                        $utils->addErrorToDisplay($duplicate);
                        break;
                }
            } else {
                $utils->addErrorToDisplay($success);
            }
        }

        return $ret;

    }

    /**
     * Get all formular elements
     *
     * @return array
     * @throws oxSystemComponentException
     */
    public function getFormElements()
    {

        $elements = array();

        if ($this->tc_cleverreach_prodsearch__article->value) {
            $settings   = array(
                'name'        => 'Article',
                'description' => 'Basic Article Search',
                'required'    => false,
                'query_key'   => tc_cleverreach_prodsearch_handler::REQUEST_PROD,
                'type'        => 'input',
            );
            $elements[] = oxNew('tc_cleverreach_prodsearch_form_input', $settings);
        }

        return $elements;


        /*
        EXAMPLES FOR cat and manufacturer, if needed in future release:

        if ($this->tc_cleverreach_prodsearch__category->value) {
            $settings = array(
                'name'          => 'Category',
                'description'   => 'Place description here or leave emtpy',
                'required'      => false,
                'query_key'     => tc_cleverreach_prodsearch_handler::REQUEST_CAT,
                'type'          => 'dropdown',
            );
            $values = array(
                0 => array('value' => 123, 'text' => 'eins')
            );
            $elements[] = oxNew('tc_cleverreach_prodsearch_form_dropdown', $settings, $values);
        }
        if ($this->tc_cleverreach_prodsearch__manufacturer->value) {
            $settings = array(
                'name'          => 'Manufacturer',
                'description'   => 'Place description here or leave emtpy',
                'required'      => false,
                'query_key'     => tc_cleverreach_prodsearch_handler::REQUEST_MANU,
                'type'          => 'input',
            );
            $values = array(
                0 => array('value' => 123, 'text' => 'eins')
            );
            $elements[] = oxNew('tc_cleverreach_prodsearch_form_dropdown', $settings, $values);
        }
        */
    }

}