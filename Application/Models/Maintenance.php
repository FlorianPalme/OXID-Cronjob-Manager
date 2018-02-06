<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */

namespace FlorianPalme\OXIDCronjobManager\Application\Models;

use FlorianPalme\OXIDCronjobManager\Core\Exception\Exception;
use GO\Scheduler;
use OxidEsales\Eshop\Core\Registry;

class Maintenance extends Maintenance_parent
{
    /**
     * Führt registrierte Cronjobs aus
     */
    public function execute()
    {
        parent::execute();

        $this->_handleOCMCronjobs();
    }


    /**
     * Handelt das Ausführen der Cronjobs
     */
    protected function _handleOCMCronjobs()
    {
        /** @var CronjobList $oCronjobList */
        $oCronjobList = oxNew( CronjobList::class );
        $oCronjobList->getList();

        if( ! $oCronjobList->count() ) return;

        // Log-Ordner anlegen
        $sLogDir = Registry::getConfig()->getLogsDir() . 'cron/';
        if( ! is_dir( $sLogDir ) ){
            mkdir( $sLogDir );
        }

        // PHP Cron Scheduler laden
        $oScheduler = new Scheduler([
            'tempDir' => Registry::getConfig()->getModulesDir() . 'fp/cronjobmanager/lockfiles/',
        ]);

        // Var speichern um in Anonymer Funktionv erwenden zu können
        $oMaintenance = $this;

        // Alle Jobs durchlaufen und Ausführung starten
        /** @var Cronjob $oCronjob */
        foreach( $oCronjobList as $oCronjob ){
            if( ! $oCronjob->getModule() ) {
                // Modul nicht mehr Verfügbar... also löschen wir den Cronjob
                $oCronjob->delete();

                continue;
            };

            $oScheduler->call( [ $this, 'runOCMCronjob' ], [ 'oCronjob' => $oCronjob ], 'cronjobmanager_' . $oCronjob->getId() )
                ->at( $oCronjob->fpocmcronjobs__oxcrontab->value )
                ->when( function() use( $oCronjob, $oMaintenance ) {
                    // Prüfen, ob Cronjob aktiv ist
                    if( $oCronjob->fpocmcronjobs__oxstatus->value == 'active' ){
                        // Prüfen ob Modul Aktiv und Funktion vorhanden
                        if( $oCronjob->getModule()->isActive() && method_exists( $oMaintenance, $oCronjob->getFnc() ) ){
                            return true;
                        }

                        // Cronjob deaktivieren
                        $oCronjob->assign([
                            'oxstatus' => 'aborted',
                        ]);
                        $oCronjob->save();
                    }

                    return false;
                })
                ->output(
                    $sLogDir . $oCronjob->fpocmcronjobs__oxmoduleid->value . '_' . $oCronjob->fpocmcronjobs__oxcronjobid . '.log'
                    , true
                );
        }

        // Cronjobs starten
        $oScheduler->run();
    }


    /**
     * Führt einen Cronjob aus
     *
     * @param Cronjob $cronjob
     * @return string
     * @throws \Exception
     */
    public function runOCMCronjob( Cronjob $cronjob )
    {
        // Log schreiben
        /** @var Log $oLog */
        $oLog = oxNew( Log::class );
        $oLog->assign([
            'oxcronjobid' => $cronjob->getId(),
            'oxstarttime' => microtime(true),
            'oxstate' => 'running',
        ]);

        $oLog->save();

        // Cronjob ausführen
        try {
            $sOutput = $this->{$cronjob->getFnc()}();

            $oLog->assign([
               'oxstate' => 'finished',
                'oxendtime' => microtime(true),
            ]);
            $oLog->save();

            return $sOutput;
        } catch( Exception $e ){
            $oLog->assign([
                'oxexception' => $e->getMessage(),
                'oxstate' => 'aborted',
                'oxendtime' => microtime(true),
            ]);
            $oLog->save();

            return '';
        } catch( \Exception $e ){
            $oLog->assign([
                'oxstate' => 'aborted',
                'oxendtime' => microtime(true),
            ]);
            $oLog->save();

            throw $e;
        }
    }
}