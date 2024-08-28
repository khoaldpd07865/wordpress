<?php
if ( isset( $args['notices'] ) && is_array( $args['notices'] ) ) :
	foreach ( $args['notices'] as $notice ) : ?>
		<p class="yith-wcwtl-error">
			<?php echo esc_html( $notice['notice'] ); ?>
		</p>
		<?php
	endforeach;
endif;
?>
