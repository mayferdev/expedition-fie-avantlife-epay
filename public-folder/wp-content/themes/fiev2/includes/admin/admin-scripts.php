<?php


add_action('admin_head', 'expedition_admin_scripts');

function expedition_admin_scripts() {
    ?>
    
    <script type="text/javascript">
        jQuery( document ).ready(function() {
            if ( window.location !== window.parent.location ) {
                // The page is in an iframe
                document.getElementsByTagName('html')[0].style.paddingTop = 0;
                document.getElementById('wpadminbar').style.display = 'none';
            }
            jQuery('#profile-page > a, #profile-page > h1').remove();
            jQuery('.user-rich-editing-wrap').parents('table').prev().remove();
            jQuery('.user-rich-editing-wrap').parents('table').remove();
            
            jQuery('.user-description-wrap').parents('table').prev().remove();
            jQuery('.user-description-wrap').parents('table').remove();
            
            jQuery('.user-capabilities-wrap').parents('table').prev().remove();
            jQuery('.user-capabilities-wrap').parents('table').remove();
            
            
            <?php
            $current_user_role = expedition_get_user_role();
            if ($current_user_role != 'administrator' && $current_user_role != 'editor'){
                ?>
                jQuery('#user_login').parents('table').prev().hide();
                jQuery('#user_login').parents('table').hide();
                
                jQuery('#email').parents('table').prev().hide();
                jQuery('#email').parents('table').hide();
                <?php
            }
            ?>
            
            
            
            
            /************************************************************************/
            /********** SEND REQUEST TO CHANGE TO NEXT LEVEL OF MEMBERSHIP **********/
            /************************************************************************/

            jQuery(document).on('click', "#change_membership_level", function(e) {
                e.preventDefault();
                var pcont = jQuery('#change_membership_level').parent();
                pcont.html('Please wait...');
                var that = this;
                
                jQuery.ajax({
                   type : "post",
                   dataType : "json",
                   url : ajaxurl,
                   data : {action: "change_membership_level"},
                   success: function(response) {
                      if( response.success ) {
                            pcont.html(response.html);
                            console.log('success,', response);
                      }else {
                            console.log('error,', response);
                            //alert("Your vote could not be added")
                      }
                   }
                })   
            })
            
            
            
            
            /******************************************/
            /********* INITIALIZE DOCTOR INFO *********/
            /******************************************/

            jQuery(document).on('click', ".confirm_single_booking", function(e) {
                e.preventDefault();
                jQuery(this).removeClass('confirm_single_booking').text('Please wait...');
                var booking_id = jQuery(this).attr("data-booking_id")
                var nonce = jQuery(this).attr("data-nonce")
                var that = this;
                
                jQuery.ajax({
                   type : "post",
                   dataType : "json",
                   url : ajaxurl,
                   data : {action: "confirm_single_booking", booking_id : booking_id, nonce: nonce},
                   success: function(response) {
                      if( response.success ) {
                          // jQuery(that).attr('href', response.url).text(response.text);
                          jQuery(that).parents('td').first().find('small').first().text(response.text)
                          jQuery(that).parent().remove()
                         console.log('success,', response);
                      }else {
                          console.log('error,', response);
                         //alert("Your vote could not be added")
                      }
                   }
                })   

            })
            
            /******************************************/
            /******* Mark booking as Canceled ********/
            /******************************************/

            jQuery(document).on('click', ".cancel_single_booking", function(e) {
                e.preventDefault();
                jQuery(this).removeClass('cancel_single_booking').text('Cargando...');
                // get booking id
                var booking_id = jQuery(this).attr("data-booking_id")
                // get data-nonce: one time security token to perform important actions
                var nonce = jQuery(this).attr("data-nonce")
                var that = this;
                <?php
                $nonce = wp_create_nonce("cancel_single_booking");
                ?>
                jQuery.ajax({
                   type : "post",
                   dataType : "json",
                   url : ajaxurl,
                   data : {action: "cancel_single_booking", booking_id : booking_id, nonce: nonce},
                   success: function(response) {
                      if( response.success ) {
                          // jQuery(that).attr('href', response.url).text(response.text);
                          jQuery(that).parents('td').first().find('small').first().text(response.text)
                            // add next status button (mark as delivered )
                          jQuery(that).parents('td').append(
                            <?php
                            if(current_user_can('administrator')){
                                echo "'".'<p class="buttons"><a href="#" '
                                    .'data-nonce="' . $nonce . '" '
                                    .'data-booking_id="'."'+";
                            ?>
                                response.booking_id
                            <?php echo "+     '".'" '
                                    . 'class="cancel_single_booking button button-small">'
                                    .__('Cancelada', 'expedition' ).'</a></p>'."'";
                                }
                              ?>
                              )
                          jQuery(that).parent().remove()
                         console.log('success,', response);
                      }else {
                          console.log('error,', response);
                         //alert("Your vote could not be added")
                      }
                   }
                })   

            })
            
            /******************************************/
            /********** GET BALANCE OF MONTH **********/
            /******************************************/

            jQuery(document).on('click', "#get_balance_button", function(e) {
                e.preventDefault();
                jQuery('#financial_selected_month_content').html('<p>Please wait...</p>');
                
                var month = jQuery('#financial_selected_month').val();
                var nonce = jQuery(this).attr("data-nonce");
                var that = this;
                
                jQuery.ajax({
                   type : "post",
                   dataType : "json",
                   url : ajaxurl,
                   data : {action: "get_financial_of_month", month : month, nonce: nonce},
                   success: function(response) {
                      if( response.success ) {
                            jQuery('#financial_selected_month_content').html(response.html);
                            console.log('success,', response);
                      }else {
                            console.log('error,', response);
                            //alert("Your vote could not be added")
                      }
                   }
                })   
            })
            
            /******************************************/
            /********* EXPORT REPORT OF MONTH *********/
            /******************************************/
            
            jQuery(document).on('click', "#export_consultation_report_button", function(e) {
                e.preventDefault();
                //jQuery('#export_consultation_report_content').html('Please wait...');
                
                var month = jQuery('#export_consultation_selected_month').val();
                var nonce = jQuery(this).attr("data-nonce");
                window.location = ajaxurl+'?action=export_consultation_report_of_month&month='+month+'&nonce='+nonce;
                var that = this;
                
                /*
                jQuery.ajax({
                   type : "post",
                   //dataType : "json",
                   url : ajaxurl,
                   data : {action: "export_consultation_report_of_month", month : month, nonce: nonce},
                   success: function(response) {
                      if( response.success ) {
                            jQuery('#export_consultation_report_content').html(response.html);
                            
                            console.log('success,', response);
                      }else {
                            console.log('error,', response);
                            //alert("Your vote could not be added")
                      }
                   }
                })
                */
            })
            
        });
    </script>
      
    <?php
}