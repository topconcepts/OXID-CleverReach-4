<?php

/**
 * Entry point for cleverreach product search. This is accessed
 * by cleverreach backend.
 *
 * @Class tc_cleverreach_prodsearch_controller
 */
class tc_cleverreach_prodsearch_controller extends oxUbase
{

    /**
     * Handle request and output json
     *
     * @return null|void
     * @throws oxConnectionException
     * @throws oxSystemComponentException
     * @throws tc_cleverreach_exception
     */
    public function render()
    {
        $handler = oxNew('tc_cleverreach_prodsearch_handler');
        header('Content-Type: application/json');
        $output = $handler->handle();
        echo $output;
        exit;
    }
}
