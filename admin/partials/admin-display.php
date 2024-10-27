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
    require_once(__DIR__ . '/../core/Link.php');

    $account = get_option('adapti_config_account');
    $token = get_option('adapti_config_token');

?>

<div class="wrap">
    <script type="text/javascript">
        window.adapti = {};
        window.adapti.account = <?php echo json_encode($account == false ? null: $account) ?>;
        window.adapti.token = <?php echo json_encode($token == false ? null: $token) ?>;
        
    </script>

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <div class="adapti-card status">
    	<span class="icon"></span>
    	<span class="text"> 
    		<span class="text-spotlight"> Service not connected </span> 
    		<span class="text-light"> - Please enter an API key </span> 
    	</span>
    	<span class="actions">
    		<button class="button update">Relaunch Test</button>
    		<a class="button" href="<?php echo esc_url(Adapti_plug_Link::route('', 'public')); ?>" target="_blank">Help</a>
    	</span>
    </div>

    <div class="adapti-card install">
    	<div class="step account">
    		<div class="info">
    			<div class="number">1</div>
    			<div class="text text-spotlight">Create an admin account on adapti.me</div>
    		</div>
    		<div class="actions">
    			<button class="button"> <span class="text-bold"> I already have </span> an account </button>
    			<a class="button" href="<?php echo esc_url(Adapti_plug_Link::route('register', 'admin')); ?>" target="_blank"> <span class="text-bold"> Create </span> a free account </a>
    		</div>
    	</div>
    	<div class="step token">
    		<div class="info">
    			<div class="number">2</div>
    			<div class="text text-spotlight">Link the accounts with the API Token</div>
    			<input type="text" name="token" placeholder="Paste the API token here ..." />
                <div class="error_msg"></div>
    		</div>
    		<div class="actions">
    			<button class="button"> <span class="text-bold">Link</span> the accounts </button>
    		</div>
    	</div>
    	<div class="step adaptations">
    		<div class="info">
    			<div class="number">3</div>
    			<div class="text text-spotlight">Start creating adaptation to make your website personalize itself</div>
    		</div>
    		<div class="actions">
    			<a class="button" href="<?php echo esc_url(Adapti_plug_Link::route('add-rule', 'admin')); ?>" target="_blank"> <span class="text-bold">Create</span> Adaptations </a>
    		</div>
    	</div>
    </div>

</div>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
