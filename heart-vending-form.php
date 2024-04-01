<?php
/*
 * Plugin Name: Heart Vending Form
 * Description: A simple plugin for a custom form to handle various types of inquiries
 * Plugin URI: keleheart.com
 * Version: 1.0
 * Author: Kele Heart
 * Author URI: keleheart.com
 * License: GPL2
 */

register_activation_hook( __FILE__, 'heart_vending_form_activate' );

function heart_vending_form_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'form_submissions';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        inquiry_reason VARCHAR(255) NOT NULL,
        specific_reasons TEXT,
        issues TEXT NOT NULL
    )";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

// Enqueue scripts and styles
function heart_vending_form_scripts_and_styles() {
    wp_enqueue_script('reason', plugin_dir_url(__FILE__) . 'assets/js/reason.js', array('jquery'), date('h:i:s'), true);
    wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), date('h:i:s'), 'all');
}
add_action('wp_enqueue_scripts', 'heart_vending_form_scripts_and_styles');

// Create a form to send emails and save to the database
function heart_vending_form() {
    ob_start();
    ?>
        <form method="post" id="heart-vending-form" action="https://heartvending.com/thank-you/" enctype="multipart/form-data">
        <input type="hidden" name="action" value="heart_vending_form">
        <input type="text" name="kh-name" id="name" placeholder="Name" required>
        <input type="email" name="kh-email" id="email" placeholder="Email" required>
        <div class="form-group">
            <label for="inquiry_reason">Reason for Inquiry</label>
            <select name="inquiry_reason" id="inquiry_reason" required>
                <option value="" hidden>Select an option</option>
                <option value="general">General Inquiry</option>
                <option value="business">New Business</option>
                <option value="customer-service">Customer Service</option>
            </select>
        </div>
        <div id="form-insert"></div>
        <textarea name="kh-message" id="kh-message" placeholder="Enter Message" required></textarea>
        <div class="form-group">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('heart_vending_form', 'heart_vending_form');

function process_submission(){
    if (isset($_POST['submit'])) {
        //save the information to the database and send the email
        global $wpdb;

        $table_name = $wpdb->prefix . 'form_submissions';
        $name = $_POST['kh-name'];
        $email = $_POST['kh-email'];
        $inquiry_reason = $_POST['inquiry_reason'];
        if(isset($_POST['specific_reason'])){
            $specific_reasons = $_POST['specific_reason'];
        } else {
            $specific_reasons = '';
        }
        $issues = $_POST['kh-message'];

        //insert the data into the database

        $reasons = implode(', ', $specific_reasons);

        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'inquiry_reason' => $inquiry_reason,
                'specific_reasons' => $reasons,
                'issues' => $issues,
            )
        );

        $to = 'kele@keleheart.com';
        $subject = 'New Inquiry';
        $message = "Name: $name\n";
        $message .= "Email: $email\n";
        $message .= "Inquiry Reason: $inquiry_reason\n";
        $message .= "Specific Reasons: $reasons\n";
        $message .= "Issues: $issues\n";

        if(wp_mail($to, $subject, $message)){
            echo '<h1>Thank you for your submission</h1>';
            echo '<p>We will get back to you as soon as possible!</p>';
            echo '<p>Please allow up to 48 hours for a response.</p>';
        } else {
            echo '<h1>There was an error with your submission</h1>';
            echo '<p>Please try again</p>';
            echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">Go Back</a>';
        }
    }else{
        //redirect to the home page
        wp_redirect(home_url());
    }
}

add_shortcode('process_submission', 'process_submission');