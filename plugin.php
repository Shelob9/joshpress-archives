<?php
/**
 Plugin Name: JoshPress Archives
 */

add_filter( 'caldera_easy_queries_loop_callback', array( 'jp2_archives', 'set_loop_cb' ) );
add_filter( 'caldera_easy_queries_args', array( 'jp2_archives',  'args' ) );
add_filter( 'caldera_easy_queries_search_atts', array( 'jp2_archives', 'atts' ) );
add_filter( 'category_template', array( 'jp2_archives', 'load_term_template' ) );
add_filter( 'tag_template', array( 'jp2_archives', 'load_term_template' ) );
add_filter( 'frontpage_template', array( 'jp2_archives', 'load_frontpage_template' ) );
/**
 * Class jp2_archives
 *
 * Class for Easy Queries-powered, live searchable archives
 */
class jp2_archives {

	/**
	 * Change the callback function for rendering Easy Queries loop
	 *
	 * @uses "caldera_easy_queries_loop_callback" filter
	 *
	 * @param $slug
	 *
	 * @return array
	 */
	static public function set_loop_cb( $slug ) {

		return array( __CLASS__, 'loop' );

	}

	/**
	 * Do the Easy Queries Loop
	 *
	 * @param $query
	 */
	static public function  loop( $query ) {
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/content', 'single' );
			}

		}

	}

	/**
	 * Reset the query args based on the search form atts to pick up taxonomy/term
	 *
	 * @uses "caldera_easy_queries_args"
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	static public function args( $args ) {

		if ( isset( $_POST[ 'jp2term' ] ) ) {
			if ( false !== strpos( $_POST[ 'jp2term' ], ',' ) ) {
				$terms = explode( ',', $_POST[ 'jp2term' ] );
				$args[ 'tax_query' ][0]['terms'] = $terms;

			}else{
				$args[ 'tax_query' ][0]['terms'][0] = trim( strip_tags( $_POST['jp2term']));
			}

		}

		if ( isset( $_POST[ 'jp2tax' ] ) ) {
			$args[ 'tax_query' ][0]['taxonomy'] = trim( strip_tags( $_POST['jp2tax']));
		}


		return $args;
	}

	/**
	 * Add atts to search form so we have them as POST data
	 *
	 * @uses "caldera_easy_queries_search_atts" filter
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	static public function atts($atts) {
		global $wp_query;
		if ( is_category() ) {
			$term             = $wp_query->query['category_name'];
			$tax = 'category';
		}elseif( is_tag() ) {
			$term = $wp_query->query['tag'];
			$tax = 'post_tag';
		}else{
			$term = $tax = false;
		}

		if ( false !== strpos( $term, '/' ) ) {
			$term = wp_json_encode( explode( '/', $term ) );
		}

		$atts['data-jp2term'] = $term;
		$atts[ 'data-jp2tax' ] = $tax;
		return $atts;
	}

	/**
	 * Swap archive template for term archives
	 *
	 *
	 * @uses "category_template" filter
	 * @uses "tag_template" filter
	 *
	 * Doing this here for saftey as this class/file is only included if Easy Queries and Caldera Forms are active
	 *
	 * @return string
	 */
	static public function load_term_template() {

		$template = dirname( __FILE__ ) . '/templates/archive-terms.php';
		return $template;
	}

	/**
	 * Swap index template to create searchable index
	 *
	 *
	 * @uses "index_template" filter

	 * Doing this here for saftey as this class/file is only included if Easy Queries and Caldera Forms are active
	 *
	 * @return string
	 */
	static public function load_frontpage_template( $template) {
		if ( is_home() ) {
			$template = dirname( __FILE__ ) . '/templates/home.php';
		}

		return $template;
	}



}


