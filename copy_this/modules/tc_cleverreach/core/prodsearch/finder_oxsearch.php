<?php
/**
 *
 * Uses oxsearch as search result retrieval
 *
 * @class tc_cleverreach_prodsearch_finder_oxsearch
 */
class tc_cleverreach_prodsearch_finder_oxsearch implements tc_cleverreach_prodsearch_finder {

    /**
     * Gets the search results like oxid does
     *
     * @param $searchString
     * @return mixed
     * @throws oxSystemComponentException
     */
    public function getSearchProducts($searchString) {

        $search = oxNew('oxsearch');
        return $search->getSearchArticles($searchString, false, false, false, false);

    }

}
