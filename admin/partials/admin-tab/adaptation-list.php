<?php 
    require_once(__DIR__ . '/../../core/Link.php');

    $account = get_option('adapti_config_account');
    $token = get_option('adapti_config_token');


        if($account == false && $token == false){
            require(__DIR__ . '/install-form.php');
        } else { 
?>

<br>
<h2>Working adaptations</h2>
<div class="adapti-card adaptations">
	<?php 
		if(count($adaptations->rules) > 0){
		?>
			<table class='table sample-fluid h'>
                <thead>
                    <th class='text-left'>Ranking</th>
                    
                    <th class='text-left'>Personalization Name</th>
                    <th class='text-left'>Hits</th>
                </thead>
                <tbody>
                <?php 
                	foreach($adaptations->rules as $index => $adaptation){
                		require(__DIR__ . '/adaptation-component.php');
                	}
                ?>
                </tbody>
            </table>
		<?php 
		}
	?>
</div>
<?php
        }
?>