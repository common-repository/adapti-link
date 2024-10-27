<?php 
	
 ?>

<div class="adapti-metabox">

	<div class='msg'> 
		<span class="versions"> <?php echo $strOperators; ?> </span></span> 
	</div>

	<div class="adapti-versions">
		<?php 
			$i = 0;
			foreach($versions as $i => $version){
				?>
				<div class="version<?php echo $version['ID'] == get_the_ID() ? ' current' : '' ?>">
					<span class="index"> <span class="nb"> <?php echo $i ?> </span>. </span> 
					<span class="labels"> <?php echo $version['label'] ?> </span>

					<?php if(count($version['tag_miss']) > 0){ ?>
						<i class="dashicons dashicons-warning wrapper">
							<div class="tooltip left"><?php echo Adapti_Human::msg('tag_miss') . ' ' . $version['tag_miss_label'] ?></div>
						</i>
					<?php } ?>
					
					<?php if($version['ID'] == get_the_ID() && !$imdad){ ?>
						<a class="action delete" href=" <?php echo get_delete_post_link($version['ID']); ?> " title="<?php echo Adapti_Human::msg('delete_tooltip') ?>">
							<i class="dashicons dashicons-no-alt"></i>
						</a>
					<?php } else if ($version['ID'] != get_the_ID()){ ?>
						<a class="action edit" href=" <?php echo get_edit_post_link($version['ID']); ?> " title="<?php echo Adapti_Human::msg('edit_tooltip') ?>">
							<i class="dashicons dashicons-edit"></i>
						</a>
					<?php } ?>
				</div>
				<?php
			}

		 ?>
	</div>

	<a class="button no-margin" href="<?php echo admin_url( "post-new.php?post_type=adapti_version&adapti_version_referer=$dad"); ?>"> + Add version</a>

	<script type="text/javascript">
		var btn = document.querySelector('.action.delete');
		btn ? btn.addEventListener('click', function(e){
			if(!confirm('<?php echo Adapti_Human::msg('delete_version') ?> ')){
				e.preventDefault();
			}
		}) : '';
	</script>
</div>