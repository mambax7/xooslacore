<div class="editbar" id="editbar">
    <table class="editbar">
        <tr>
            <{foreach from=$menu_icons item=icon}>
                <td class="text" id="editbar-<{$icon.key}>">
                    <a href="#" onclick="<{$icon.link}>"><{$icon.image}><{$icon.title}></a>
                </td>
            <{/foreach}>
        </tr>
    </table>
</div>
<div class="clear">&nbsp;</div>
