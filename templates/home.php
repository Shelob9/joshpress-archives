<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
			if ( have_posts() ) :

				echo Caldera_Forms::render_form( 'CF55f605b723ab9' );
				$slug = 'tax_archives';
				$class =  \calderawp\caeq\cf::get_instance();
				$easy_query = \calderawp\caeq\options::get_single( $slug );
				echo $class->render_easy_query( $easy_query );
			else :
				get_template_part( 'template-parts/content', 'none' );

			endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
