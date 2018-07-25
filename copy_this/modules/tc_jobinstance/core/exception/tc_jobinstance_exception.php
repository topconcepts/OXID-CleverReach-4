<?php
/**
 * Klasse für die Exceptions von tc_jobinstance.
 */
class tc_jobinstance_exception extends Exception {
    /**
     * Fehlercode wenn das Programm bereits läuft.
     * 
     * @const   integer
     */
    const ERROR_ALREADY_RUNNING = 20;

    /**
     * Fehlercode wenn das Programm nicht läuft und die PID Datei nicht entfernt
     * werden kann.
     * 
     * @const   integer
     */
    const ERROR_CAN_NOT_START = 10;

    /**
     * Fehlercode wenn das Programm die maximale Zeit überschritten hat.
     * 
     * @const   integer
     */
    const ERROR_MAX_EXECUTION_REACHED = 30;
}
