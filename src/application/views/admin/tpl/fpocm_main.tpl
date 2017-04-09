[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]




<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{$oxid }]">
    <input type="hidden" name="cl" value="fpocm_main">
    <input type="hidden" name="editlanguage" value="[{$editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="fpocm_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid }]">
    <input type="hidden" name="editval[fpocmcronjobs__oxid]" value="[{$oxid }]">

    <table cellspacing="0" cellpadding="0" border="0" width="98%">
        <colgroup><col width="30%"><col width="5%"><col width="65%"></colgroup>

            <tr>
                <td valign="top" class="edittext" width="200">
                    <table cellspacing="0" cellpadding="0" border="0">

                        [{block name="admin_fpocm_main_form"}]
                            <tr>
                                <td class="edittext" width="70">
                                    [{oxmultilang ident="FPOCM_ADMIN_MAIN_STATUS" }]
                                </td>
                                <td class="edittext">
                                    <select class="edittext" name="editval[fpocmcronjobs__oxstatus]" [{$readonly }]>
                                        <option value="active" [{if $edit->fpocmcronjobs__oxstatus->value == 'active'}]selected[{/if}]>[{oxmultilang ident="FPOCM_ADMIN_MAIN_STATUS_ACTIVE"}]</option>
                                        <option value="paused" [{if $edit->fpocmcronjobs__oxstatus->value == 'paused'}]selected[{/if}]>[{oxmultilang ident="FPOCM_ADMIN_MAIN_STATUS_PAUSED"}]</option>
                                        <option value="aborted" [{if $edit->fpocmcronjobs__oxstatus->value == 'aborted'}]selected[{/if}]>[{oxmultilang ident="FPOCM_ADMIN_MAIN_STATUS_ABORTED"}]</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr>
                                <td class="edittext">
                                    [{oxmultilang ident="FPOCM_ADMIN_MAIN_CRONTAB" }]
                                </td>
                                <td class="edittext">
                                    <input type="text" id="crontab" value="[{$edit->fpocmcronjobs__oxcrontab->value}]" name="editval[fpocmcronjobs__oxcrontab]" size="28" maxlength="[{$edit->oxcontents__oxtermversion->fldmax_length}]" [{$readonly}] />
                                </td>
                            </tr>
                        [{/block}]
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr>
                            <td class="edittext">
                            </td>
                            <td class="edittext">
                                <input type="submit" class="edittext" name="saveContent" value="[{oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'"" [{ $readonly }]><br>
                            </td>
                        </tr>
                    </table>
                </td>
                <!-- Ende rechte Seite -->
            </tr>
    </table>
</form>


[{*<script type="text/javascript">
    jQuery(function($){
       $('#crontab').crontabGenerator();
    });
</script>*}]




[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]