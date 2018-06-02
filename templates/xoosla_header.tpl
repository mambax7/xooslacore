<script type="text/javascript">
    IMG_ON = '<{xooslaIcons 16/icon-16-yes.png}>';
    IMG_OFF = '<{xooslaIcons 16/icon-16-no.png}>';
</script>
<ul id="xo-menu-breadcrumb" class="ui-corner-all"
    style="background-image:url('<{xooslaIcons /breadcrumb/bc_bg.png}>'); ">
    <{foreach item=breadcrumb from=$xo_menu_breadcrumb}>
        <{if $breadcrumb.home}>
            <li><a class="tooltip" href="<{$breadcrumb.link}>" title="<{$breadcrumb.title}>"
                   style="background-image:url('<{xooslaIcons breadcrumb/bc_separator.png}>');"><img class="home" src="<{xooslaIcons breadcrumb/home.png}>" alt="<{$breadcrumb.title}>"></a>
            </li>
        <{else}>
            <{if $breadcrumb.link}>
                <li><a class="tooltip" href="<{$breadcrumb.link}>" title="<{$breadcrumb.title}>"
                       style="background-image:url('<{xooslaIcons breadcrumb/bc_separator.png}>');"><{$breadcrumb.title}></a>
                </li>
            <{else}>
                <li><{$breadcrumb.title}></li>
            <{/if}>
        <{/if}>
    <{/foreach}>
    <{if $xo_menu_help}>
        <li class="xo-help">
            <a class="cursorhelp tooltip help_view" title="<{$smarty.const._XL_AD_NAV_HELP_VIEW}>"
               style="background-image:url('<{xooslaIcons breadcrumb/bc_separator_end.png}>'); display: visible;"><img src="<{xooslaIcons 32/icon-32-help.png}>" alt="<{$smarty.const._XL_AD_NAV_HELP_VIEW}>"></a>
            <a class="cursorhelp tooltip help_hide" title="<{$smarty.const._XL_AD_NAV_HELP_HIDE}>"
               style="background-image:url('<{xooslaIcons breadcrumb/bc_separator_end.png}>'); display: none;"><img src="<{xooslaIcons 32/icon-32-help.png}>" alt="<{$smarty.const._XL_AD_NAV_HELP_HIDE}>"></a>
        </li>
    <{/if}>
</ul>
<ul id="xo-tabs">
    <{$xo_menu_tabs}>
</ul>
<div class="hide" id="xo-menu-help">
    <{includeq file="$xo_menu_helpfile"}>
</div>
<{if $xo_menu_tips}>
    <div class="tips ui-corner-all">
        <img class="floatleft tooltip" src="<{xooslaIcons 32/icon-32-jabber.png}>" alt="<{$smarty.const._XL_AD_NAV_TIPS}>"
             title="<{$smarty.const._XL_AD_NAV_TIPS}>">
        <div class="floatleft"><{$xo_menu_tips}></div>
        <div class="clear">&nbsp;</div>
    </div>
<{else}>
    <br>
<{/if}>
