<?php
defined( 'ABSPATH' ) || exit;

// Adv Filter Integration
$adv_filter_dataset = ultimate_post()->get_adv_data_attrs( $attr );

$pagi_block_html = '';

// Pagination
if ( $attr['advPaginationEnable'] && ( $attr['paginationType'] == 'pagination' ) ) {
	$pagi_datasets = array(
		'for'        => 'ultp-block-' . $attr['blockId'],
		'blockid'    => sanitize_html_class( $attr['blockId'] ),
		'expost'     => isset( $curr_post_id ) ? $curr_post_id : '',
		'paged'      => '1',
		'pages'      => $pageNum,
		'blockname'  => 'ultimate-post_' . $block_name,
		'postid'     => ( isset( $attr['currentPostId'] ) && $attr['currentPostId'] ) ? sanitize_html_class( $attr['currentPostId'] ) : ultimate_post()->get_page_post_id( ultimate_post()->get_ID(), $attr['blockId'] ),
		'selfpostid' => ( ( isset( $attr['currentPostId'] ) && $attr['currentPostId'] ) ? 'yes' : 'no' ),
	);

	$f_pagi_datasets = ultimate_post()->get_formatted_datasets( $pagi_datasets );

	$pagi_class = ' class="ultp-pagination-wrap' . ( $attr['paginationAjax'] ? ' ultp-pagination-ajax-action' : '' ) . '" ';


	$pagi_block_html .= '<div' . $pagi_class . ultimate_post()->get_builder_attr( $attr['queryType'] ) . $adv_filter_dataset . $f_pagi_datasets . '>';

	$pagi_block_html .= ultimate_post()->pagination( $pageNum, $attr['paginationNav'], $attr['paginationText'], $attr['paginationAjax'] );

	$pagi_block_html .= '</div>';
}

// Navigation bottom
if ( $attr['advPaginationEnable'] && ( $attr['paginationType'] == 'navigation' ) ) {
	$pagi_datasets = array(
		'for'        => 'ultp-block-' . $attr['blockId'],
		'blockid'    => sanitize_html_class( $attr['blockId'] ),
		'expost'     => isset( $curr_post_id ) ? $curr_post_id : '',
		'pagenum'    => '1',
		'pages'      => $pageNum,
		'blockname'  => 'ultimate-post_' . $block_name,
		'postid'     => ( isset( $attr['currentPostId'] ) && $attr['currentPostId'] ) ? sanitize_html_class( $attr['currentPostId'] ) : ultimate_post()->get_page_post_id( ultimate_post()->get_ID(), $attr['blockId'] ),
		'selfpostid' => ( ( isset( $attr['currentPostId'] ) && $attr['currentPostId'] ) ? 'yes' : 'no' ),
	);

	$f_pagi_datasets = ultimate_post()->get_formatted_datasets( $pagi_datasets );
	$pagi_class      = ' class="ultp-next-prev-wrap" ';

	$pagi_block_html .= '<div' . $pagi_class . ultimate_post()->get_builder_attr( $attr['queryType'] ) . $adv_filter_dataset . $f_pagi_datasets . '>';

	if ( 1 != $pageNum ) {
		$pagi_block_html .= ultimate_post()->next_prev();
	}

	$pagi_block_html .= '</div>';
}

// Load More
if ( $attr['advPaginationEnable'] && ( $attr['paginationType'] == 'loadMore' ) ) {
	$allowed_html_tags = ultimate_post()->ultp_allowed_html_tags();

	$attr['loadMoreText'] = isset( $attr['loadMoreText'] ) ? wp_kses( $attr['loadMoreText'], $allowed_html_tags ) : 'Load More';

	$page_post_id = ( isset( $attr['currentPostId'] ) && $attr['currentPostId'] ) ? sanitize_html_class( $attr['currentPostId'] ) : ultimate_post()->get_page_post_id( ultimate_post()->get_ID(), $attr['blockId'] );

	$self_post_id = 'data-selfpostid="' . ( ( isset( $attr['currentPostId'] ) && $attr['currentPostId'] ) ? 'yes' : 'no' ) . '"';

	$exclude_id = isset( $curr_post_id ) ? $curr_post_id : '';

	$pagi_class = ' class="ultp-loadmore-action" ';

	$pagi_block_html .= '<div class="ultp-loadmore">';

	if ( 1 != $pageNum ) {
		$pagi_block_html .= '<span' . $pagi_class . ' tabindex="0" role="button" data-for="ultp-block-' . $attr['blockId'] . '" data-pages="' . $pageNum . '" data-pagenum="1"  data-expost="' . $exclude_id . '" data-blockid="' . $attr['blockId'] . '" data-blockname="ultimate-post_' . $block_name . '" data-postid="' . $page_post_id . '" ' . ultimate_post()->get_builder_attr( $attr['queryType'] ) . $self_post_id . $adv_filter_dataset . '>' . $attr['loadMoreText'] . ' <span class="ultp-spin">' . ultimate_post()->svg_icon( 'refresh' ) . '</span></span>';
	}

	$pagi_block_html .= '</div>';
}

// $pagi_block_html = json_encode( array( 'html' => $pagi_block_html ) );
$pagi_block_html = '<div class="pagination-block-html" aria-hidden="true" style="display: none;">' . $pagi_block_html . '</div>';
