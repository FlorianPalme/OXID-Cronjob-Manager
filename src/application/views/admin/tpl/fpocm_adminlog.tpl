[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]




<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{$oxid }]">
    <input type="hidden" name="cl" value="fpocm_adminlog">
    <input type="hidden" name="editlanguage" value="[{$editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink() }]" method="post" onsubmit="return confirm('[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_CLEARLOG_AREYOUSURE"}]');">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="fpocm_adminlog">
    <input type="hidden" name="fnc" value="clearLog">
    <input type="hidden" name="oxid" value="[{$oxid }]">
    <input type="hidden" name="editval[fpocmcronjobs__oxid]" value="[{$oxid }]">

    <table cellspacing="0" cellpadding="0" border="0" width="98%">
        <colgroup>
            <col width="30%">
            [{if $mylist->count()}]
            <col width="30%">
            <col width="30%">
            <col width="10%">
            [{/if}]
        </colgroup>
        [{assign var="oStatistics" value=$edit->getStatistics()}]
        <tr>
            <td valign="top" class="edittext">
                <b>[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_ITEMS" suffix="COLON" }]</b> [{$oStatistics->count}]
            </td>
            [{if $mylist->count()}]
            <td valign="top" class="edittext">
                <b>[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_ABORTED" suffix="COLON" }]</b> [{$oStatistics->aborted}]
            </td>
            <td valign="top" class="edittext">
                <b>[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_AVERAGEDURATION" suffix="COLON" }]</b> [{$oStatistics->averageDuration|date_format:'%M:%S'}] [{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_TIME_M"}]
            </td>
            <td valign="top" class="edittext">
                <button type="submit">[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_CLEARLOG"}]</button>
            </td>
            [{/if}]
        </tr>
        <tr>
            <td colspan="[{if $mylist->count()}]4[{else}]1[{/if}]">&nbsp;</td>
        </tr>
    </table>
</form>

[{if $mylist->count()}]
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <colgroup>
        [{block name="admin_fpocm_adminlog_list_colgroup"}]
            <col width="15%">
            <col width="15%">
            <col width="15%">
            <col width="12%">
            <col width="42%">
            <col width="1%">
        [{/block}]
    </colgroup>
    <tr class="listitem">
        [{block name="admin_fpocm_adminlog_list_sorting"}]
            <td class="listheader first" height="15">[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_LIST_STARTTIME" }]</td>
            <td class="listheader">[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_LIST_ENDTIME" }]</td>
            <td class="listheader">[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_LIST_DURATION" }]</td>
            <td class="listheader">[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_LIST_STATE" }]</td>
            <td class="listheader">[{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_LIST_EXCEPTION" }]</td>
            <td class="listheader">&nbsp;</td>
        [{/block}]
    </tr>

    [{assign var="blWhite" value=""}]
    [{assign var="_cnt" value=0}]
    [{foreach from=$mylist item=listitem}]
        [{assign var="_cnt" value=$_cnt+1}]
        <tr id="row.[{$_cnt}]">

            [{block name="admin_fpocm_adminlog_list_item"}]
                [{if $listitem->blacklist == 1}]
                    [{assign var="listclass" value=listitem3 }]
                [{else}]
                    [{assign var="listclass" value=listitem|cat:$blWhite }]
                [{/if}]

                [{if $listitem->fpocmcronjobs__oxid->value == $oxid }]
                    [{assign var="listclass" value=listitem4 }]
                [{/if}]

                <td valign="top" class="[{$listclass}]">
                    <div class="listitemfloating">
                        [{$listitem->fpocmlog__oxstarttime->value|date_format:'%d.%m.%Y %H:%M:%S'}]
                    </div>
                </td>
                <td valign="top" class="[{$listclass}]">
                    <div class="listitemfloating">
                        [{$listitem->fpocmlog__oxendtime->value|date_format:'%d.%m.%Y %H:%M:%S'}]
                    </div>
                </td>
                <td valign="top" class="[{$listclass}]">
                    <div class="listitemfloating">
                        [{math assign="fDuration" equation="x - y" x=$listitem->fpocmlog__oxendtime->value y=$listitem->fpocmlog__oxstarttime->value}]
                        [{$fDuration|date_format:'%M:%S'}] [{oxmultilang ident="FPOCM_ADMIN_ADMINLOG_TIME_M"}]
                    </div>
                </td>
                <td valign="top" class="[{$listclass}]">
                    <div class="listitemfloating">
                        <span class="fpocmlogstate [{$listitem->fpocmlog__oxstate->value}]">
                            [{"FPOCM_ADMIN_ADMINLOG_LIST_STATE_"|cat:$listitem->fpocmlog__oxstate->value|upper|oxmultilangassign}]
                        </span>
                    </div>
                </td>
                <td valign="top" class="[{$listclass}]">
                    <div class="listitemfloating">
                        [{$listitem->fpocmlog__oxexception->value}]
                    </div>
                </td>
                <td class="[{$listclass}]">&nbsp;</td>
            [{/block}]
        </tr>
        [{if $blWhite == "2"}]
            [{assign var="blWhite" value=""}]
        [{else}]
            [{assign var="blWhite" value="2"}]
        [{/if}]
    [{/foreach}]
</table>
[{/if}]



[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]