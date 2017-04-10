<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_oxMaintenance extends fpOCM_oxMaintenance_parent
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
        /** @var fpOCM_CronjobList $oCronjobList */
        $oCronjobList = oxNew( 'fpOCM_CronjobList' );
        $oCronjobList->getList();

        if( ! $oCronjobList->count() ) return;

        // Log-Ordner anlegen
        $sLogDir = oxRegistry::getConfig()->getLogsDir() . 'cron/';
        if( ! is_dir( $sLogDir ) ){
            mkdir( $sLogDir );
        }

        // PHP Cron Scheduler laden
        require_once( oxRegistry::getConfig()->getModulesDir() . 'fp/oxid-cronjob-manager/vendor/autoload.php' );
        $oScheduler = new GO\Scheduler([
            'tempDir' => oxRegistry::getConfig()->getModulesDir() . 'fp/oxid-cronjob-manager/lockfiles/',
        ]);

        // Var speichern um in Anonymer Funktionv erwenden zu können
        $oMaintenance = $this;

        // Alle Jobs durchlaufen und Ausführung starten
        /** @var fpOCM_Cronjob $oCronjob */
        foreach( $oCronjobList as $oCronjob ){
            if( ! $oCronjob->getModule() ) {
                // Modul nicht mehr Verfügbar... also löschen wir den Cronjob
                $oCronjob->delete();

                continue;
            };

            $oScheduler->call( [ $this, 'runOCMCronjob' ], [ 'oCronjob' => $oCronjob ] )
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
     * @param array $aArgs
     *
     * @throws Exception
     *
     * @return string
     */
    public function runOCMCronjob( array $aArgs )
    {
        /** @var fpOCM_Cronjob $oCronjob */
        $oCronjob = $aArgs[ 'oCronjob' ];

        // Log schreiben
        /** @var fpOCM_Log $oLog */
        $oLog = oxNew( 'fpOCM_Log' );
        $oLog->assign([
            'oxcronjobid' => $oCronjob->getId(),
            'oxstarttime' => microtime(true),
            'oxstate' => 'running',
        ]);

        $oLog->save();

        // Cronjob ausführen
        try {
            $sOutput = $this->{$oCronjob->getFnc()}();

            $oLog->assign([
               'oxstate' => 'finished',
                'oxendtime' => microtime(true),
            ]);
            $oLog->save();

            return $sOutput;
        } catch( fpOCM_Exception $e ){
            $oLog->assign([
                'oxexception' => $e->getMessage(),
                'oxstate' => 'aborted',
                'oxendtime' => microtime(true),
            ]);
            $oLog->save();

            return '';
        } catch( Exception $e ){
            $oLog->assign([
                'oxstate' => 'aborted',
                'oxendtime' => microtime(true),
            ]);
            $oLog->save();

            throw $e;
        }
    }
}