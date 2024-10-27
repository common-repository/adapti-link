<tr>
    <td> <span class="medal"><?php echo esc_html($index+1); ?></span> </td>
    <td> 
        <div> <strong><?php echo adapti_remove_js($adaptation->name); ?></strong> </div> 
        <div><?php echo esc_html($adaptation->description); ?></div> 
    </td>
    <td class='nb-big'><?php echo esc_html($adaptation->count); ?></td>
</tr>