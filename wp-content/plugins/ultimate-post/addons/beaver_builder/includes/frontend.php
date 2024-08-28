<?php $id = $settings->template;//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
<div class="ultp-shortcode" data-postid="<?php echo esc_attr( $id ); ?>">
	<?php
	if ( $id ) {
		ultimate_post()->register_scripts_common();
		ultimate_post()->set_css_style( $id );
		$args      = array(
			'p'         => $id,
			'post_type' => 'ultp_templates',
		);
		$the_query = new \WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				the_content();
			}
			wp_reset_postdata();
		}
	} else {
		if ( isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
			echo '<p style="text-align:center;">' . sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.', 'ultimate-post' ) . ' ', '<strong><i>' . esc_html( 'Dashboard > PostX > Saved Templates', 'ultimate-post' ) . '</i></strong>' ) . '</p>'; //phpcs:ignore
		}
	}
	?>
</div>
