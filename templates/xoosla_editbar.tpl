<div class="editbar" id="editbar">
    <table class="editbar">
        <tr>
            <{foreach from=$selection key=k item=v}>
                <td class="text" id="editbar-<{$k}>">
                    <a href="#" onclick="javascript:submitform('<{$k}>');return false;"><span class="icon-32-<{$k}>"
                                                                                              title="<{$v}>">1</span><{$v}>
                    </a>
                </td>
            <{/foreach}>
        </tr>
    </table>
</div>
<br clear="all"/>
