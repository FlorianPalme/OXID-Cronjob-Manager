<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_oxModule extends fpOCM_oxModule_parent
{
    /**
     * Gibt eine Liste der Cronjobs für das Modul zurück
     *
     * @return array
     */
    public function getCronjobs()
    {
        return isset($this->_aModule['cronjobs']) ? $this->_aModule['cronjobs'] : array();
    }
}