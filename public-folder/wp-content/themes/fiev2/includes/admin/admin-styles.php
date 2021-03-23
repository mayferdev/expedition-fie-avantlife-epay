<?php


add_action('admin_head', 'expedition_admin_styles');

function expedition_admin_styles() {
    ?>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <style type="text/css">
            
            .pull-left{float:left;}
            .pull-right{float:right;}
            /* DASHBOARD WIDGETS */
            .welcome-panel-column h2{margin: 20px 12px;font-size: 13px;font-weight: bold;color: #aaa;}
                
            .postbox{
                border: 1px solid #e5e5e5;
                box-shadow: 0 3px 11px rgba(0,0,0,.1);
                border-radius: 10px;
            }
            
            .welcome-panel{
                border : none;
                background : transparent;
                padding: 0;
                width : 100%;
            }
            .custom-welcome-panel-content{margin-right:20px;}
            .welcome-panel-content, .welcome-panel-close{display : none;}
            
            .stat_mini{overflow : hidden;}
            .stat_mini img{width : 100px;}
            .stat_mini strong{font-size: 40px;color: #555;line-height:1; display : block; margin-top : 5px;}
            .stat_mini span{font-size:18px;}
            .stat_mini .red{ color : red}
            .stat_mini .green{ color : green}
            .clearfix{clear:both}
            .half-cont{overflow:hidden}
            .welcome-panel-column-half{width : 50%;float:left;}
            .welcome-panel-column-half h2, .welcome-panel-column-full h2, h2.bluecolor{color : #007cb9; font-size : 26px; font-weight : bold; margin: 15px 12px;}
            .welcome-panel-column-half:first-child .postbox{margin-right:10px;}
            .welcome-panel-column-half:last-child .postbox{margin-left:10px;}
            .welcome-panel-column-half .postbox{height: 270px;}
            
            .welcome-panel-column-half .pull-left{font-size: 22px;color: #888; margin-bottom : 25px; margin-top : 15px;}
            .welcome-panel-column-half .pull-right{font-size: 24px;color: #888; margin-bottom : 25px; margin-top : 15px;}
            .welcome-panel-column-half .footer, .welcome-panel-column-full .footer{
                border-top: solid 1px #ddd;
                padding-top: 10px;
                position: absolute;
                bottom: 10px;
                left: 15px;
                right: 15px;
                text-align : right;
            }
            .welcome-panel-column-full .footer{    position: relative;margin-top: 25px;left: 0;right: 0;}
            .welcome-panel-column-two-third{width:66.6%; float : left;}
            .welcome-panel-column-two-third .half{width:50%;float:left;}
            
            
            @media screen and (min-width: 871px){
                .welcome-panel .welcome-panel-column, .welcome-panel .welcome-panel-column:first-child {
                    width : 33.3%;
                }
                .welcome-panel .welcome-panel-column:first-child .postbox{
                    margin-right : 10px;
                }
                .welcome-panel .welcome-panel-column:nth-child(2) .postbox{
                    margin-left : 10px;
                    margin-right : 10px;
                }
                .welcome-panel .welcome-panel-column:last-child .postbox{
                    margin-left : 10px;
                }
            }
            
            @media screen and (max-width: 870px){
                .welcome-panel-column-half{width : 100%!important;}
                .welcome-panel-column-half:first-child .postbox{margin-right:0;}
                .welcome-panel-column-half:last-child .postbox{margin-left:0;}
                .welcome-panel-column-two-third{width:100%}
            }
            @media screen and (max-width: 700px){
                .welcome-panel-column-two-third .half{width: 100%; margin-bottom: 20px;}
            }
            
            
            
            
            
            /* HIDE SCREEN OPTIONS */
            .acp-screen-option-prefs, .metabox-prefs.view-mode{display:none}
            
            
            /*GENERAL*/
            .widefat td, .widefat td ol, .widefat td p, .widefat td ul{font-size: 12px;}
            .index-php h1{
/*                background-image: url(/wp-content/uploads/2019/02/expedition_logo@2x.png);
                background-repeat: no-repeat;
                height : 75px;
                background-size: contain;*/
            }
            
            #wpfooter{display: none;}
            #wp-admin-bar-new-content{display:none;}
            .expedition-message{display:block!important;}
            #wp-admin-bar-site-name .ab-sub-wrapper{display:none!important}
            
            
            .subsubsub .count{display:none}
            <?php
            $current_user_role = expedition_get_user_role();
            if ( $current_user_role == 'administrator' || $current_user_role == 'editor' ){
                echo '#authordiv{display:block;}';
            }
            ?>
            
            /*LOOK & FEEL STYLES*/
            /*#adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu,
            #toplevel_page_expedition_reports{border-top : solid 15px #13293e!important;}
            #wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input,
            #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus,
            
            #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap{
                background-color: #13293e;
            }
            
            #adminmenu, #wpadminbar{background-color: #001529!important;}
            body, .acf-postbox.seamless > .acf-fields > .acf-tab-wrap .acf-tab-group li.active a{background-color: #eceef3;}
            */
            
            
            /* CONSULTATIONS LIST STYLES */
            
            .expedition_search_form{margin-bottom: -38px;}
            .expedition_search_input{width : 150px;}
            @media  ( max-width: 782px ){
                .expedition_search_form{
                    margin-bottom: -15px; 
                    margin-left: auto; 
                    margin-right:auto;
                    width: 350px;
                }
            }
            @media  ( max-width: 540px ){
                .expedition_search_form{width: 290px;}
                .expedition_search_input{width : 90px;}
            }
            
            
            .toplevel_page_expedition_consultations #col_id { text-align: left; width:85px !important; overflow:hidden }
            .toplevel_page_expedition_consultations #col_created_at { text-align: left; width:100px !important; overflow:hidden }
            .toplevel_page_expedition_consultations #col_finished_at { text-align: left; width:100px !important; overflow:hidden }
            
            .toplevel_page_expedition_consultations #col_charge { text-align: left; width:100px !important; overflow:hidden }
            .toplevel_page_expedition_consultations #col_expedition_fee { text-align: left; width:100px !important; overflow:hidden }
            .toplevel_page_expedition_consultations #col_status { text-align: left; width:90px !important; overflow:hidden }
            
            .modal-open #wpwrap{
                -webkit-filter: blur(5px);
                -moz-filter: blur(5px);
                -o-filter: blur(5px);
                -ms-filter: blur(5px);
                filter: blur(5px);
            }
            #TB_window{border-radius: 10px;}
            #TB_title{    
                border-radius: 10px 10px 0 0;
                height: 40px!important;
                border: none!important;
                background: #000000!important;
                color: white;
                text-align: center;
            }
            #TB_title > div{padding-top: 6px;}
            
            .consultation_container{}
            .consultation_container a{text-decoration: none;}
            .consultation_container ul{}
            .consultation_container ul li{padding: 5px;}
            .consultation_container ul li:nth-child(odd){background: #f9f9f9;}
            .consultation_container ul strong{display: inline-block; width: 160px;text-transform: uppercase;font-size: 11px;}
            .prescription_img{max-width: 60px;height: auto;}
            
            /* STATISTICS WIDGET STYLES */
            
            .select_report_type{margin-bottom: 20px;}
        
            .ul_table_widget{list-style: none;}
            .head{background-color: #00A0D2;color: white;padding: 5px 10px;display: table;width: 100%;margin-left: -10px;}
            .head > div{display: table-cell; vertical-align: middle;}
            .ul_table_widget li{overflow: hidden;}
            .ul_table_widget li.head{}
            
            .ul_table_widget li >div{}
            .ul_table_widget .first_column, .ul_table_widget .second_column, .ul_table_widget .third_column{float : left;overflow: hidden;}
            .ul_table_widget .first_column{width : 50%;}
            .ul_table_widget .second_column{width : 20%; }
            .ul_table_widget .second_column.hasbutton{margin-top: 5px;}
            .buttonBig{
                height: 35px!important;
                line-height: 35px!important;
                width: 100%;
                text-align: center;
                margin-top: 20px!important;
                /*margin-bottom: 20px!important;*/
            }
            
            table.wf-striped-table {
                    width: 100%;
                    max-width: 100%;
                    border-collapse: collapse;
            }
            table.wf-fixed-table {
                    table-layout: fixed;
            }
            table.wf-striped-table th,
            table.wf-striped-table td {    
                text-align: left;
                padding: 6px 8px;
                border: 1px solid #cccccc;
            }
            table.wf-striped-table thead th,
            table.wf-striped-table thead td {
                    background-color: #007cb9;
                    color: #FFFFFF;
                    font-weight: bold;
                    border-color: #007cb9;
            }
            table.wf-striped-table tbody tr.even td {
                    background-color: #eeeeee;
            }
            
            .ul_table_widget .third_column{width : 30%;text-align: right;}
            .no_margin_bottom{margin-bottom: 0; margin-top: 3px;}
            .no_margin_bottom:last-child{margin-bottom: 20px;}
            
            
            /* USER PROFILE STYLES */
            
            #profile-page{
                /*max-width: 630px;*/
                /*background: white;*/
                /*padding: 20px;*/
            }
            .user-profile-picture, .user-url-wrap, .user-syntax-highlighting-wrap, .user-comment-shortcuts-wrap, .user-admin-bar-front-wrap, .user-description-wrap{display: none;}
            
            #edit-slug-box{display:none;}
            body.profile-php #profile-page h2{
                padding: 20px;
                background: #ddd;
                margin: 0 -23px;
            }
            .row-actions .view{display : none!important;}
            .form-table.firebase_info_table th,
            .form-table.firebase_info_table td{
                padding-top:5px!important;padding-bottom:5px!important;
            }
            .firebase_image{
                width:60px; height:60px; background-size:cover;border-radius:30px; 
            }
            
            .user-edit-php .form-table td, .user-edit-php .form-table th, .user-edit-php .label-responsive {
                display: table-cell;
                vertical-align: middle;
            }
            .user-edit-php .form-table th{
                width: 200px;
            }
            .no_underline{text-decoration: none;}
            h2 small{font-weight: normal;}
                
            
            /* REQUESTS LIST STYLES */
            a.delete{color:#a00;}
            
        </style>
      
    <?php
}