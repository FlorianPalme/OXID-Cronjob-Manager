<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_CronjobList extends oxList
{
    /**
     * Class constructor, initiates parent constructor (parent::oxList()).
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct('fpocm_cronjob');
    }
}