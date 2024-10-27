<?php 
	$adapti_data = json_decode(Adapti_Api::get('rule'));

    if($adapti_data->rulenb)
        $adapti_nb = $adapti_data->rulenb;
    else
        $adapti_nb = 0;

?>
<span class="medal-menu wrapper">
	<?php echo $adapti_nb > 99 ? '99+' : $adapti_nb; ?>
	<div class="tooltip right"><?php echo esc_html(Adapti_Human::msg('adaptations', [ 'nb' => $adapti_nb, 'mult' => (($adapti_nb > 1) ? 's' : '') ])); ?></div>
</span>
