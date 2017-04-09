<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_LogList extends oxList
{
    /**
     * Class constructor, initiates parent constructor (parent::oxList()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct('fpocm_log');
    }


    /**
     * Lädt die Logliste eines bestimmten Cronjobs
     *
     * @param string $sCronjobId
     * @return $this
     */
    public function loadForCronjob( $sCronjobId )
    {
        $oListObject = $this->getBaseObject();
        $sFieldList = $oListObject->getSelectFields();
        $sQ = "select $sFieldList from " . $oListObject->getViewName();

        if ($sActiveSnippet = $oListObject->getSqlActiveSnippet()) {
            $sQ .= " where $sActiveSnippet AND ";
        } else {
            $sQ .= " where ";
        }

        $sQ .= "OXCRONJOBID = '$sCronjobId' ORDER BY oxstarttime";

        $this->selectString($sQ);

        return $this;
    }
}