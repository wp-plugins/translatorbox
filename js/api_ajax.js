function get_translation (text_var,from,to,tr_box_width,tr_box_height,bg_color,txt_color) 
{
    jQuery.post(
        // see tip #1 for how we declare global javascript variables
        tr_box_ajax.ajaxurl,
        {
            // here we declare the parameters to send along with the request
            // this means the following action hooks will be fired:
            // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
            action : 'tr-box-request',
     
            // other parameters can be added along with "action"
            text_to_translate :text_var,
            from_language :from,
            to_language :to,
            security_check :tr_box_ajax.security_check
        },
        function(returned_json){
            console.log(returned_json);
            if (jQuery('#from_text').length <= 0) 
            {
                jQuery('#translate').after('<textarea id="from_text" style="width:'+tr_box_width+';height:'+tr_box_height+';background-color:'+bg_color+';color:'+txt_color+'"></textarea>');
            }

            try 
            {
                var translation_text = returned_json.matches[0].translation;
                if (!translation_text) {throw "Error Emtpy String"};
                jQuery('#from_text').text(translation_text);
            }
            catch(err)
            {
                jQuery('#from_text').text('No available translation!');
            }
        }
    );
}

function swap_langs()
{
    var from = jQuery('#from option:selected').val();
    var to = jQuery('#to option:selected').val();
    jQuery('#from').val(to);
    jQuery('#to').val(from);
}

function generate_shortcode()
{
    var allLangs = new Array();
    jQuery('input:checkbox[name=langs]:checked').each(function()
        {
                    allLangs.push(jQuery(this).val());
                    console.log(jQuery(this).attr('checked',undefined));
        });
    var lang_string = '';
    var lang_length = allLangs.length;
    if (lang_length>1) {
        for(iiterator=0;iiterator<lang_length;iiterator++)
        {
            if (iiterator==lang_length-1) {
                lang_string+=allLangs[iiterator];
            }
            else{
                lang_string+=allLangs[iiterator]+',';
            }
            
            
        }
        if (jQuery('#short-code').length <= 0)
        {
            if (jQuery('#short-code-error'))
                {
                    jQuery('#short-code-error').remove();
                }
            jQuery('.wrap').after('<p id="short-code"><strong>You can copy and paste this generated shortcode in your page or post: </strong></p><textarea id="trbox-code" style="width:80%;height:27px;padding:5px 0 0 5px">[translation_box languages="'+lang_string+'" width="100%" height="200px" bgcolor="white" txtcolor="#000000"]</textarea></p>');
        } 
        else 
        {
             jQuery('#trbox-code').text('[translation_box languages="'+lang_string+'" width="100%" height="200px" bgcolor="white" txtcolor="#000000"]');
        }
    }
    else 
    {
        if (jQuery('#short-code-error').length <= 0)
        {
            if(jQuery('#short-code'))
                {
                    jQuery('#short-code').remove();
                    jQuery('#trbox-code').remove();
                }
            jQuery('.wrap').after('<p id="short-code-error" style="color:red"><strong>You have to choose 2 or more languages for the shortcode</strong></p>');
        }
    }
}