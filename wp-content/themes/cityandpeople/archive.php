<?php get_header('v2'); ?>
<div class='container container-archive'>
    <div class='row'>
        <div class='col-md-12'>
            <!-- Title -->
            <h1 class='mt-4 mb-3'>
                <?php
				if (substr(strstr(substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]) - 1), '/'), 0, 8) == '/author/') {
					printf(__('Author: %s', 'striped'), '<span>' . get_the_author() . '</span>');
				} else {
					if (substr(strstr(substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]) - 1), '/'), 0, 22) == '/city_object_taxonomy/') {
						$taxonomies = get_the_terms(get_the_ID(), 'city_object_taxonomy');
						printf(__('Taxonomy: %s', 'striped'), '<span>' . $taxonomies[0]->name . '</span>');
					} else {
						printf(__('Category: %s', 'striped'), '<span>' . get_cat_name(get_the_ID()) . '</span>');
					}
				}
				?>
            </h1>
            <span><?php the_archive_description(); ?></span>
        </div>
    </div>
</div>
<!-- Page Content -->
<div class='container'>

    <!-- Marketing Icons Section -->
    <div class='row'>
        <div class='col-md-8'>
            <!-- Blog Entries Column -->
            <div id="object_archive" class='row'>

                <?php
				if (substr(strstr(substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]) - 1), '/'), 0, 8) == '/author/') {
					$args = array(
						'author_name' => get_the_author(),
						'post_type' => array('post', 'city_object'),
						'posts_per_page' => get_option('posts_per_page'),
					);
					if (isset($_GET['page'])) {
						$args['offset'] = (int)(get_option('posts_per_page')) * ($_GET['page'] - 1);
					}
					$query = new WP_Query($args);
					//echo ' qgo: '.$query->get('offset');
					//echo ' q: ';
					//print_r($query);
					if ($query->have_posts()) {
						while ($query->have_posts()) {
							$query->the_post();
							get_template_part('partials/posts/content', 'excerpt');
						}
					} else {
						get_template_part('partials/posts/content', 'none');
					}
					echo paginate_links([
						'base'    => get_site_url() . '/author/' . get_the_author() . '/?page=%#%',
						'current' => max(1, get_query_var('page')),
						'before_page_number' => '&nbsp;',
						'total'   => $query->max_num_pages,
					]);
				} else {
					global $wp_query;
					if (have_posts()) {
						while (have_posts()) {
							the_post();
							get_template_part('partials/posts/content', 'excerpt');
						}
					} else {
						get_template_part('partials/posts/content', 'none');
					}
					echo paginate_links([
						'before_page_number' => '&nbsp;'
					]);
				}
				?>
            </div>
        </div>
        <?php if (substr(strstr(substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]) - 1), '/'), 0, 22) == '/city_object_taxonomy/') {
		?>
        <!-- Sidebar Widgets Column -->
        <div class='col-md-4'>
            <?php
				if (is_active_sidebar('cityandpeople_sidebar')) {
					dynamic_sidebar('cityandpeople_sidebar');
				}
				$category = get_term_by('slug', strstr(substr(strstr($_SERVER['REQUEST_URI'], '/city_object_taxonomy/'), 22, strlen(strstr($_SERVER['REQUEST_URI'], '/city_object_taxonomy/')) - 22), '/', true), 'city_object_taxonomy')->term_id;
				$categories = array($category);
				?>
            <form action='<?php echo site_url() ?>/wp-admin/admin-ajax.php' method='POST' id='filter'>
                <input type='date' name='old_date' placeholder='<?php _e('The oldest date'); ?>' />
                <input type='date' name='new_date' placeholder='<?php _e('The newsest date'); ?>' />
				<input type='hidden' name='city_object_taxonomy' value='<?php echo $category; ?>'/>
                <?php
					$taxonomies_all = get_terms([
						'taxonomy'     => 'city_object_taxonomy',
						'type'         => 'city_object',
						'orderby'      => 'name',
						'order'        => 'ASC',
						'hide_empty'   => 0,
						'hierarchical' => 1,
						'show_count' => 1,
						'pad_counts' => 0,
						// полный список параметров смотрите в описании функции http://wp-kama.ru/function/get_terms
					]);
					$tax_hierarchies = array();
					Hierarchical::sort_terms_hierarchicaly($taxonomies_all, $tax_hierarchies);
					echo '<h4>';
					_e('Object categories:');
					echo '</h4>
						<ul>';
					Hierarchical::child_list($tax_hierarchies, $categories);
					?>
                <div id='filter_applay'></div>
                <input type='hidden' name='action' value='myfilter' />
            </form>
        </div>
        <?php
		} else
			get_sidebar();
		?>
    </div>
</div>

<?php get_footer(); ?>