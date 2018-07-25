<?php

$sLangName = "English";
$iLangNr   = 1;

// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------

$aLang = array(
    'charset'                                     => 'UTF-8',

    // Menü
    'TC_CLEVERREACH'                              => 'CleverReach®',
    'TC_CLEVERREACH_CONFIG'                       => 'Configuration',

    // Überschriften
    'TC_CLEVERREACH_CSV'                          => 'CSV',
    'TC_CLEVERREACH_START'                        => 'CleverReach® Start',
    'TC_CELVERREACH_WELCOME'                      => 'Welcome to top concepts CleverReach® Module',

    // Texte
    'TC_CLEVERREACH_ACCOUNT_ID'                   => 'Account ID.',
    'TC_CLEVERREACH_API_KEY'                      => 'API Key',
    'TC_CLEVERREACH_LOGIN'                        => 'Login',
    'TC_CLEVERREACH_PASSWORD'                     => 'Password',
    'TC_CLEVERREACH_OAUTH_APP_CLIENT_ID'          => 'OAuth App Client Id',
    'TC_CLEVERREACH_OAUTH_APP_CLIENT_SECRET'      => 'OAuth App Client Secret',
    'TC_CLEVERREACH_LIST_ID'                      => 'List ID',
    'TC_CLEVERREACH_TRACKING'                     => 'Conversion Tracking',
    'TC_CLEVERREACH_SUBMIT'                       => 'Save',
    'TC_CLEVERREACH_LAST_TRANSFER'                => 'Last data transfer',
    'TC_CLEVERREACH_LETS_GET_STARTED'             => 'Let\'s get started',
    'TC_CLEVERREACH_LETS_GET_STARTED_HELPER_TEXT' => 'Well done - you are now connected! Now it\'s time to import your users into CleverReach®.',

    'TC_CLEVERREACH_OAUTH_NEEDED_BUTTON' => 'Connect now',
    'TC_CLEVERREACH_OAUTH_TITLE'         => 'Authentication',
    'TC_CLEVERREACH_OAUTH_DONE'          => 'Status: Connected',
    'TC_CLEVERREACH_OAUTH_DISC'          => 'Status: Disconnected',
    'TC_CLEVERREACH_OAUTH_HINT'          => 'This will open a new window with CleverReach® login form.',
    'TC_CLEVERREACH_OAUTH_NEEDED_HINT'   => 'Authentication needed. Please connect to CleverReach® on the "CleverReach® Start" tab.',
    'TC_CLEVERREACH_OAUTH_RESET'         => 'Reset authentication with CleverReach®.',
    'TC_CLEVERREACH_CONFIG_DATA'         => 'Module config data',
    'TC_CLEVERREACH_OAUTH_RELOAD'        => 'Click to refresh and finish authorizing',

    'TC_CLEVERREACH_START_TRANSFER'     => 'Start Manual data transfer',
    'TC_CLEVERREACH_START_TRANSFER_CSV' => 'Start Manual CSV file generation',
    'TC_CLEVERREACH_START_RESET'        => 'Data reset',
    'TC_CLEVERREACH_SELECT_LIST'        => 'Select',
    'TC_CLEVERREACH_LIST'               => 'List',
    'TC_CLEVERREACH_CURRENT'            => 'Current recipient list',
    'TC_CLEVERREACH_EXISTING_LIST'      => 'or choose an',
    'TC_CLEVERREACH_EXISTING_ONE'       => 'existing one',


    'TC_CLEVERREACH_TRANSFER_INFO'                  => 'You can transfer the data using the cronjob or manually through the browser to CleverReach®. ',
    'TC_CLEVERREACH_TRANSFER_INFO_CONFIG_CRON'      => 'To configure the cronjobs use these files:',
    'TC_CLEVERREACH_TRANSFER_INFO_MANUELL_TRANSFER' => 'To Start the manual transfer click the button below.',
    'TC_CLEVERREACH_CRON_RECIPIENTS' => 'export recipients only:',
    'TC_CLEVERREACH_CRON_ORDER' => 'include order data:',
    'TC_CLEVERREACH_CRON_TRANSFER' => 'Cronjob to transfer pending unsubscriptions from OXID to CleverReach®:',
    'TC_CLEVERREACH_CSV_INFO'                  => 'You can save your customer data to a CSV file. We advise to use the CSV option for the initial import in CleverReach®.<br>
                                                    To create the CSV file click the button below or execute the following PHP file.',
    'TC_CLEVERREACH_CSV_CRON'                  => 'Generation of CSV file over shell:',
    'TC_CLEVERREACH_CSV_INFO_MANUELL_TRANSFER' => 'Export-Path for CSV-Files:',

    'TC_CLEVERREACH_RESET_INFO' => 'Reset all transmission data',


    'TC_CLEVERREACH_SEND_USER'                  => 'Transfer customer data to CleverReach®. To be Transferred:',
    'TC_CLEVERREACH_SEND_ORDER'                 => 'Transfer order data to CleverReach®. To be Transferred:',
    'TC_CLEVERREACH_TRANSFER_COMPLETE'          => 'Transfer finished.',
    'TC_CLEVERREACH_RESET_COMPLETE'             => 'Reset finished.',
    'TC_CLEVERREACH_WITH_ORDERS'                => 'Add order data',
    'TC_CLEVERREACH_FULL_EXPORT'                => 'Export all newsletter recipients',

    // Fehler
    'TC_CLEVERREACH_ERROR_NO_KEY'               => 'API Key or List ID is missing.',
    'TC_CLEVERREACH_ERROR_NO_PATH'              => 'Not able to create dir %s. Make sure to create it, if it does not exist.',
    'TC_CLEVERREACH_ERROR_NO_PATH2'             => 'Verzeichnis existiert nicht oder besitzt keine Schreibrechte',

    // product search

    'TC_CLEVERREACH_PRODSEARCH_EXISTS'          => 'Error: Search with this name/url does already exist',
    'TC_CLEVERREACH_PRODSEARCH_SUCCESS'         => 'Product search %s was successfully installed in CR',

    // HELP
    'TC_CLEVERREACH_HELP_FULL_EXPORT'           => 'Exports all customers. If there\'s allready an existing csv file, it will get completely overriden.
This settings applies only to the manual export which is triggered by the button "<b>Start Manual CSV file generation</b>"',
    'TC_CLEVERREACH_HELP_EXPORTPATH'            => "
Set your export path here. <br>
This path is relative to the shop root directory \n <br>
If you would, for instance, use 'tc_export/' as dir, <br>
the generation path for CleverReach® would be YOURSHOPDIR/tc_export/. <br>
If you want to use a destination outside of your shop dir <br>
you can use '/../' to go a level up in your directory tree. <br>
(e.g. 'SHOPPATH/../../tc_export/' <br><br>
Please make sure that the folder exists and has sufficient writing permissions. <br>
",
    'TC_CLEVERREACH_HELP_ORDER'         => '
<b>Checked</b> - Includes orders in the export data.</br>
<b>Unchecked</b> - Export only users, without their orders.</br>
',
    'TC_CLEVERREACH_HELP_OPTIN' => '
<b>Checked</b> - Transfers only double opt-in users </br>
<b>Not checked </b> - Transfers every user</br>
Customers with newsletter subscription, who still didn\'t confirm the newsletter <br>
are nevertheless marked as <b style="color:green;">active</b>.
',

    'TC_CLEVERREACH_ERROR_PATHINVALID' => 'The Path %s is invalid, choose another one.',

    'TC_CLEVERREACH_GET_STARTED'             => 'Get started with CleverReach® Email Marketing!',
    'TC_CLEVERREACH_GET_STARTED_HELPER_TEXT' => 'Simply click the "Connect now" button and see how magic happens ;)',
    'TC_CLEVERREACH_CREATE_LIST'             => 'Create list',
    'TC_CLEVERREACH_CREATE_LIST_HELPER_TEXT' => 'Click to create a list in your CleverReach® account. All the shop customers you transfer using this module will end up in that list.',
    'TC_CLEVERREACH_GROUP_NAME'              => 'Name:',
    'TC_CLEVERREACH_NOT_YET'                 => 'Not yet',

    'TC_CLEVERREACH_ARE_YOU_SURE_RESET'                 => 'Are you sure you want to reset the export data?',
    'TC_CLEVERREACH_LIST_NOT_FOUND' => 'List not found in CleverReach®. Reload the page to create a new list.',
    'TC_CLEVERREACH_TOKEN_NOT_VALID' => 'OAuth access token not valid. Authenticate again.'
);
