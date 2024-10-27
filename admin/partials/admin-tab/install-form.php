<div class="wrap"> 
    
    <hr class="wp-header-end"></hr>

    <div class="adapti-card install">
        <div class="headerbg" style="background-image: url(<?php echo WP_PLUGIN_URL; ?>/adapti-link/admin/img/head_bg2.png);background-size: cover;">
            <h2>Welcome to your</h2>
            <h1>Personalization service</h1>
        </div>
        <div class="part">
            <p style='text-align:center;margin-bottom:25px;'>Just follow a few tiny steps to fully implement the plugin.<br/>Please note that if you use the Free Version, we will add a small link to our services at the bottom of your website.</p>
            <div class="list list--1">Please register on <u>www.adapti.me</u> in order to chose the wanted subscription, to generate your personalization service API and get the needed token.</div>
            <div class="btn-holder" style="text-align:center;"><a href="https://www.adapti.me" target="_blank" class="adapti-btn">Sign up on Adapti.me</a></div>
            <div class="list list--2">Please copy the Wordpress token you can find on the settings page to connect your website with the API.</div>
           
            <form method='POST' action='' style="width: 70%;margin:auto;">
                <div class="adapti-line">
                    <label class="adapti-label">Please enter an API key</label>
                </div>
                <div class="adapti-line">
                    <input type="text" name="token" id='token' placeholder="Paste your token here..." value='<?php echo esc_attr(get_option('adapti_config_token')); ?>'>
                    <input type='submit' class="adapti-button" value="Let's go">
                </div>
            </form> 
            <br/><br/><br/>
        </div>
        

    </div>

</div>