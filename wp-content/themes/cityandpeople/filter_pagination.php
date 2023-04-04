<?php
/*
 * Template Name: Шаблон для пагінаційних сторінок відфільтрованих міських об'єктів
*/
get_header('v2'); ?>

<!-- Page Content -->
<div class='container container-up'>

    <!-- Marketing Icons Section -->
    <div class='row'>
        <!-- Blog Entries Column -->
        <div class='col-md-8'>

            <!-- Title -->

            <h1 class='mt-4 mb-3'>Архів міських об’єктів</h1>


            <div id='object_archive' class='row'>
                <?php
				$args = [];
				$address = get_site_url() . '/filter_pagination?';
				if (isset($_GET['new_date']) && $_GET['new_date'] || isset($_GET['old_date']) && $_GET['old_date']) {
					$args['meta_query'] = array('relation' => 'AND'); // AND means that all conditions of meta_query should be true
				}

				// if both minimum price and maximum price are specified we will use BETWEEN comparison
				if (isset($_GET['new_date']) && $_GET['new_date'] && isset($_GET['old_date']) && $_GET['old_date']) {
					$args['meta_query'][] = array(
						'key' => 'дата',
						'value' => array($_GET['old_date'], $_GET['new_date']),
						'type' => 'date',
						'compare' => 'between'
					);
					$address .= 'old_date=' . $_GET['old_date'] . '&new_date' . $_GET['new_date'];
				} else {
					// if only min price is set
					if (isset($_GET['old_date']) && $_GET['old_date']) {
						$args['meta_query'][] = array(
							'key' => 'дата',
							'value' => $_GET['old_date'],
							'type' => 'date',
							'compare' => '>'
						);
						$address .= 'old_date=' . $_GET['old_date'];
					}

					// if only max price is set
					if (isset($_GET['new_date']) && $_GET['new_date']) {
						$args['meta_query'][] = array(
							'key' => 'дата',
							'value' => $_GET['new_date'],
							'type' => 'date',
							'compare' => '<'
						);
						$address .= 'new_date=' . $_GET['new_date'];
					}
				}
				$args['post_type'] = 'city_object';
				$taxonomies = '';
				if (isset($_GET['taxonomies'])) {
					if (gettype($_GET['taxonomies']) == 'array') {
						if (count($_GET['taxonomies']) > 0) {
							$args['tax_query'][0]['taxonomy'] = 'city_object_taxonomy';
							foreach ($_GET['taxonomies'] as $taxonomy) {
								$address .= '&taxonomies[]=' . $taxonomy;
								$args['tax_query'][0]['terms'][] = $taxonomy;
							}
						}
					}
				}
				if (isset($_GET['author'])) {
					$address .= '&author=' . $_GET['author'];
					$args['author_name'] = $_GET['author'];
				}
				if (isset($_GET['s1'])) {
					$address .= '&s1=' . $_GET['s1'];
					$args['s1'] = $_GET['s1'];
				}
				switch ($_GET['sort']) {
					case 'ABC':
						$args['orderby'] = 'title';
						$args['order'] = 'ASC';
						break;
					case 'date_city_object':
						$args['meta_key']  = 'дата';
						$args['orderby'] = ['meta_value' => 'DESC'];
						break;
					default:
						$args['orderby'] = 'date';
						break;
				}
				$args['posts_per_page'] = get_option('posts_per_page');
				$args['offset'] = (int)(get_option('posts_per_page')) * ($_GET['page1'] - 1);
				$query = new WP_Query($args);
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
						get_template_part('partials/posts/content', 'excerpt');
					}
				} else {
					get_template_part('partials/posts/content', 'none');
				}
				echo paginate_links([
					'base'    => $address . '&page1=%#%',
					'current' => $_GET['page1'],
					'before_page_number' => '&nbsp;',
					'total' => $query->max_num_pages,
				]);
				?>
            </div>
        </div>
        <!-- Sidebar Widgets Column -->
        <div class='col-md-4'>
            <?php
			if (is_active_sidebar('cityandpeople_sidebar')) {
				dynamic_sidebar('cityandpeople_sidebar');
			}
			?>
            <form action='<?php echo site_url() ?>/wp-admin/admin-ajax.php' method='POST' id='filter'>
                <input type='date' name='old_date' placeholder='<?php _e('The oldest date'); ?>' <?php
																									if (isset($_GET['old_date'])) {
																										echo 'value="' . $_GET['old_date'] . '"';
																									}
																									?> />
                <input type='date' name='new_date' placeholder='<?php _e('The newsest date'); ?>' <?php
																									if (isset($_GET['new_date'])) {
																										echo 'value="' . $_GET['new_date'] . '"';
																									}
																									?> />
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
				if (isset($_GET['author'])) {
					echo '<input type="hidden" name="author" value="' . $_GET['author'] . '"/>';
				}
				if (isset($_GET['taxonomies'])) {
					if (gettype($_GET['taxonomies']) == 'array') {
						foreach ($_GET['taxonomies'] as $taxonomy) {
							echo '<input type="hidden" name="taxonomies" value="' . $taxonomy . '"/>';
						}
					}
				}
				if (isset($_GET['s1'])) {
					echo '<input type="hidden" name="s1" value="' . $_GET['s1'] . '"/>';
				}
				$tax_hierarchies = array();
				Hierarchical::sort_terms_hierarchicaly($taxonomies_all, $tax_hierarchies);
				echo '<h4>';
				_e('Object categories:');
				echo '</h4>';
				if (array_key_exists('tax_query', $args)) {
					Hierarchical::child_list($tax_hierarchies, $args['tax_query'][0]['terms']);
				} else {
					Hierarchical::child_list($tax_hierarchies);
				}
				?>
                <div id='filter_applay'></div>
                <input type='hidden' name='action' value='myfilter' />
                <input type='radio' name='sort' value='date_posted' <?php
																	if ($_GET['sort'] == 'date_posted') {
																		echo ' checked';
																	}
																	?> /><?php echo _e('By date posted'); ?><br />
                <input type='radio' name='sort' value='ABC' <?php
															if ($_GET['sort'] == 'ABC') {
																echo ' checked';
															}
															?> /><?php echo _e('By ABC'); ?><br />
                <input type='radio' name='sort' value='date_city_object' <?php
																			if ($_GET['sort'] == 'date_city_object') {
																				echo ' checked';
																			}
																			?> /><?php echo _e('By city object date'); ?><br />
            </form>
        </div>
    </div>
</div>

<?php get_footer();
?>