<div style="padding-top: 3px; padding-bottom: 3px; text-align: left;">
    <{foreach from=$legend key=k item=v}>
        <div style="padding: 3px;"><img style="vertical-align: middle;"
                                        src="<{$xoops_url}>/modules/xooslacore/media/icons/<{$k}>" title="<{$v}>"
                                        alt="<{$v}>"> <{$v}></div>
    <{/foreach}>
</div>
