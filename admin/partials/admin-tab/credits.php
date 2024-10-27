<br>
<div class="adapti-card pricing adapti-line">
	<div class="part adapti-plans">
        <div class="adapti-line adapti-line--margin">
            <h3>Plugin plans</h3>
            <a href="https://www.adapti.me/" class="adapti-link">Upgrade</a>
        </div>
        <div class="curpack">
            <?php echo adapti_remove_js($res->choice); ?>
        </div>
    </div>
    <div class="part adapti-credits">
        <h3 class="adapti-line--margin">User Credits</h3>
        <div>Total user credits right</div>
        <div class="bignum"><?php echo esc_html(Adapti_Human::msg('credits', [ 'nb' => number_format($adaptations->credits, 0, '.', ' '), 'mult' => (($adaptations->credits > 1) ? 's' : '') ])); ?></div>
        <div>
            <div class="adapti-block">
                <p>Currently Active adaptations</p>
                <div class="bignum"><?php echo esc_html(Adapti_Human::msg('adaptations', [ 'nb' => $adaptations->rulenb, 'mult' => ((count($adaptations) > 1) ? 's' : '') ])) ?></div>
            </div>
            <div class="adapti-block">
                <p>Total of<br/>free Credits</p>
                <div class="bignum"><?php echo esc_html(Adapti_Human::msg('credits', [ 'nb' => number_format($adaptations->freecredits, 0, '.', ' '), 'mult' => (($adaptations->freecredits > 1) ? 's' : '') ])); ?></div>
            </div>
            <div class="adapti-block">
                <p>Total of<br/>payed credits</p>
                <div class="bignum"><?php echo esc_html(Adapti_Human::msg('credits', [ 'nb' => number_format($adaptations->payedcredits, 0, '.', ' '), 'mult' => (($adaptations->payedcredits > 1) ? 's' : '') ])); ?></div>
            </div>
        </div>
    </div>
</div>