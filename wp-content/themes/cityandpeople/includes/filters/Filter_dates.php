<?php
class Filter_dates
{
	public function __construct()
	{
	}
	public function my_filter_function()
	{
		// create $args['meta_query'] array if one of the following fields is filled
		$args = [];
		$address = get_site_url() . '/filter_pagination?';
		if (isset($_POST['new_date']) && $_POST['new_date'] || isset($_POST['old_date']) && $_POST['old_date']) {
			$args['meta_query'] = array('relation' => 'AND'); // AND means that all conditions of meta_query should be true
		}

		// if both minimum price and maximum price are specified we will use BETWEEN comparison
		if (isset($_POST['new_date']) && $_POST['new_date'] && isset($_POST['old_date']) && $_POST['old_date']) {
			$args['meta_query'][] = array(
				'key' => 'дата',
				'value' => array($_POST['old_date'], $_POST['new_date']),
				'type' => 'date',
				'compare' => 'between'
			);
			$address .= 'old_date=' . $_POST['old_date'] . '&new_date' . $_POST['new_date'];
		} else {
			// if only min price is set
			if (isset($_POST['old_date']) && $_POST['old_date']) {
				$args['meta_query'][] = array(
					'key' => 'дата',
					'value' => $_POST['old_date'],
					'type' => 'date',
					'compare' => '>'
				);
				$address .= 'old_date=' . $_POST['old_date'];
			}

			// if only max price is set
			if (isset($_POST['new_date']) && $_POST['new_date']) {
				$args['meta_query'][] = array(
					'key' => 'дата',
					'value' => $_POST['new_date'],
					'type' => 'date',
					'compare' => '<'
				);
				$address .= 'new_date=' . $_POST['new_date'];
			}
		}
		$args['post_type'] = "city_object";
		$taxonomies = "";
		if (isset($_POST['taxonomies'])) {
			if (count($_POST['taxonomies']) > 0) {
				$args['tax_query'][0]['taxonomy'] = 'city_object_taxonomy';
				foreach ($_POST['taxonomies'] as $taxonomy) {
					$args['tax_query'][0]['terms'][] = $taxonomy;
					$address .= '&taxonomies[]=' . $taxonomy;
				}
			}
		}
		if (isset($_POST['author'])) {
			$args['author_name'] = $_POST['author'];
			$address .= '&author=' . $_POST['author'];
		}
		if (isset($_POST['city_object_taxonomy'])) {
			$args['tax_query'][0]['terms'][] = $_POST['city_object_taxonomy'];
			$address .= '&taxonomies[]=' . $_POST['city_object_taxonomy'];
		}
		if (isset($_POST['search'])) {
			$search_request = str_replace('+', ' ', $_POST['search']);
			$args['s'] = $search_request;
			$address .= '&s1=' . $search_request;
		}
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
			'current' => max(1, get_query_var('page')),
			'before_page_number' => '&nbsp;',
			'total' => $query->max_num_pages,
		]);
		die();
	}
}