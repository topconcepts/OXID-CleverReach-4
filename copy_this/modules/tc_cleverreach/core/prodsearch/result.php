<?php
/**
 * Generates search results for oxlist objects
 *
 * @class tc_cleverreach_prodsearch_result
 */
class tc_cleverreach_prodsearch_result {

    /**
     * List object
     *
     * @var null|oxarticlelist
     */
    protected $list = null;

    /**
     * Sets list object
     *
     * @param oxarticlelist $list
     */
    public function __construct(oxArticleList $list) {
        $this->list = $list;
    }

    /**
     * Builds json construct from list array
     *
     * @return array
     * @throws tc_cleverreach_exception
     */
    public function getResultArray() {

        $outputArr = array();
        if (count($this->list) === 0) {
            throw new tc_cleverreach_exception('article list is empty');
        }

        $outputArr['settings']                        = array();
        $outputArr['settings']['type']                = 'product';
        $outputArr['settings']['link_editable']       = false;
        $outputArr['settings']['link_text_editable']  = false;
        $outputArr['settings']['image_size_editable'] = false;
        $outputArr['items']                           = array();

        foreach ($this->list as $item) {
            $newArr['title']        = $item->oxarticles__oxtitle->value;
            $newArr['description']  = $item->getLongDesc();
            //$newArr['content']      = $item->getLongDesc();
            $newArr['image']        = $item->getThumbnailUrl();
            $link                   = preg_replace('/(\?force_sid=[\d\w]+)/', '', $item->getLink());
            $newArr['url']          = $link;
            $currency               = oxRegistry::getConfig()->getActShopCurrencyObject();
            $newArr['price']        = $item->getFPrice() . ' ' . $currency->sign;
            $newArr['display_info'] = null;
            $outputArr['items'][]   = $newArr;
        }

        return $outputArr;
    }
}
