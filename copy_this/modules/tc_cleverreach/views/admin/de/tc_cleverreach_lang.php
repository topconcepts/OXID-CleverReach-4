<?php

$sLangName = "Deutsch";
$iLangNr   = 0;

// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------

$aLang = array(
    'charset'                                     => 'UTF-8',

    // Menü
    'TC_CLEVERREACH'                              => 'CleverReach®',
    'TC_CLEVERREACH_CONFIG'                       => 'Einstellungen',

    // Überschriften
    'TC_CLEVERREACH_TRANSFER'                     => 'Kundendaten übertragen',
    'TC_CLEVERREACH_CSV'                          => 'CSV',
    'TC_CLEVERREACH_START'                        => 'CleverReach® Start',
    'TC_CELVERREACH_WELCOME'                      => 'Willkommen im CleverReach® Modul von top concepts',

    // Texte
    'TC_CLEVERREACH_ACCOUNT_ID'                   => 'Account Nr.',
    'TC_CLEVERREACH_API_KEY'                      => 'API Key',
    'TC_CLEVERREACH_LOGIN'                        => 'Login',
    'TC_CLEVERREACH_PASSWORD'                     => 'Password',
    'TC_CLEVERREACH_OAUTH_APP_CLIENT_ID'          => 'OAuth App Client Id',
    'TC_CLEVERREACH_OAUTH_APP_CLIENT_SECRET'      => 'OAuth App Client Secret',
    'TC_CLEVERREACH_LIST_ID'                      => 'Listen ID',
    'TC_CLEVERREACH_TRACKING'                     => 'Conversion Tracking',
    'TC_CLEVERREACH_SUBMIT'                       => 'Speichern',
    'TC_CLEVERREACH_LAST_TRANSFER'                => 'Letzte Datenübertragung',
    'TC_CLEVERREACH_LETS_GET_STARTED'             => 'Los geht\'s',
    'TC_CLEVERREACH_LETS_GET_STARTED_HELPER_TEXT' => 'Fertig - der Shop ist nun mit Ihrem CleverReach® verbunden! Jetzt müssen die Kundendaten in Ihren CleverReach® Account importiert werden.',//fixme:translate

    'TC_CLEVERREACH_OAUTH_NEEDED_BUTTON' => 'Jetzt Verbinden',
    'TC_CLEVERREACH_OAUTH_TITLE'         => 'Authentifizierung',
    'TC_CLEVERREACH_OAUTH_DONE'          => 'Status: Verbunden',
    'TC_CLEVERREACH_OAUTH_DISC'          => 'Status: Nicht verbunden',
    'TC_CLEVERREACH_OAUTH_HINT'          => 'Es wird ein Popup-Fenster mit dem CleverReach® Login Formular geöffnet.',//fixme:translate
    'TC_CLEVERREACH_OAUTH_NEEDED_HINT'   => 'Authentifizierung erforderlich. Bitte verbinden Sie den Shop mit CleverReach® im "CleverReach® Start"-Tab.',//fixme:translate
    'TC_CLEVERREACH_OAUTH_RESET'         => 'CleverReach® Authentifizierung entfernen.',
    'TC_CLEVERREACH_CONFIG_DATA'         => 'Modul Konfigurations Daten',
    'TC_CLEVERREACH_OAUTH_RELOAD'        => 'Klicken Sie hier, um die Seite neu zu laden und die Authentifizierung abzuschließen.',//fixme:translate


    'TC_CLEVERREACH_START_TRANSFER'     => 'Manuelle Datenübertragung starten',
    'TC_CLEVERREACH_START_TRANSFER_CSV' => 'CSV Datei erstellen',
    'TC_CLEVERREACH_START_RESET'        => 'übertragungsdaten zurücksetzten',
    'TC_CLEVERREACH_SELECT_LIST'        => 'Auswählen',
    'TC_CLEVERREACH_LIST'               => 'Liste',
    'TC_CLEVERREACH_CURRENT'            => 'Aktuelle Empfängerliste',
    'TC_CLEVERREACH_EXISTING_LIST'      => 'oder wähle eine bestehende',
    'TC_CLEVERREACH_EXISTING_ONE'       => 'Liste aus',


    'TC_CLEVERREACH_TRANSFER_INFO'                  => 'Sie haben die Möglichkeit, ihre Kundendaten per Cronjob oder manuell über den Browser an CleverReach® zu übertragen.',
    'TC_CLEVERREACH_TRANSFER_INFO_CONFIG_CRON'      => 'Folgende Dateien benötigen Sie für die Konfiguration der Cronjobs:',
    'TC_CLEVERREACH_TRANSFER_INFO_MANUELL_TRANSFER' => 'Klicken Sie den nachstehenden Button, um den manuellen Transfer zu starten.',

    'TC_CLEVERREACH_CSV_INFO' => 'Sie haben die Möglichkeit, Ihre Kundendaten in einer CSV Datei zu speichern. Dies ist besonders vor der ersten Übertragung zu empfehlen.<br>
                                                    Um eine CSV Datei zu erstellen, können Sie den nachfolgenden Button klicken oder eine PHP Datei ausführen.',

    'TC_CLEVERREACH_CSV_CRON'                  => 'Generieren der CSV-Datei über die Shell mit folgendem Befehl:',
    'TC_CLEVERREACH_CSV_INFO_MANUELL_TRANSFER' => 'Export-Pfad für CSV-Dateien:',
    'TC_CLEVERREACH_CRON_RECIPIENTS' => 'Nur E-Mail-Adressen exportieren:',
    'TC_CLEVERREACH_CRON_ORDER' => 'E-Mail-Adressen und Bestellungen exportieren:',
    'TC_CLEVERREACH_CRON_TRANSFER' => 'Cronjob um unerledigte Abmeldungen von OXID an CleverReach® zu übertragen:',

    'TC_CLEVERREACH_RESET_INFO' => 'Durch einen Klick auf den unteren Button werden alle Kunden als noch nicht übertragen gekennzeichtnet.<br>
                                                    Beim nachsten Export werden dann alle ausgewählten Kunden übertragen.',

    'TC_CLEVERREACH_SEND_USER'         => 'Sende Kunden an Cleverreach®. Noch zu übertragen: ',
    'TC_CLEVERREACH_SEND_ORDER'        => 'Sende Bestellungen an Cleverreach®. Noch zu übertragen:',
    'TC_CLEVERREACH_TRANSFER_COMPLETE' => 'Transfer beendet.',
    'TC_CLEVERREACH_RESET_COMPLETE'    => 'Reset beendet.',
    'TC_CLEVERREACH_WITH_ORDERS'       => 'Bestelldaten hinzufügen',
    'TC_CLEVERREACH_FULL_EXPORT'       => 'Alle Newsletter-Empfänger exportieren',

    // Fehler
    'TC_CLEVERREACH_ERROR_NO_KEY'      => 'API Key oder Listen ID fehlt.',
    'TC_CLEVERREACH_ERROR_NO_PATH'     => 'Konnte das Verzeichnis %s nicht erstellen. Stellen sie sicher dass es existiert.',
    'TC_CLEVERREACH_ERROR_NO_PATH2'    => 'Verzeichnis existiert nicht oder besitzt keine Schreibrechte',

    // product search

    'TC_CLEVERREACH_PRODSEARCH_EXISTS'  => 'Fehler: Suche existiert schon in CleverReach®. Bitte Name ändern',
    'TC_CLEVERREACH_PRODSEARCH_SUCCESS' => 'Produktsuche %s erfolgreich in CleverReach® erstellt',

    // HELP
    'TC_CLEVERREACH_HELP_FULL_EXPORT'   => 'Exportiert alle Kunden. Falls schon eine CSV-Datei existiert, wird diese komplett neu überschrieben.
Diese Einstellung bezieht sich auf den manuellen Export über den unteren Button "<b>CSV Datei erstellen</b>"',
    'TC_CLEVERREACH_HELP_EXPORTPATH'    => "
Geben sie hier den Export-Pfad an. <br>
Dieser ist relativ zum Shop-Hauptverzeichnis. <br>
Bspw. würde ein Pfad 'tc_export/' bedeuten, dass der Exporter <br>
die Dateien im Ordner IHR-SHOP-PFAD/tc_export/ generiert. <br>
Möchten Sie die Dateien in einem Ordner außerhalb ihres <br>
Shopverzeichnisses generieren lassen, <br>
nutzen sie bspw. /../ um im in der Ordnerstruktur nach oben zu wandern <br>
(Bspw. 'SHOPPATH/../../tc_export/' <br><br>
Achten Sie darauf, dass dieser Ordner existiert und genügend Schreibrechte besitzt.
",
    'TC_CLEVERREACH_HELP_ORDER'         => '
<b>Angehakt</b> - Überträgt zusätzlich Bestelldaten des Kunden</br>
<b>Nicht angehakt</b> - Überträgt keinerlei Bestelldaten</br>
',
    'TC_CLEVERREACH_ERROR_PATHINVALID'  => 'Der Pfad %s ist ungültig. Wählen Sie einen anderen.',

    'TC_CLEVERREACH_HELP_OPTIN2' => '
<b>Nur Double-Opt-in</b> - Überträgt nur double-optin Kunden </br>
<b>Alle Kunden</b> - Überträgt alle Kunden </br>
Kunden die den Newsletter registriert, aber nicht bestätigt haben, </br>
werden dann in CleverReach® trotzdem als <b style="color:green;">aktiv</b> gekennzeichnet. </br>
',

    'TC_CLEVERREACH_GET_STARTED'             => 'Starten Sie jetzt mit CleverReach® Email Marketing!',
    'TC_CLEVERREACH_GET_STARTED_HELPER_TEXT' => 'Einfach den "Jetzt Verbinden" Button klicken und schon kann es losgehen.',
    'TC_CLEVERREACH_CREATE_LIST'             => 'Empfänger-Gruppe anlegen',
    'TC_CLEVERREACH_CREATE_LIST_HELPER_TEXT' => 'Klicken Sie hier, um in Ihrem CleverReach® Account eine Empfänger-Gruppe anzulegen. Das CleverReach® Modul übertrag Nutzerdaten in diese Empfänger-Gruppe.',
    'TC_CLEVERREACH_GROUP_NAME'              => 'Name:',
    'TC_CLEVERREACH_NOT_YET'                 => 'Noch nie',

    'TC_CLEVERREACH_ARE_YOU_SURE_RESET' => 'Sind Sie sicher, dass Sie die bereits exportierten Daten zurücksetzen wollen?',
    'TC_CLEVERREACH_LIST_NOT_FOUND'     => 'List not found in CleverReach®. Reload the page to create a new list.',//todo:translate
    'TC_CLEVERREACH_TOKEN_NOT_VALID'    => 'oAuth Zugangs-Token ist ungültig. Bitte authentifizieren Sie sich erneut.',
);
