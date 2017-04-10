<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_Admin extends oxAdminView
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'fpocm_admin.tpl';


    /**
     * fpOCM_Admin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Neu einlesen aller vorhandenen Cronjobs
        $sModulesDir = $this->getConfig()->getModulesDir();

        /** @var oxModuleList $oModuleList */
        $oModuleList = oxNew( 'oxModuleList' );


        /** @var oxModule|fpOCM_oxModule $oModule */
        foreach( $oModuleList->getModulesFromDir( $sModulesDir ) as $oModule ) {
            if( ! count( $oModule->getCronjobs() ) ) continue;

            foreach( $oModule->getCronjobs() as $sCronjobId => $aCronjobConfig ){
                if( ! isset( $aCronjobConfig[ 'crontab' ] ) ) continue;

                // Prüfen, ob der Cronjob bereits in der Datenbank ist
                /** @var fpOCM_Cronjob $oCronjob */
                $oCronjob = oxNew( 'fpOCM_Cronjob' );
                if( ! $oCronjob->loadByCronjobId( $sCronjobId, $oModule->getId() ) ){
                    $oCronjob->assign([
                        'oxstatus' => 'active',
                        'oxcronjobid' => $sCronjobId,
                        'oxcrontab' => $aCronjobConfig[ 'crontab' ],
                        'oxmoduleid' => $oModule->getId(),
                    ]);
                    $oCronjob->save();
                }
            }
        }
    }
}