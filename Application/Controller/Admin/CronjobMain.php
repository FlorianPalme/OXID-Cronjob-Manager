<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Controller\Admin;

use FlorianPalme\OXIDCronjobManager\Application\Models\Cronjob;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class CronjobMain extends AdminDetailsController
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
        $aParams = Registry::get(Request::class)->getRequestParameter("editval");

        /** @var Cronjob $oCronjob */
        $oCronjob = oxNew( Cronjob::class );

        if( $soxId != '-1' ){
            $oCronjob->load( $soxId );
        }

        $oCronjob->assign($aParams);
        $oCronjob->save();

        // set oxid if inserted
        $this->setEditObjectId($oCronjob->getId());
    }
}