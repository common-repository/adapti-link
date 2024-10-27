<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.adapti.me
 * @since      1.0.0
 *
 * @package    Adapti_Link
 * @subpackage Adapti_Link/admin/partials
 */

?>

<?php 
    require_once(__DIR__ . '/../../core/Link.php');

    $account = get_option('adapti_config_account');
    $token = get_option('adapti_config_token');


        if($account == false && $token == false){
            require(__DIR__ . '/install-form.php');
        } else { 
?>

<div class="wrap">

<hr class="wp-header-end"></hr>

    <script type="text/javascript">
        window.adapti = {};
        window.adapti.account = <?php echo json_encode($account == false ? null: $account) ?>;
        window.adapti.token = <?php echo json_encode($token == false ? null: $token) ?>;
    </script>

    <!-- btn : .wp-core-ui .button -->

    <br>
    <div class="adapti-line">
        <div class="adapti-card adapti-card--one-third settings">
            <!-- Updates -->
            <div class="part part--sep adapti-line versioncheck" data-version="1.01">
                <div class="updateTitle">
                    <h3>Plugin updates</h3>
                    <h4>RELEASE 2.01</h4>
                </div>
            </div>
            <!-- end Updates -->

            <!-- Link adapti -->
            <div class="part statuscheck">
                <div class="adapti-line adapti-line--margin">
                    <h3>Manage your Webite</h3>
                    <h4>Go to adapti website to create and manage your adaptations: <a href="https://adapti.me">www.adapti.me</a></h4>
                </div>
            </div>
            <!-- end status -->
        </div>

    </div>

    <?php
           // require(__DIR__ . '/credits.php');
        } 
    ?>

</div>