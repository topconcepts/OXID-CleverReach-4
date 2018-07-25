<?php
/**
 * Reflects an input form in the cleverreach email creation backedn
 *
 * @class tc_cleverreach_prodsearch_form_input
 */
class tc_cleverreach_prodsearch_form_input extends tc_cleverreach_prodsearch_form  {

    /**
     * The type of the form element
     *
     * @var string
     */
    protected $type = 'input';

    /**
     * Builds formular data for json response
     *
     * @return array
     */
    public function getFormularData() {

        $settings                = $this->getSettings();
        $settings['type']        = $this->type;
        return $settings ;

     /*
        EXAMPLE:
        $contents = array(
            1 => array(
                'name'          => 'Product',
                'description'   => 'Place description here or leave emtpy',
                'required'      => false,
                'query_key'     => 'product',
                'type'          => 'input',
            )

        );
     */

    }
}
