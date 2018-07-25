<?php
/**
 * Interface for finder
 */
interface tc_cleverreach_prodsearch_finder {

    /**
     * Used to implements the handling of the search source
     * Where the search results should come from
     *
     * @param $searchString
     * @return mixed
     */
    public function getSearchProducts($searchString);

}