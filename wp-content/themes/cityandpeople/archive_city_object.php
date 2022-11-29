<?php
/*
 * Template Name: Архів міських об'єктів
 * Template post type: page, city_object
*/
get_header('v2'); ?>

<!-- Page Content -->
<div class='container container-up'>

    <!-- Marketing Icons Section -->
    <div class='row'>
        <!-- Blog Entries Column -->
        <div class='col-md-8'>
            <?php
			while (have_posts()) {
				the_post();
				global $post;
				$author_ID = $post->post_author;
				$author_URL = get_author_posts_url($author_ID);
			?>

            <!-- Title -->

            <h1 class='mt-4 mb-3'><?php the_title() ?></h1>

            <!-- Post category: -->
            <h2 class='mt-4'><?php the_category(' ') ?></h2>

            <!-- Author -->
            <p class='lead'>
                <?php _e('by'); ?>
                <a href='<?php echo $author_URL; ?>'><?php the_author(); ?></a>
            </p>

            <hr>

            <!-- Date/Time -->
            <p><?php the_time(get_option('date_format'));
					echo ' ';
					the_time(get_option('time_format')); ?></p>

            <hr>

            <!-- Preview Image -->
            <?php
				if (has_post_thumbnail()) {
					the_post_thumbnail('full', ['class' => 'card-img-top']);
				}
				?>

            <hr />

            <!-- Post Content -->
            <?php
				the_content();
				$defaults = array(
					'before' => '<div class="row justify-content-center align-items-center">' . __('Pages:'),
					'after' => '</div>',
				);

				wp_link_pages($defaults);

				edit_post_link();
				?>

            <hr />

            <!-- Tag cloud -->
            <?php the_tags('', ', '); ?>

            <hr />
            <?PHP
			}
			?>
            <div id='object_archive' class='row'>
                <?php
				$args = array(
					'post_type' => 'city_object',
					'posts_per_page' => get_option('posts_per_page'),
					'paged' => get_query_var('page'),
				);
				$my_query = new WP_Query($args);
				if ($my_query->have_posts()) {
					while ($my_query->have_posts()) {
						$my_query->the_post();
						get_template_part('partials/posts/content', 'excerpt');
					}
				} else {
					get_template_part('partials/posts/content', 'none');
				}
				echo paginate_links([
					'base'    => get_site_url() . '/page/%#%',
					'current' => max(1, get_query_var('page')),
					'total'   => $my_query->max_num_pages,
					'before_page_number' => '&nbsp;',
				]);
				?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer();
?>