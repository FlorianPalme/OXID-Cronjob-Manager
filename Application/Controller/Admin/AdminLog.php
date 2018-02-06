<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Controller\Admin;

use FlorianPalme\OXIDCronjobManager\Application\Models\Cronjob;
use FlorianPalme\OXIDCronjobManager\Application\Models\LogList;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\DatabaseProvider;

class AdminLog extends AdminDetailsController
{
    /**
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();

        /** @var Cronjob $oCronjob */
        $oCronjob = oxNew( Cronjob::class );
        $oCronjob->load( $soxId );

        $this->_aViewData["edit"] = $oCronjob;


        /** @var LogList $oLogList */
        $oLogList = oxNew( LogList::class );
        $oLogList->loadForCronjob( $oCronjob->getId() );

        $this->_aViewData["mylist"]  = $oLogList;

        return "fpocm_adminlog.tpl";
    }


    /**
     * Löscht den Log des Cronjobs
     */
    public function clearLog()
    {
        $soxId = $this->getEditObjectId();
        $oDb = DatabaseProvider::getDb();
        $oDb->execute("DELETE FROM fpocmlog WHERE oxcronjobid = '$soxId'");
    }
}