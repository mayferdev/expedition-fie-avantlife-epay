<?php
if (!defined('EXPEDITION_QR_SCANNER')) {
    define("EXPEDITION_QR_SCANNER", "expedition_qr_scanner");
}

if (!defined('EXPEDITION_QR_SCANNER_TITLE')) {
    define("EXPEDITION_QR_SCANNER_TITLE", __('QR Check In', 'expedition'));
}

add_action('admin_menu', 'expedition_add_qr_scanner_screen');

function expedition_add_qr_scanner_screen() {
    add_menu_page(EXPEDITION_QR_SCANNER_TITLE, EXPEDITION_QR_SCANNER_TITLE, 'edit_tours', EXPEDITION_QR_SCANNER, 'render_qr_scanner_content', 'dashicons-camera-alt', 5);
}

function render_qr_scanner_content() {
    ?>
<input type="hidden" value="<?= wp_create_nonce("program_confirm") ?>" id="program_confirm_nonce"/>
<div class="wrap" id="wrap">
    <h1>
        <?php esc_html_e('Check In by QR Scan', 'my-plugin-textdomain'); ?>
    </h1>
    <p id="wrapp">
        Please place the QR code in front of the camera of this device, if it does not work in one position rotate the QR code and/or zoom out the QR code.
    </p>
    <div id="outdiv"></div>
    <div id="result"></div>
</div>
<style>
    #qrfile{
        width:320px;
        height:240px;
    }
    #qr-canvas{
        display:none;
    }
    .wrap{
        text-align: center;
    }
    #result{
        font-size: 15px;
        margin-top: 10px;
    }
</style>
<canvas id="qr-canvas" width="800" height="600" ></canvas>
<script type="text/javascript">load();</script>
    <?php
}
    