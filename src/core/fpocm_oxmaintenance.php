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
            if( ! $oCronjob->getModule() ) continue;

            $oScheduler->call( [ $this, $oCronjob->getFnc() ] )
                ->at( $oCronjob->fpocmcronjobs__oxcrontab->value )
                ->when( function() use( $oCronjob, $oMaintenance ) {
                    // Prüfen, ob Cronjob aktiv ist
                    if( $oCronjob->fpocmcronjobs__oxstatus->value == 'active' ){
                        // Prüfen ob Modul Aktiv und Funktion vorhanden
                        if( $oCronjob->getModule()->isActive() && method_exists( $oMaintenance, $oCronjob->getFnc() ) ){
                            return true;
                        }
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
     * Test-Cronjob
     */
    public function doMyJobj()
    {
        sleep(5);
        return "Some Job";
    }
}