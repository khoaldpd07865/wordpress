<?php
class PostXBeaverTemplate extends FLBuilderModule {

	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'PostX Template', 'ultimate-post' ),
			'description'     => __( 'An basic example module using jQuery TwentyTwenty.', 'fl-builder' ),
			'category'        => __( 'Basic', 'ultimate-post' ),
			'dir'             => __DIR__,
			'partial_refresh' => true,
			'url'             => plugins_url( '', __FILE__ )
		));
	}
}


FLBuilder::register_module( 'PostXBeaverTemplate', array(
	'general' => array(
		'title' => __( 'General', 'ultimate-post' ),
		'sections' => array(
			'general' => array(
				'title' => __( 'Template Settings', 'ultimate-post' ),
				'fields' => array(
					'template' => array(
						'type'          => 'select',
						'label'         => __( 'Select Your Template', 'ultimate-post' ),
						'default'       => '',
						'options'       => ultimate_post()->get_all_lists('ultp_templates'),
						'multi-select'  => false
					),
					'edit_template' => array(
						'type'    => 'raw',
						'label'   =>  __( 'Edit Template', 'ultimate-post' ),
						'content' => '<a href="'.admin_url('edit.php?post_type=ultp_templates').'" style="color:#fff; background-color:#0c0d0e; padding:10px 20px; border-radius:4px; display:inline-block;" target="_blank"><span style="color:#fff; font-size:12px; width:12px; height:12px;" class="dashicons dashicons-edit"></span> '.__('Edit This Template', 'ultimate-post').'</a>'
					),
					'add_new_template' => array(
						'type'    => 'raw',
						'label'   => 'Add New Template',
						'content' => '<a href="'.admin_url('post-new.php?post_type=ultp_templates').'" style="color:#fff; background-color:#0c0d0e; padding:10px 20px; border-radius:4px; display:inline-block;" target="_blank"><span style="color:#fff; font-size:12px; width:12px; height:12px;" class="dashicons dashicons-plus-alt2"></span> '.__('Add New Template', 'ultimate-post').'</a>'
					)
				)
			)
		)
	)
));