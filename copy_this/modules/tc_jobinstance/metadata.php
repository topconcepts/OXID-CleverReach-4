<?php

$sMetadataVersion = '1.0';

$aModule = array(
    'id'           => 'tc_jobinstance',
    'title'        => 'tc jobinstance',
    'description'  => 'Mit tc_jobinstance können Sie sicherstellen, wie viele Instanzen eines Cronjobs
                       gleichzeitig laufen dürfen und wie lange diese Instanzen laufen dürfen, bevor
                       sie über ein exec("kill") gestoppt werden.',
    'thumbnail'    => 'tc_logo.jpg',
    'version'      => '1.0.1',
    'author'       => 'top concepts',
    'email'        => 'support@topconcepts.de',
    'url'          => 'https://www.topconcepts.de',

    'extend'       => array(
    ),

    'files'        => array(
        'tc_jobinstance_pid'       => 'tc_jobinstance/core/tc_jobinstance_pid.php',
        'tc_jobinstance'           => 'tc_jobinstance/core/tc_jobinstance.php',
        'tc_jobinstance_exception' => 'tc_jobinstance/core/exception/tc_jobinstance_exception.php',
    ),

);
