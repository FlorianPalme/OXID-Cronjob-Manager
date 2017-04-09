<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_List extends oxAdminList
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'fpocm_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'fpocm_cronjob';

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'fpocm_cronjoblist';
}