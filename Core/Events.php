<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 * @see http://wiki.oxidforge.org/Features/Extension_metadata_file#events
 */
namespace FlorianPalme\OXIDCronjobManager\Core;

use OxidEsales\Eshop\Core\ConfigFile;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\FileCache;
use OxidEsales\Eshop\Core\Registry;

class Events
{
    /**
     * Führt bei Modul-aktivierung einige SQL-Befehle aus
     *
     * @access  public
     */
    public static function onActivate()
    {
        // Datenbank-Objekt auslesen
        $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        $aSql = [];
        $aSql[] = <<<SQL
CREATE TABLE `fpocmcronjobs` (
  `OXID` char(32) COLLATE latin1_general_ci NOT NULL,
  `OXSTATUS` enum('active','paused','aborted') COLLATE latin1_general_ci DEFAULT NULL,
  `OXCRONJOBID` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `OXCRONTAB` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  `OXTIMESTAMP` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `OXMODULEID` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`OXID`),
  UNIQUE KEY `fpocmcronjobs_OXCRONJOBID_uindex` (`OXCRONJOBID`),
  KEY `fpocmcronjobs_OXMODULEID_index` (`OXMODULEID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Cronjob Manager Config Table'
SQL;

        $aSql[] = <<<SQL
CREATE TABLE `fpocmlog` (
  `OXID` char(32) COLLATE latin1_general_ci NOT NULL,
  `OXCRONJOBID` char(32) COLLATE latin1_general_ci NOT NULL,
  `OXSTARTTIME` decimal(16,6) DEFAULT NULL,
  `OXENDTIME` decimal(16,6) DEFAULT NULL,
  `OXSTATE` enum('running','finished','aborted') COLLATE latin1_general_ci NOT NULL,
  `OXEXCEPTION` text COLLATE latin1_general_ci,
  `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OXID`),
  KEY `fpocmlog_OXCRONJOBID_index` (`OXCRONJOBID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Log-Table for Cronjob-Execution'
SQL;

        foreach ($aSql as $sSql) {
            // MySQL-Fehler abfangen
            try {
                $blResult = $oDb->execute($sSql);
            } catch (DatabaseErrorException $e) {
                // Ausser es sind keine "alread exists" fehler...
                if (!preg_match('/(already exists|Duplicate column name)/i', $e->getMessage())) {
                    throw $e;
                }
            }
        }

        self::clearCache();
    }


    /**
     * Bei Modul-Deaktivierung
     */
    public static function onDeactivate()
    {
        self::clearCache();
    }


    /**
     * Löscht den TMP-Ordner sowie den Smarty-Ordner
     */
    protected static function clearCache()
    {
        /** @var FileCache $fileCache */
        $fileCache = oxNew(FileCache::class);
        $fileCache::clearCache();

        /** Smarty leeren */
        $tempDirectory = Registry::get(ConfigFile::class)->getVar("sCompileDir");
        $mask = $tempDirectory . "/smarty/*.php";
        $files = glob($mask);
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }
}
