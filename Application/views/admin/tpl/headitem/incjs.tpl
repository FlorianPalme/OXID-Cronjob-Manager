[{$smarty.block.parent}]

[{if $oView->getClassName() == 'fpocm_main'}]
<script type="text/javascript" src="[{$oViewConf->getModuleUrl('fpOxidCronjobManager','out/admin/src/js/jquery-3.2.0.min.js')}]"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="[{$oViewConf->getModuleUrl('fpOxidCronjobManager','out/admin/src/js/crontabgenerator.js')}]"></script>
[{/if}]