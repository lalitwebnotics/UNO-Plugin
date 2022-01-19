<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! defined( 'UNOLOGO' )){
	define('UNOLOGO','data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSI0NTYgMjg2LjIgMTY0IDE2NCI+PHN0eWxlPnBhdGh7ZmlsbDojZWVlO308L3N0eWxlPjxwYXRoIGQ9Ik01NzQuNyAzMjMuNWMtOC4zIDIxLTIxLjQgNDAuMy0zNC41IDU0VjQxMGwtMS41LjVoLTJjLTQ1IDMtNzEuOCAyNS43LTcxLjggMjUuNyA1MC40LTMxIDEwOC4yLTE3IDEwOC4yLTE2LjVsNC40LTF2LTk0LjJsLTMtMXoiLz48cGF0aCBkPSJNNjA4LjYgMzQ2LjRzLTUgNi4zLTE5LjQgMTkuNGMtMy40IDMtNS4zIDUtOC43IDcuOHY0OC42bC0zIC41cy0xMi41LTQtMzQuNC01Yy0yMS4zLS40LTUzLjMgNS40LTc3LjYgMTguNmgxczY1LjUtMjUuNyAxNDEuOC41bDMtMXYtODguNGwtMi42LTF6Ii8+PHBhdGggZD0iTTUzNy4zIDMwMC43bC0zLjQtMXYuNWMtLjYgMy00IDI2LjItMjEgNTguMy05LjcgMTktMjQuOCA0My4yLTQ4IDY3djExLjJzMjQuMi0yMy4zIDY4LjQtMjcuN2gxLjVsMi4zLTFWMzAwLjd6Ii8+PC9zdmc+');
}

/**
 * Checking for the current PHP version.
 * We support 5.4+
 */
if ( version_compare( phpversion(), '5.4', '<' ) ) {
    add_action( 'admin_notices', function() {
        $screen = get_current_screen();
        if ( $screen->base === 'plugins' ) {
            ?>
            <div class="notice notice-error">
                <p>
                    <?php printf( __( 'Dynamics NAV Integration detected that your environment has PHP %1$s. The plugin requires at least PHP %2$s to work. Please upgrade your PHP installation to fully enable the plugin.', 'uno' ), phpversion(), '5.4' ); ?>
                </p>
            </div>
            <?php
        }
    } );
    return;
}

/**
 * Check whether cURL is installed.
 */
if ( !function_exists( 'curl_version' ) ) {
    add_action( 'admin_notices', function() {
        $screen = get_current_screen();
        if ( $screen->base === 'plugins' ) {
            ?>
            <div class="notice notice-error">
                <p>
                    <?php _e( 'cURL, a PHP extension, is not installed. <strong>Dynamics NAV Integration</strong> requires cURL to work properly.', 'uno' ); ?>
                </p>
            </div>
            <?php
        }
    } );

    return;
}

/**
 * Check whether SOAP is installed.
 */
if ( !extension_loaded( 'soap' ) ) {
    add_action( 'admin_notices', function() {
        $screen = get_current_screen();
        if ( $screen->base === 'plugins' ) {
            ?>
            <div class="notice notice-error">
                <p>
                    <?php _e( 'soap, a PHP extension, is not installed. <strong>Dynamics NAV Integration</strong> requires cURL to work properly.', 'uno' ); ?>
                </p>
            </div>
            <?php
        }
    } );

    return;
}