// Submit button
function submitbutton( button ) {
    submitform( button );
}

/**
 *
 * @access public
 * @return void
 **/
function submitform( button ){
    if ( button ) {
        document.adminform.op.value=button;
    }
    if ( typeof document.adminform.onsubmit == "function" ) {
        document.adminform.onsubmit();
    }
    document.adminform.submit();
    return true;
}

/**
 *
 * @access public
 * @return void
 **/
function submitValidateForm( button ){
    if ( xoopsFormValidate_adminform() == false ) {
        return false;
    }

    if ( button ) {
        document.adminform.op.value=button;
    }
    if ( typeof document.adminform.onsubmit == "function" ) {
        document.adminform.onsubmit();
    }
    document.adminform.submit();
    return true;
}


/**
 *
 * @access public
 * @return void
 **/
function isChecked(isitchecked){
    if (isitchecked == true){
        document.adminform.boxchecked.value++;
    }
    else {
        document.adminform.boxchecked.value--;
    }
}

/**
 *
 * @access public
 * @return void
 **/
function xoopsCheckAll( form, switchId )
{
    var eltForm = xoops$(form);
    var eltSwitch = xoops$(switchId);
    // You MUST NOT specify names, it's just kept for BC with the old lame crappy code
    if ( !eltForm && document.forms[form] ) {
        eltForm = document.forms[form];
    }
    if ( !eltSwitch && eltForm.elements[switchId] ) {
        eltSwitch = eltForm.elements[switchId];
    }
    var i;
    var n2 = 0;
    for (i = 0; i != eltForm.elements.length; i++) {
        if ( eltForm.elements[i] != eltSwitch && eltForm.elements[i].type == 'checkbox' ) {
            eltForm.elements[i].checked = eltSwitch.checked;
            if (eltSwitch.checked == true){
                n2++;
            }
        }
    }
    if (n2 > 0) {
        document.adminform.boxchecked.value = n2;
    } else {
        document.adminform.boxchecked.value = 0;
    }
}

/**
 *
 * @access public
 * @return void
 **/
function pageNavigation( param ){
    document.forms['adminform'].order.value = 'DSC';
    document.forms['adminform'].start.value = param;
}

/**
 *
 * @access public
 * @return void
 **/
function system_setStatus( data, img, file ) {
    // Post request
    $.post( file, data ,
    function(reponse, textStatus) {
        if (textStatus=='success') {
            $('img#'+img).hide();
            $('#loading_'+img).show();
            setTimeout(function(){
                $('#loading_'+img).hide();
                $('img#'+img).fadeIn('fast');
            }, 500);
            // Change image src
            if ($('img#'+img).attr("src") == IMG_ON) {
                $('img#'+img).attr("src",IMG_OFF);
            } else {
                $('img#'+img).attr("src",IMG_ON);
            }
        }
    });
}

/**
 *
 * @access public
 * @return void
 **/
$(document).ready(function() {

        $("a.help_view").click(function(){
            $("div#xo-menu-help").slideToggle(1000);
            $("a.help_view").toggle();
            $("a.help_hide").toggle();
        });

       $("a.help_hide").click(function(){
            $("div#xo-menu-help").slideToggle(1000);
            $("a.help_view").toggle();
            $("a.help_hide").toggle();
       });

       if('function' == typeof($("").tablesorter)){
            $("#xo-candylist-sorter").tablesorter({sortList: [[0,0]], headers: {5:{sorter: false}}});
        }
    });

    function system_displayHelp() {
    $("div.panel_button").click(function(){
        $("div#panel").animate({
            height: "500px"
        })
        .animate({
            height: "400px"
        }, "fast");
        $("div.panel_button").toggle();

    });

   $("div#hide_button").click(function(){
        $("div#panel").animate({
            height: "0px"
        }, "fast");
   });
}