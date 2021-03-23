<?php


function expedition_custom_dashboard() {
    $screen = get_current_screen();
    if( $screen->base == 'dashboard' ) {
        expedition_welcome_panel();
    }
}
add_action('admin_notices', 'expedition_custom_dashboard');

function expedition_welcome_panel() {
    global $current_user, $wpdb;
    if ($current_user && $current_user->ID == 1) {
        
    } else {
         // return false;
    }
    ?>
    <script type="text/javascript">
        /* Hide default welcome message */
        var defaultChartOptions = {
                responsive: true,
                legend: {
                    display : false,
                    position: 'top'
                },
                labels :{
                    fontColor : 'red',
                    display : false
                },
                scales: {
                    xAxes: [{
                    ticks: {
                        display: false
                    }
                  }]
                },
                title: {
                    display: false,
                    text: ''
                }
        };

        jQuery(document).ready(function ($)
        {


        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="https://www.chartjs.org/samples/latest/utils.js"></script>

    <div id="welcome-panel" class="welcome-panel">
        <div class="custom-welcome-panel-content">
            <h3 style="font-size: 30px;color: #888;margin: 0 0 20px 0;">
                <?php
                $name = get_user_meta($current_user->ID, 'first_name', true );
                ?>
                <?= __('Welcome', 'expedition') . ' ' .$name.'!'; ?> 
            </h3>
            <div class="welcome-panel-column-container">

                <div class="welcome-panel-column">
                    <div class="postbox">
                        <h2>
                            <span>Total de eventos</span>
                        </h2>
                        <?php
                        /**
                        $bookings_all = $wpdb->get_results("SELECT * FROM ".USER_BOOKINGS_TABLE);
                        foreach ($bookings_all as $key => $booking) {
                            if ( $booking->seats ){
                                continue;
                            }
                            $total = Expedition_Helper::getTourBookingTotalSeats($booking);

                            $wpdb->update( USER_BOOKINGS_TABLE, 
                                    array( 
                                        'seats' => $total,
                                        'updated_at' => current_time('mysql', 1)
                                    ), 
                                    array( 
                                        'id' => $booking->id
                                        ), 
                                    array( '%d', '%s' ), 
                                    array( '%d' )
                            );

                        }
                         */
                        
                        
                        $admin_query_modifier = Expedition_Helper::isCurrentUserAdmin() ? '' : " AND owner_id=$current_user->ID ";
                        
                        $total_seats = $wpdb->get_var("SELECT SUM(seats) as seats FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE ( status = ".BOOKING_CHECKED_IN." OR status = ".BOOKING_CONFIRMED." OR status = ".BOOKING_CONFIRMED_CARD." ) $admin_query_modifier ");
                        $total_sales = $wpdb->get_var("SELECT SUM(amount) as amount FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE ( status = ".BOOKING_CHECKED_IN." OR status = ".BOOKING_CONFIRMED." OR status = ".BOOKING_CONFIRMED_CARD." ) $admin_query_modifier ");
                                
                        $count_posts = wp_count_posts( 'tour' )->publish;  
                        
                        
                        $post_count = $wpdb->get_var("SELECT COUNT(ID) FROM ".$wpdb->prefix."posts WHERE post_author = '" . $current_user->ID . "' AND post_type = 'tour' AND post_status = 'publish'");
                        
                       

                        $old_date = date("Y-m-d H:i:s", strtotime(date('Y-m-01') . " -11 months"));
                        $query = "SELECT MONTH(created_at) as month, created_at, SUM(seats) as seats, SUM(amount) as amount".
                            " FROM ".USER_BOOKINGS_TABLE.
                            " WHERE created_at > '$old_date' AND ( status = ".BOOKING_CHECKED_IN." OR status = ".BOOKING_CONFIRMED." ) $admin_query_modifier ".
                            " GROUP BY month ORDER BY created_at DESC";

                        $seats_by_month = $wpdb->get_results($query);
                        // var_dump_pre( $query, $wpdb->get_results($query) );
                        $months_names = array();
                        $month_data = array();
                        foreach ($seats_by_month as $i => $seat) {
                            $months_names[] = date("F Y", strtotime($seat->created_at));
                            $month_data[] = $seat->seats;
                        }
                        $month_data_seats = array();
                        $months_names_seats = array();
                        foreach ($seats_by_month as $i => $seat) {
                            $months_names_seats[] = date("F Y", strtotime($seat->created_at));
                            $month_data_seats[] = $seat->seats;
                        }
                        $month_data_sales = array();
                        $months_names_sales = array();
                        foreach ($seats_by_month as $i => $seat) {
                            $months_names_sales[] = date("F Y", strtotime($seat->created_at));
                            $month_data_sales[] = $seat->amount;
                        }
                        ?>
                        <div class="inside"> 
                            <div class="stat_mini">
                                <div class="pull-left">
                                    <strong><?= $post_count ?></strong><br/>
                                    <span class="green">
                                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                        
                                    </span>
                                </div>
                                <!--<div class="pull-right">-->
                                <!--    <div style="width: 200px;height: 100px">-->
                                <!--        <canvas id="canvas_visits"></canvas>-->
                                <!--    </div>-->

                                <!--    <script>-->
                                <!--        var color = Chart.helpers.color;-->
                                <!--        var barChartDataVisits = {-->
                                <!--                labels: <?= json_encode( $months_names ) ?>,-->
                                <!--                datasets: [{-->
                                <!--                        label: 'Eventos',-->
                                <!--                        backgroundColor: color('#00e1ff').alpha(0.5).rgbString(),-->
                                <!--                        borderColor: 'transparent',-->
                                <!--                        borderWidth: 1,-->
                                <!--                        data: [<?= implode(',', $month_data) ?>]-->
                                <!--                }]-->
                                <!--        };-->
                                <!--    </script>-->

                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="welcome-panel-column">
                    <div class="postbox">
                        <h2>
                            <span>Boletos vendidos</span>
                        </h2>

                        <div class="inside"> 
                            <div class="stat_mini">
                                <div class="pull-left">
                                    <strong><?= $total_seats ?></strong><br/>
                                    <?php
                                    $current_month_seats = $month_data_seats[0];
                                    $prev_month_seats = $month_data_seats[1];
                                    $change = $current_month_seats - $prev_month_seats;
                                    $percent = $change / $current_month_seats * 100;
                                    // var_dump_pre($month_data_seats, $current_month_seats, $prev_month_seats, $change, $percent);
                                    // var_dump_pre($current_month_seats, $prev_month_seats);
                                    ?>
                                    <span class="<?= $percent > 0 ? 'green' : 'red' ?>">
                                        <i class="fa fa-arrow-<?= $percent > 0 ? 'up' : 'down' ?>" aria-hidden="true"></i>
                                        <?= number_format($percent,2) ?>%
                                    </span>
                                </div>
                                <div class="pull-right">
                                    <div style="width: 200px;height: 100px">
                                        <canvas id="canvas"></canvas>
                                    </div>

                                    <script>
                                        var color = Chart.helpers.color;
                                        var barChartDataSeats = {
                                                labels: <?= json_encode( $months_names_seats ) ?>,
                                                datasets: [{
                                                        label: 'Personas',//#00e1ff
                                                        backgroundColor: color('#ac9fff').alpha(0.5).rgbString(),
                                                        borderColor: 'transparent',
                                                        borderWidth: 1,
                                                        data: [<?= implode(',', $month_data_seats) ?>]
                                                }]
                                        };
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="welcome-panel-column">
                    <div class="postbox">
                        <h2>
                            <span>Total de ventas</span>
                        </h2>
                        <div class="inside"> 
                            <div class="stat_mini">
                                <div class="pull-left">
                                    <strong>Q<?= $total_sales > 1000 ? number_format($total_sales/1000, 0).'K' : number_format($total_sales,2) ?></strong><br/>
                                    <?php
                                    $current_month_sales = $month_data_sales[0];
                                    $prev_month_sales = $month_data_sales[1];
                                    $change_sales = $current_month_sales - $prev_month_sales;
                                    $percent_sales = $change_sales / $current_month_sales * 100;
                                    ?>
                                    <span class="<?= $percent_sales > 0 ? 'green' : 'red' ?>">
                                        <i class="fa fa-arrow-<?= $percent_sales > 0 ? 'up' : 'down' ?>" aria-hidden="true"></i>
                                        <?= number_format($percent_sales,2) ?>%
                                    </span>
                                </div>
                                <div class="pull-right">
                                    <div style="width: 200px;height: 100px">
                                        <canvas id="canvas_sales"></canvas>
                                    </div>

                                    <script>
                                        var barChartDataSales = {
                                                labels: <?= json_encode( $months_names_sales ) ?>,
                                                datasets: [{
                                                        label: 'Quetzales',
                                                        backgroundColor: color('#00ed9d').alpha(0.5).rgbString(),
                                                        borderColor: 'transparent',
                                                        borderWidth: 1,
                                                        data: [<?= implode(',', $month_data_sales) ?>]
                                                }]
                                        };
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="clearfix"></div>

            <div class="half-cont">
                <div class="welcome-panel-column-half">
                    <div class="postbox">
                        <h2>
                            <span>Ventas</span>
                        </h2>
                        <?php

                        $amount_last_month = $wpdb->get_var("SELECT SUM(amount) as amount FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE ( status = ".BOOKING_CHECKED_IN." OR status = ".BOOKING_CONFIRMED." OR status = ".BOOKING_CONFIRMED_CARD.") $admin_query_modifier "
                                . "AND YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) "
                                . "AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ");

                        $amount_current_month = $wpdb->get_var("SELECT SUM(amount) as amount FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE ( status = ".BOOKING_CHECKED_IN." OR status = ".BOOKING_CONFIRMED." OR status = ".BOOKING_CONFIRMED_CARD.") $admin_query_modifier "
                                . "AND YEAR(created_at) = YEAR(CURRENT_DATE) "
                                . "AND MONTH(created_at) = MONTH(CURRENT_DATE)");

                        /*
                        $bookings_all = $wpdb->get_results("SELECT * FROM ".USER_BOOKINGS_TABLE);
                        foreach ($bookings_all as $key => $booking) {
                            $total = Expedition_Helper::getTourBookingTotalAmount($booking);

                            $wpdb->update( USER_BOOKINGS_TABLE, 
                                    array( 
                                        'amount' => $total,
                                        'updated_at' => current_time('mysql', 1)
                                    ), 
                                    array( 
                                        'id' => $booking->id
                                        ), 
                                    array( '%d', '%s' ), 
                                    array( '%d' )
                            );

                        }
                        */
                        ?>
                        <div class="inside"> 
                            <div class="">
                                <div class="pull-left">
                                    Mes Pasado
                                </div>
                                <div class="pull-right">
                                    Q<?= number_format($amount_last_month, 2) ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="">
                                <div class="pull-left">
                                    Mes Actual
                                </div>
                                <div class="pull-right">
                                    <strong>Q<?= number_format($amount_current_month, 2) ?></strong>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class='footer'>
                            <a class="button button-primary button-small" href="<?= admin_url("admin.php?page=expedition_transactions") ?>">Ver todo</a>
                            <a class="button button-primary button-small" href="">Exportar</a>
                        </div>
                    </div>
                </div>
                <div class="welcome-panel-column-half">
                    <div class="postbox">
                        <h2>
                            <span>Reservas</span>
                        </h2>
                        <div class="inside"> 

                            <table class="wf-striped-table wf-fixed-table">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Evento ID</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php                                    
                                        $bookings = $wpdb->get_results("SELECT * FROM ".USER_BOOKINGS_TABLE."
                                                        WHERE ( status = ".BOOKING_PENDING." OR status = ".BOOKING_PENDING_CONFIRM." ) $admin_query_modifier "
                                                . "ORDER BY created_at DESC LIMIT 3");

                                        if ( $bookings && count($bookings) > 0 ){
                                            foreach( $bookings as $i => $booking ){
                                                $name = $booking->source=='web'? 'Web' :get_user_meta($booking->user_id, 'first_name', true ). ' '. get_user_meta($booking->user_id, 'last_name', true );
                                                ?>
                                                <tr <?= $i % 2 == 0 ? 'class="even"' : 'class="odd"' ?>>
                                                    <td><?= $name ?></td>
                                                    <td>
                                                        <a href='<?= admin_url('post.php?post='.$booking->tour_id.'&action=edit') ?>'>
                                                            <?= $booking->tour_id ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?= $booking->seats ?>
                                                    </td>
                                                    <td>
                                                        <?= Expedition_Helper::getTourBookingStatusFromCode($booking->status) ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }   
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class='footer'>
                            <a class="button button-primary button-small" href="<?= admin_url("admin.php?page=expedition_tour_bookings&search_for=status&search=1") ?>">Ver todo</a>
                            <a class="button button-primary button-small" href="">Exportar</a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="full-cont">
                <div class="welcome-panel-column-full">
                    <div class="postbox">
                        <h2>
                            <span>Confirmados</span>
                        </h2>
                        <div class="inside"> 

                            <table class="wf-striped-table wf-fixed-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Tour</th>
                                        <th>Espacios</th>
                                        <th>Notas</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $bookings2 = $wpdb->get_results("SELECT * FROM ".USER_BOOKINGS_TABLE."
                                                        WHERE 1=1 AND status=".BOOKING_CONFIRMED." $admin_query_modifier ORDER BY created_at DESC LIMIT 3");

                                        if ( $bookings2 && count($bookings) > 0 ){
                                            foreach( $bookings2 as $i => $booking ){
                                                $name = $booking->source=='web'? 'Web' :get_user_meta($booking->user_id, 'first_name', true ). ' '. get_user_meta($booking->user_id, 'last_name', true );
                                                $total = Expedition_Helper::getTourBookingTotalAmount($booking);
                                                ?>
                                                <tr <?= $i % 2 == 0 ? 'class="even"' : 'class="odd"' ?>>
                                                    <td>
                                                        <a href='<?= admin_url('admin.php?page=expedition_tour_bookings&search_for=id&search='.$booking->id) ?>'>
                                                            <?= $booking->id ?>
                                                        </a>
                                                    </td>
                                                    <td><?= $name ?></td>
                                                    <td>
                                                        <a href='<?= admin_url('post.php?post='.$booking->tour_id.'&action=edit') ?>'>
                                                            <?= get_the_title($booking->tour_id) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?= $booking->seats ?>
                                                    </td>
                                                    <td>
                                                        ...
                                                    </td>
                                                    <td>
                                                        Q<?= number_format($total, 2) ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }   
                                        }
                                    ?>
                                </tbody>
                            </table>
                            
                            <div class='footer'>
                                <a class="button button-primary button-small" href="<?= admin_url("admin.php?page=expedition_tour_bookings&search_for=status&search=1") ?>">Ver todo</a>
                                <a class="button button-primary button-small" href="">Exportar</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <div class="postbox">
                        <h2 class="bluecolor">
                            <span>Clientes</span>
                        </h2>
                        <div class="inside"> 
                            <div class="stat_mini">
                                <?php
                                //$total_users_app = count( get_users( array( 'fields' => array( 'ID' ), 'role__in' => array( 'expeditioner', /*'business'*/ ) ) ) );
                                $total_users_app = $wpdb->get_var("SELECT count(amount) as count FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE source='app' $admin_query_modifier ");
                                $total_users_web = $wpdb->get_var("SELECT count(amount) as count FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE source='web' $admin_query_modifier ");
                                $total_users_whatsapp = $wpdb->get_var("SELECT count(amount) as count FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE source='whatsapp' $admin_query_modifier ");
                                $total_users_facebook = $wpdb->get_var("SELECT count(amount) as count FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE source='facebook' $admin_query_modifier ");
                                $total_users_instagram = $wpdb->get_var("SELECT count(amount) as count FROM ".USER_BOOKINGS_TABLE." "
                                . "WHERE source='instagram' $admin_query_modifier ");
                                
                                $total_users = $total_users_app + $total_users_web + $total_users_whatsapp + $total_users_facebook + $total_users_instagram;
                                ?>
                                <div>
                                    <div class="pull-left">Total</div>
                                    <div class="pull-right" style="color:#00a496;font-weight: bold;"><?= $total_users ?></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div style="width: 100%;height: 250px;margin-top: 10px;border-top: solid 1px #ddd;padding-top: 20px;">
                                    <canvas id="canvas_clients"></canvas>
                                </div>

                                <script>
                                    Chart.defaults.pie.cutoutPercentage = 5;
                                    var pieChartDataClients = {
                                            labels: <?= json_encode( array(
                                                "App $total_users_app", 
                                                "Web $total_users_web", 
                                                "Whatsapp $total_users_whatsapp", 
                                                "Facebook $total_users_facebook", 
                                                "Instagram $total_users_instagram") ) ?>,
                                            datasets: [{
                                                    label: 'Clientes',
                                                    borderColor: 'transparent',
                                                    borderWidth: 0,
                                                    data: [<?= implode(',', array( 
                                                        $total_users_app , 
                                                        $total_users_web, 
                                                        $total_users_whatsapp,
                                                        $total_users_facebook,
                                                        $total_users_instagram
                                                        )) ?>],
                                                    backgroundColor: [
                                                            color('#edecff').alpha(0.5).rgbString(),
                                                            color('#a89cff').alpha(0.5).rgbString(),
                                                            
                                                            color('#52EA64').alpha(0.85).rgbString(),
                                                            color('#3B5998').alpha(0.85).rgbString(),
                                                            color('#E73292').alpha(0.85).rgbString(),
                                                    ]
                                            }]
                                    };
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="welcome-panel-column-two-third">
                    <div class="postbox">
                        <h2 class="bluecolor">
                            <span>Proximos eventos</span>
                        </h2>
                        <?php
                        $basic_args = array(
                            'post_type'=>'tour', 
                            'meta_key'          => 'departure_date',
                            'orderby'           => 'meta_value_num',
                            'order'             => 'DESC',
                            'posts_per_page'    =>  1000,
                            'fields'            => array('ID', 'post_date')

                        );

                        $args = array(
                                    'meta_query' => array(
                                        'relation' => 'OR', // Optional, defaults to "AND"
                                        array(
                                            'relation' => 'AND',
                                            array(
                                                'key'     => 'type',
                                                'value'   => 'public',
                                                'compare' => '='
                                            ),
                                            array(
                                                'key'     => 'departure_date',
                                                'value'   =>  date("Y-m-d H:i:s"),
                                                'compare' => '>=', // Return the ones greater than today's date
                                                'type' => 'DATE'
                                            )
                                        )
                                    )
                                );
                        $query = new WP_Query( array_merge($basic_args, $args) );

                        $dates = array();
                        if ( $query->found_posts ){
                            foreach ($query->posts as $p) {
                                $date = get_field( 'departure_date', $p->ID, false );
                                $fdate = date('Y-m-d', strtotime($date));
                                /*if ( isset($dates[$fdate]) ){
                                    $dates[$fdate]++;
                                }else{
                                    $dates[$fdate] = 1;
                                }*/
                                $dates[$fdate] = admin_url( "edit.php?s&post_type=tour&action=-1&departure=$fdate" );
                            }
                        }
                        // var_dump_pre($dates);
                        ?>
                        <div class="inside">
                            <div class="half">
                                <?php
                                $calendar = new Calendar();
                                //echo $calendar->show( date('Y'), date('m', strtotime('++1 month')) );
                                echo $calendar->show( date('Y'), date('m'), $dates );
                                ?>
                            </div>
                            <div class="half">
                                <?php
                                $calendar = new Calendar();
                                echo $calendar->show( date('Y'), date('m', strtotime('++1 month')), $dates );
                                ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
            <script>
                var barChartDataSales = {
                        labels: <?= json_encode( $months_names ) ?>,
                        datasets: [{
                                label: 'Quetzales',
                                backgroundColor: color('#00ed9d').alpha(0.5).rgbString(),
                                borderColor: 'transparent',
                                borderWidth: 1,
                                data: [<?= implode(',', $month_data_sales) ?>]
                        }]
                };

                window.onload = function() {
                    new Chart(document.getElementById('canvas').getContext('2d'), {
                            type: 'bar',
                            data: barChartDataSeats,
                            options: defaultChartOptions
                    });
                    new Chart(document.getElementById('canvas_sales').getContext('2d'), {
                            type: 'bar',
                            data: barChartDataSales,
                            options: defaultChartOptions
                    });
                    new Chart(document.getElementById('canvas_visits').getContext('2d'), {
                            type: 'bar',
                            data: barChartDataVisits,
                            options: defaultChartOptions
                    });
                    new Chart(document.getElementById('canvas_clients').getContext('2d'), {
                            type: 'doughnut',
                            data: pieChartDataClients,
                            options: {
                                        responsive: true,
                                        legend: {
                                            display : true,
                                            position: 'bottom'
                                        },
                                        cutoutPercentage : 80,
                                        labels :{
                                            fontColor : 'red',
                                            display : false
                                        },
                                        scales: {
                                            xAxes: [{
                                            ticks: {
                                                display: false
                                            }
                                          }]
                                        },
                                        title: {
                                            display: false,
                                            text: ''
                                        }
                                }
                    });

                };
            </script>

        </div>
    </div>

    <?php
}


add_action('wp_dashboard_setup', 'expedition_remove_dashboard_widgets');

function expedition_remove_dashboard_widgets() {
    // Globalize the metaboxes array, this holds all the widgets for wp-admin
    global $wp_meta_boxes, $current_user;

    // Remove right now
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

    if ($current_user && $current_user->ID == 1) {
        
    } else {
        unset($wp_meta_boxes['dashboard']['normal']['core']['wordfence_activity_report_widget']);
    }
    // var_dump_pre( $wp_meta_boxes );
}

if (current_user_can('business') || current_user_can('administrator') || current_user_can('editor')) {
    add_action('wp_dashboard_setup', 'expedition_reports_add_dashboard_widget');
}

function expedition_reports_add_dashboard_widget() {
    wp_add_dashboard_widget(
            'expedition_transactions_report_widget', __('Transactions Reports', 'expedition'), 'expedition_transactions_report_widget'
    );
}

/**
 * Display the widget with the reports
 * 
 * @global type $wpdb
 * @global type $valid_countries
 * @return type'
 */
function expedition_transactions_report_widget() {

    echo '<div class="widget_cont">';
    ?>    
    <table class="wf-striped-table wf-fixed-table">
        <thead>
            <tr>
                <th><?= __('Period', 'expedition') ?></th>
                <th width="85px"><?= __('Options', 'expedition') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i <= 6; $i++) {
                $month = date("F Y", strtotime(date('Y-m-01') . " -$i months"));
                ?>
                <tr <?= $i % 2 == 0 ? 'class="even"' : 'class="odd"' ?>>
                    <td><?= $month ?></td>
                    <td>
                        <a class="button button-secondary button-big" href="<?= admin_url('admin-ajax.php?action=export_transactions_report_of_month&month=' . date("Y-m", strtotime(date('Y-m-01') . " -$i months")) . '&nonce=' . wp_create_nonce("export_transactions_report_of_month")) ?>">Download</a>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr class="odd">
                <td>
                    <strong><?= __('Custom Period', 'expedition') ?></strong><br/>
                    <input id="export_consultation_selected_month" class="input_month" type="month" name="export_consultation_selected_month" 
                           max="<?= date('Y-m', strtotime(date('Y-m-01') . " -7 month")) ?>"
                           value="<?= date('Y-m', strtotime(date('Y-m-01') . " -7 months")) ?>"/>
                </td>
                <td>
                    <a data-nonce="<?= wp_create_nonce("export_transactions_report_of_month"); ?>" id="export_consultation_report_button"
                       class="button button-secondary button-big" target="_blank" href="">Download</a>
                    <div id="export_consultation_report_content"></div>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
    echo '</div>';

    return;
}
