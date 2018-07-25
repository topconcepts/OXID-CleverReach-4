<?php
/**
 * Handles incoming search request from prodsearch_controller
 *
 * @class tc_cleverreach_prodsearch_handler
 */
class tc_cleverreach_prodsearch_handler {

    /**
     * constant request parameter for categories currently (inactive)
     *
     * @var string
     */
    CONST REQUEST_CAT   = 'category';

    /**
     * constant request parameter for manufacturer requests (currently inactive)
     *
     * @var string
     */
    CONST REQUEST_MANU  = 'manufacturer';

    /**
     * constnat requrest parameter for product requests
     *
     * @var string
     */
    CONST REQUEST_PROD  = 'product';


    /**
     * Handles incoming request
     * Checks for valid pw if given
     * Checks for correct loading
     * Builds form data with elements and results
     * if specific result parameter is given
     *
     * @return string
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     * @throws tc_cleverreach_exception
     */
    public function handle() {

        $config           = oxRegistry::getConfig();
        $passwd           = $config->getRequestParameter('password');
        $hasArticleSearch = $config->getRequestParameter(self::REQUEST_PROD);

        $query = "
            SELECT
                oxid
            FROM
                tc_cleverreach_prodsearch
            WHERE
                password = ? LIMIT 1";

        $id     = oxDb::getDB()->getOne($query, array($passwd));
        $search = oxNew('tc_cleverreach_prodsearch');
        $loaded = $search->load($id);

        if ($loaded === false) {
            return json_encode('error, no item found for product search');
        }

        $elements = $search->getFormElements();
        if (count($elements) === 0) {
            return json_encode('error, no elements for this search');
        }

        // output json
        $formData = array();
        foreach ($elements as $element) {
            $formData[] = $element->getFormularData();
        }

        // CR backend user triggered a search for products
        if (isset($hasArticleSearch) === true) {

            // currently we are supporting only oxsearch dependent search
            // and no filtering by category or manufacturer
            $finder     = oxNew('tc_cleverreach_prodsearch_finder_oxsearch');
            $products   = $finder->getSearchProducts($hasArticleSearch);
            $result     = oxNew('tc_cleverreach_prodsearch_result', $products);
            $prodResult = $result->getResultArray();
            $formData   = array_merge($formData, $prodResult);
        }

        return json_encode($formData);
    }
}
