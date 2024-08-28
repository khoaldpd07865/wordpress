<?php
if( isset( $args['notices'] ) && is_array( $args['notices'] ) ):
	foreach( $args['notices'] as $notice ):
        if( isset( $notice['notice'] ) ): ?>
            <div class="wrapper-notice yith-wcwtl-success">
                <img src="<?php echo YITH_WCWTL_ASSETS_URL . '/images/success.svg' ?>" alt="success" />
                <span class="notice-text"><?php echo $notice['notice']; ?></span>
            </div>
        <?php endif; ?>
	<?php
	endforeach;
endif;
?>