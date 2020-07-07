<?php
function post_shortcode( $atts ) {

	// Parse your shortcode settings with it's defaults
	$atts = shortcode_atts( array(
		'number' => '2' // default
	), $atts, 'post' );

	// Extract shortcode atributes
	extract( $atts );

	// Define output var
	$output = '';

	// Define query
	$query_args = array(
		'post_type'      => 'post', // Change this to the type of post you want to show
		'posts_per_page' => $number,
		'taxonomy' => 'category',
		'field'    => 'slug',
		'terms'     => 'uncategorized',
		'operator'  => 'NOT IN'
	);

	// Query posts
	$custom_query = new WP_Query( $query_args );

	// Add content if we found posts via our query
	if ( $custom_query->have_posts() ) {

		// Open div wrapper around loop
		echo '<div class="jcustom blog-layout-alternate">';

		// Loop through posts
		while ( $custom_query->have_posts() ) {

			$custom_query->the_post(); ?>

			<article id="post-<?php the_ID(); ?>" class="type-post status-publish format-standard has-post-thumbnail hentry category-blogs post">

				<?php if ( ! is_single() && ! siteorigin_corp_is_post_loop_widget() && siteorigin_setting( 'blog_archive_layout' ) == 'offset' || siteorigin_corp_is_post_loop_template( 'offset' ) ) : ?>
					<div class="entry-offset">
						<?php siteorigin_corp_offset_post_meta(); ?>
					</div>
				<?php endif; ?>

				<?php if ( has_post_thumbnail() ) : ?>
					<?php siteorigin_corp_entry_thumbnail(); ?>
				<?php endif; ?>	

				<div class="corp-content-wrapper">

					<header class="entry-header">
						<?php 
						if ( is_single() ) {
							if ( siteorigin_page_setting( 'page_title' ) ) {
								the_title( '<h2 class="entry-title">', '</h1>' );
							}
						} else {
							the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
						} ?>

						<?php if ( 'post' === get_post_type() ) : ?>
							<div class="entry-meta">
								<?php 
								if ( ! is_single() && ! siteorigin_corp_is_post_loop_widget() && siteorigin_setting( 'blog_archive_layout' ) == 'offset' || siteorigin_corp_is_post_loop_template( 'offset' ) ) :
									siteorigin_corp_posted_on();
								else :
									siteorigin_corp_post_meta();
								endif; 
								?>
						</div><!-- .entry-meta -->
						<?php
						endif; ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php
							if ( is_single() || ( siteorigin_setting( 'blog_archive_content' ) == 'full' ) ) {
								the_content();
							} else {
								siteorigin_corp_excerpt_custom();
							}

							wp_link_pages( array(
								'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'siteorigin-corp' ) . '</span>',
								'after'  => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
							) );
						?>
					</div>
					
				</div>

				<?php siteorigin_corp_entry_footer(); ?>
			</article>

		<?php }

		echo '</div>';

		// Restore data
		wp_reset_postdata();

	}

}
add_shortcode( 'post_service_page', 'post_shortcode' );
