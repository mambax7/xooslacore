<{php}>
    $this->_tpl_vars[andorArray] = array( 'OR' => _XL_AD_TOOBAR_ANY, 'AND' => _XL_AD_TOOBAR_ALL, 'exact' => _XL_AD_TOOBAR_EXACT );
    $this->_tpl_vars[limitArray] = array( 5 => '5', 10 => '10', 15 => '15', 25 => '25', 30 => 30, 50 => '50', 100 => '100', 0 => _XL_AD_TOOBAR_ALLPD );
    $this->_tpl_vars[orderArray] = array( 'ASC' => _XL_AD_TOOBAR_ASC, 'DESC' => _XL_AD_TOOBAR_DESC );
<{/php}>
<div style="clear: both;"></div>

<div class="boxrounded" id="boxrounded">
    <div style="float: left;">
        <{if $calendar }>
            <span><{ $calendar }></span>
        <{/if}>
        <input name="search" id="search" type="text" value="<{$search}>" size="15" maxlength="100"
               onfocus="if(this.value=='<{$smarty.const._XL_AD_TOOBAR_FILTER}>')this.value='';"
               onblur="if(this.value=='')this.value='<{$smarty.const._XL_AD_TOOBAR_FILTER}>';">
        <{html_options name=andor id=andor options=$andorArray selected=$andor}>
        <button class="button" onClick="this.form.submit();"><{$smarty.const._XL_AD_TOOBAR_SUBMIT}></button>
        <button class="button"
                onClick="document.getElementById('search').value='<{$smarty.const._XL_AD_TOOBAR_FILTER}>';<{foreach item=reset from=$resets}><{$reset}><{/foreach}>this.form.submit();"><{$smarty.const._XL_AD_TOOBAR_RESET}></button>
    </div>
    <div style="float: right;">
        <{if $pulldowns}>
            <{foreach item=pulldown from=$pulldowns}>
                <span><{$pulldown}></span>
            <{/foreach}>
        <{/if}>
        <{html_options name=order id=order onchange=document.adminform.submit(); options=$orderArray selected=$order}>
        <{html_options name=limit id=limit onchange=document.adminform.submit(); options=$limitArray selected=$limit}>
    </div>
    <div style="clear: both;"></div>
</div>
<div style="clear: both;"></div>
