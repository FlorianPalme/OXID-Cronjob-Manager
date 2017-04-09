<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_AdminLog extends oxAdminDetails
{
    /**
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();

        /** @var fpOCM_Cronjob $oCronjob */
        $oCronjob = oxNew( 'fpOCM_Cronjob' );
        $oCronjob->load( $soxId );

        $this->_aViewData["edit"] = $oCronjob;


        /** @var fpOCM_LogList $oLogList */
        $oLogList = oxNew( 'fpOCM_LogList' );
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
        $oDb = oxDb::getDb();
        $oDb->execute("DELETE FROM fpocmlog WHERE oxcronjobid = '$soxId'");
    }
}