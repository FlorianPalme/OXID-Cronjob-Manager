<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 * @see http://wiki.oxidforge.org/Features/Extension_metadata_file#events
 */
class fpOCM_Events
{
    /**
     * Führt bei Modul-aktivierung einige SQL-Befehle aus
     *
     * @access  public
     */
    public static function onActivate()
    {
        // Datenbank-Objekt auslesen
        /** @var oxLegacyDb $oDb */
        $oDb = oxRegistry::get('oxDb')->getDb(oxDb::FETCH_MODE_ASSOC);

        $aSql = [];
        $aSql[] = <<<SQL
CREATE TABLE `fpocmcronjobs` (
  `OXID` char(32) COLLATE latin1_general_ci NOT NULL,
  `OXSTATUS` enum('active','paused','aborted') COLLATE latin1_general_ci DEFAULT NULL,
  `OXCRONJOBID` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `OXCRONTAB` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  `OXTIMESTAMP` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OXID`),
  UNIQUE KEY `fpocmcronjobs_OXCRONJOBID_uindex` (`OXCRONJOBID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Cronjob Manager Config Table'
SQL;

        foreach ($aSql as $sSql) {
            // MySQL-Fehler abfangen
            try {
                $blResult = $oDb->execute($sSql);
            } catch (oxAdoDbException $e) {
                // Ausser es sind keine "alread exists" fehler...
                if (!preg_match('/(aready exists|Duplicate column name)/i', $e->getMessage())) {
                    throw $e;
                }
            }
        }
    }
}
