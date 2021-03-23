<?php

add_action('manage_tour_posts_columns','expedition_add_program_columns');
function expedition_add_program_columns($column_headers) {
  $column_headers['options'] = 'Options';
  return $column_headers;
}

add_action('manage_tour_posts_custom_column', 'expedition_add_program_columns_content', 10, 2);
function expedition_add_program_columns_content($column, $program_id) {
    if ( $column == 'options' ){
//        echo '<a class="button button-secondary button-big" href="'. admin_url('admin-ajax.php?action=export_program_report&program_id=' . $program_id . '&nonce=' . wp_create_nonce("export_program_report")). '>Download Report</a>';
        echo '<a class="button button-secondary button-big" href="'. admin_url('admin-ajax.php?action=export_program_report&program_id=' . $program_id . '&nonce=' . wp_create_nonce("export_program_report")). '">Download Report</a>';
    }
}
