<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Controller\Admin;

use FlorianPalme\OXIDCronjobManager\Application\Models\Cronjob;
use FlorianPalme\OXIDCronjobManager\Core\Module\Module;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Core\Module\ModuleList;

class Admin extends AdminController
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

        /** @var ModuleList $oModuleList */
        $oModuleList = oxNew( ModuleList::class );


        /** @var Module $oModule */
        foreach( $oModuleList->getModulesFromDir( $sModulesDir ) as $oModule ) {
            if( ! count( $oModule->getCronjobs() ) ) continue;

            foreach( $oModule->getCronjobs() as $sCronjobId => $aCronjobConfig ){
                if( ! isset( $aCronjobConfig[ 'crontab' ] ) ) continue;

                // Prüfen, ob der Cronjob bereits in der Datenbank ist
                /** @var Cronjob $oCronjob */
                $oCronjob = oxNew( Cronjob::class );
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