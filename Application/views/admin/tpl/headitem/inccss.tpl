[{$smarty.block.parent}]

[{if $oView->getClassName() == 'fpocm_list' || $oView->getClassName() == 'fpocm_adminlog'}]
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('fpOxidCronjobManager','out/admin/src/css/style.css')}]">
[{/if}]