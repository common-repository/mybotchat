<?php

/**
 * Plugin Name: Mybotchat
 * Description: Custom ChatGPT AI Chatbot for your business website to handle customer support and lead generation 24/7.
 * Version: 1.0.0
 * Author: Mybotchat
 * Author URI: https://mybot.chat
 * License: GPL2
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
add_action('admin_menu', 'Mybotchat_add_options_page');

// Add the options page to the admin menu
function Mybotchat_add_options_page()
{
    add_options_page('Mybotchat Plugin Settings', 'Mybotchat Options', 'administrator', 'Mybotchat_id', 'Mybotchat_options_page');
    add_action('admin_init', 'Mybotchat_options_register');
}

// Register the options settings
function Mybotchat_options_register()
{
    register_setting('Mybotchat_options', 'Mybotchat_id');
    register_setting('Mybotchat_options', 'Mybotchat_width', array(
        'show_in_rest' => true,
        'type'         => 'number',
        'default'      => 450,
    ));
    register_setting('Mybotchat_options', 'Mybotchat_height', array(
        'show_in_rest' => true,
        'type'         => 'number',
        'default'      => 600,
    ));
    register_setting('Mybotchat_options', 'Mybotchat_color', array(
        'show_in_rest' => true,
        'type'         => 'string',
        'default'      => '#000',
    ));
}

// Define the content of the options page
function Mybotchat_options_page()
{
    ?>
    <div class="wrap">
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <div id="form-container">

            <form method="post" action="options.php">
                <?php settings_fields('Mybotchat_options'); ?>
                <?php do_settings_sections('Mybotchat_options'); ?>

                <div class="logo-container">
                    <a href="https://mybot.chat/" target="_blank">
                        <img alt="Mybotchat" loading="lazy" width="64" style="color:transparent"
                            src="<?php echo plugin_dir_url( __FILE__ ) . 'images/mybot-logo-small.png'; ?>">
                    </a>
                </div>

                <div class="form-group">
                    <label for="Mybotchat_id" class="text-secondary">Chatbot ID</label>
                    <input type="text" class="form-control" placeholder="Chatbot ID" name="Mybotchat_id" id="Mybotchat_id"
                        value="<?php echo esc_attr(get_option('Mybotchat_id')); ?>" required />
                </div>
                <div class="form-group">
                    <label for="Mybotchat_width" class="text-secondary">Embed Width</label>
                    <input type="text" class="form-control" placeholder="Embed Width" name="Mybotchat_width" id="Mybotchat_width"
                        value="<?php echo esc_attr(get_option('Mybotchat_width', 450)); ?>" />
                </div>
                <div class="form-group">
                    <label for="Mybotchat_height" class="text-secondary">Embed Height</label>
                    <input type="text" class="form-control" placeholder="Embed Height" name="Mybotchat_height" id="Mybotchat_height"
                        value="<?php echo esc_attr(get_option('Mybotchat_height', 600)); ?>" />
                </div>
                <div class="form-group">
                    <label for="Mybotchat_color" class="text-secondary">Embed Icon Color</label>
                    <input type="text" class="form-control" placeholder="Embed Icon Color" name="Mybotchat_color" id="Mybotchat_color"
                        value="<?php echo esc_attr(get_option('Mybotchat_color', '#000')); ?>" />
                </div>
                <div>
                    <label class="note-label">
                        *Note: Copy your Chatbot ID from <a href="https://mybot.chat/" target="_blank">Mybot.chat</a> Console page
                    </label>
                </div>
                <div>
                    <label class="note-label">
                        *Note: See <a href="https://mybot.chat/documentation" target="_blank">MyBot Documentations</a> for step-by-step tutorial on how to train your AI Chatbot before you can use it here.
                    </label>
                </div>
                <div class="submit-btn-container">
                    <?php submit_button(); ?>
                </div>
            </form>
        </div>



    </div>
    <?php
}

function Mybotchat_Container() {
    if ( is_admin() ) {
		return;
	}
    echo '<div id="mybotchat-container"></div>';
}
add_action( 'wp_footer', 'Mybotchat_Container', 100 );

// Embed the script on the site using the ID entered in the options page
function Mybotchat_embed_chatbot()
{
    $handle = 'mybotchat-script';

    $script_url = plugin_dir_url( __FILE__ ).'js/mybotchatbubble.min.js';

    $Mybotchat_id = get_option('Mybotchat_id');
    $width = get_option('Mybotchat_width', 450);
    $height = get_option('Mybotchat_height', 600);
    $color = get_option('Mybotchat_color', '#000');

    // Enqueue the script
    wp_enqueue_script(
        $handle,
        $script_url,
        array(), // Dependencies (if any)
        '1.0.1', // Version number (null for no version)
        // Add script in the footer
        array(
            'strategy' => 'async',
            'in_footer' => true,
       )
    );

    $data = "window.mybotparams = { width: ".esc_js($width).", height:".esc_js($height)
            .", botId: '". esc_js($Mybotchat_id). "', "
            ." bubbleColor: '". esc_js($color) ."'};";
    wp_add_inline_script( $handle, $data,'before' );
}

add_action('wp_enqueue_scripts', 'Mybotchat_embed_chatbot');

//style for admin option
function Mybotchat_addStyle() {
    $style_url = plugin_dir_url( __FILE__ ).'css/mybotchat_style.css';
    wp_enqueue_style('Mybotchat_style', $style_url, array(), '1.0.1');
}

add_action('admin_enqueue_scripts', 'Mybotchat_addStyle');
?>
