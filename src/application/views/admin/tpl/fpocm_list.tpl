[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]
[{assign var="where" value=$oView->getListFilter()}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]


<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    [{if $updatelist == 1}]
        top.oxid.admin.updateList('[{$oxid }]');
    [{/if}]
}
//-->
</script>

<div id="liste">


    <form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">
        [{include file="_formparams.tpl" cl="fpocm_list" lstrt=$lstrt actedit=$actedit oxid=$oxid fnc="" language=$actlang editlanguage=$actlang}]
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <colgroup>
                [{block name="admin_fpocm_list_colgroup"}]
                    <col width="3%">
                    <col width="30%">
                    <col width="46%">
                    <col width="20%">
                    <col width="1%">
                [{/block}]
            </colgroup>
            <tr class="listitem">
                [{block name="admin_fpocm_list_sorting"}]
                    <td class="listheader first" height="15" width="30" align="center">[{ oxmultilang ident="FPOCM_ADMIN_LIST_ACTIVE" }]</td>
                    <td class="listheader">[{ oxmultilang ident="FPOCM_ADMIN_LIST_MODULETITLE" }]</td>
                    <td class="listheader">[{ oxmultilang ident="FPOCM_ADMIN_LIST_TITLE" }]</td>
                    <td class="listheader">[{ oxmultilang ident="FPOCM_ADMIN_LIST_CRONTAB" }]</td>
                    <td class="listheader">&nbsp;</td>
                [{/block}]
            </tr>

            [{assign var="blWhite" value=""}]
            [{assign var="_cnt" value=0}]
            [{foreach from=$mylist item=listitem}]
                [{assign var="_cnt" value=$_cnt+1}]
                <tr id="row.[{$_cnt}]">

                    [{block name="admin_fpocm_list_item"}]
                        [{if $listitem->blacklist == 1}]
                            [{assign var="listclass" value=listitem3 }]
                        [{else}]
                            [{assign var="listclass" value=listitem$blWhite }]
                        [{/if}]

                        [{if $listitem->fpocmcronjobs__oxid->value == $oxid }]
                            [{assign var="listclass" value=listitem4 }]
                        [{/if}]

                        <td valign="top" class="[{$listclass}][{if $listitem->fpocmcronjobs__oxstatus->value == 'active'}] active[{elseif $listitem->fpocmcronjobs__oxstatus->value == 'paused'}] paused[{/if}]" height="15"><div class="listitemfloating">&nbsp</a></div></td>
                        <td valign="top" class="[{$listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{$listitem->fpocmcronjobs__oxid->value}]');" class="[{$listclass}]">[{$listitem->getModuleTitle()}]</a></div></td>
                        <td valign="top" class="[{$listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{$listitem->fpocmcronjobs__oxid->value}]');" class="[{$listclass}]">[{$listitem->getTitle()}]</a></div></td>
                        <td valign="top" class="[{$listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{$listitem->fpocmcronjobs__oxid->value}]');" class="[{$listclass}]">[{$listitem->fpocmcronjobs__oxcrontab->value}]</a></div></td>
                        <td>&nbsp;</td>
                    [{/block}]
                </tr>
                [{if $blWhite == "2"}]
                    [{assign var="blWhite" value=""}]
                [{else}]
                    [{assign var="blWhite" value="2"}]
                [{/if}]
            [{/foreach}]
            [{include file="pagenavisnippet.tpl" colspan="5"}]
        </table>
    </form>
</div>

[{include file="pagetabsnippet.tpl"}]


<script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{oxmultilang ident="FPOCM_ADMIN_LIST_MENUITEM" }]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="FPOCM_ADMIN_LIST_MENUSUBITEM" }]";
        parent.parent.sWorkArea    = "[{$_act}]";
        parent.parent.setTitle();
    }
</script>

</body>
</html>