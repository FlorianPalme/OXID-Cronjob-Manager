<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Core\Module;

class Module extends Module_parent
{
    /**
     * Gibt eine Liste der Cronjobs für das Modul zurück
     *
     * @return array
     */
    public function getCronjobs()
    {
        return isset($this->_aModule['cronjobs']) ? $this->_aModule['cronjobs'] : [];
    }
}