<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_Main extends oxAdminDetails
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

        return "fpocm_main.tpl";
    }


    /**
     * Saves Cronjob
     *
     * @return mixed
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        /** @var fpOCM_Cronjob $oCronjob */
        $oCronjob = oxNew( 'fpOCM_Cronjob' );

        if( $soxId != '-1' ){
            $oCronjob->load( $soxId );
        }

        $oCronjob->assign($aParams);
        $oCronjob->save();

        // set oxid if inserted
        $this->setEditObjectId($oCronjob->getId());
    }
}