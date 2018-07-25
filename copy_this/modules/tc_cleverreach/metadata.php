<?php

$sMetadataVersion = '1.2';

$aModule = array(
    'id'          => 'tc_cleverreach',
    'title'       => array(
        'de' => 'Offizieller CleverReach® Connector',
        'en' => 'Official CleverReach® Connector',
    ),
    'description' => 'Dieses Modul ermöglicht es unkompliziert Kundendaten und Bestellungen nach CleverReach® zu übertragen.',
    'thumbnail'   => 'tc_logo.jpg',
    'version'     => '4.1.0',
    'author'      => 'top concepts GmbH',
    'email'       => 'support@topconcepts.com',
    'url'         => 'https://www.topconcepts.de',

    'extend' => array(
        'newsletter'       => 'tc_cleverreach/controllers/tc_cleverreach_newsletter',
        'oxnewssubscribed' => 'tc_cleverreach/models/tc_cleverreach_oxnewssubscribed',
        'oxorder'          => 'tc_cleverreach/models/tc_cleverreach_oxorder',
        'oxuser'           => 'tc_cleverreach/models/tc_cleverreach_oxuser',
    ),

    'files' => array(
        // Admin
        'tc_cleverreach_config'                     => 'tc_cleverreach/controllers/admin/tc_cleverreach_config.php',
        'tc_cleverreach_transfer_manual'            => 'tc_cleverreach/controllers/admin/tc_cleverreach_transfer_manual.php',
        'tc_cleverreach_frames'                     => 'tc_cleverreach/controllers/admin/tc_cleverreach_frames.php',
        'tc_cleverreach_list'                       => 'tc_cleverreach/controllers/admin/tc_cleverreach_list.php',
        'tc_cleverreach_manual_csv'                 => 'tc_cleverreach/controllers/admin/tc_cleverreach_manual_csv.php',
        'tc_cleverreach_manual_reset'               => 'tc_cleverreach/controllers/admin/tc_cleverreach_manual_reset.php',

        // Core
        'tc_cleverreach_collection'                 => 'tc_cleverreach/core/tc_cleverreach_collection.php',
        'tc_cleverreach_collection_filter'          => 'tc_cleverreach/core/tc_cleverreach_collection_filter.php',
        'tc_cleverreach_csv'                        => 'tc_cleverreach/core/tc_cleverreach_csv.php',
        'tc_cleverreach_rest_api'                   => 'tc_cleverreach/core/tc_cleverreach_rest_api.php',
        'tc_cleverreach_tldmapping'                 => 'tc_cleverreach/core/tc_cleverreach_tldmapping.php',
        'tc_cleverreach_transfer'                   => 'tc_cleverreach/core/tc_cleverreach_transfer.php',
        'tc_cleverreach_modulehandler'              => 'tc_cleverreach/core/tc_cleverreach_modulehandler.php',
        'tc_cleverreach_prodsearch_controller'      => 'tc_cleverreach/controllers/tc_cleverreach_prodsearch_controller.php',
        'tc_cleverreach_prodsearch_form'            => 'tc_cleverreach/core/prodsearch/form.php',
        'tc_cleverreach_prodsearch_form_input'      => 'tc_cleverreach/core/prodsearch/form_input.php',
        'tc_cleverreach_prodsearch_form_dropdown'   => 'tc_cleverreach/core/prodsearch/form_dropdown.php',
        'tc_cleverreach_prodsearch_handler'         => 'tc_cleverreach/core/prodsearch/handler.php',
        'tc_cleverreach_prodsearch_finder'          => 'tc_cleverreach/core/prodsearch/finder.php',
        'tc_cleverreach_prodsearch_finder_oxsearch' => 'tc_cleverreach/core/prodsearch/finder_oxsearch.php',
        'tc_cleverreach_prodsearch_result'          => 'tc_cleverreach/core/prodsearch/result.php',

        //models
        'tc_cleverreach_prodsearch'                 => 'tc_cleverreach/models/prodsearch.php',

        // Exception
        'tc_cleverreach_exception'                  => 'tc_cleverreach/core/exception/tc_cleverreach_exception.php',
        'tc_group_not_found_exception'              => 'tc_cleverreach/core/exception/tc_group_not_found_exception.php',

        //oauth
        'tc_cleverreach_oauth'                      => 'tc_cleverreach/controllers/tc_cleverreach_oauth.php',
    ),

    'templates' => array(
        // Admin
        'tc_cleverreach_config.tpl'          => 'tc_cleverreach/views/admin/tpl/tc_cleverreach_config.tpl',
        'tc_cleverreach_transfer_manual.tpl' => 'tc_cleverreach/views/admin/tpl/tc_cleverreach_transfer_manual.tpl',
        'tc_cleverreach_frames.tpl'          => 'tc_cleverreach/views/admin/tpl/tc_cleverreach_frames.tpl',
        'tc_cleverreach_list.tpl'            => 'tc_cleverreach/views/admin/tpl/tc_cleverreach_list.tpl',
        'tc_cleverreach_manual_csv.tpl'      => 'tc_cleverreach/views/admin/tpl/tc_cleverreach_manual_csv.tpl',
        'tc_cleverreach_oauth.tpl'           => 'tc_cleverreach/views/admin/tpl/tc_cleverreach_oauth.tpl',
    ),

    'events' => array(
        'onActivate'   => 'tc_cleverreach_modulehandler::onActivate',
        'onDeactivate' => 'tc_cleverreach_modulehandler::onDeactivate',
    ),

);
