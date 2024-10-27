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

?>

<div class="wrap">

<hr class="wp-header-end"></hr>

    <script type="text/javascript">
        window.adapti = {};
        window.adapti.account = <?php echo json_encode($account == false ? null: $account) ?>;
        window.adapti.token = <?php echo json_encode($token == false ? null: $token) ?>;
    </script>
    <!-- btn : .wp-core-ui .button -->

    <?php 
        require(__DIR__ . '/adaptation-list.php');
    ?>

</div>