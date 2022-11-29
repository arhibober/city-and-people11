<!-- Sidebar Widgets Column -->
<div class='col-md-4'>
    <?php
	if (is_active_sidebar('cityandpeople_sidebar')) {
		dynamic_sidebar('cityandpeople_sidebar');
	}
	?>
    <form action='<?php echo site_url() ?>/wp-admin/admin-ajax.php' method='POST' id='filter'>
        <input type='date' name='old_date' placeholder='<?php _e('The oldest date'); ?>' />
        <input type='date' name='new_date' placeholder='<?php _e('The newsest date'); ?>' />
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
		Hierarchical::child_list($tax_hierarchies);
		if (substr(strstr(substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]) - 1), '/'), 0, 8) == '/author/') {
			echo '<input type="hidden" name="author" value="' . strstr(substr(strstr($_SERVER['REQUEST_URI'], '/author/'), 8, strlen(strstr($_SERVER['REQUEST_URI'], '/author/')) - 8), '/', true) . '"/>';
		}
		if (substr(strstr(substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"]) - 1), '/'), 0, 5) == '/tag/') {
			echo '<input type="hidden" name="tag" value="' . strstr(substr(strstr($_SERVER['REQUEST_URI'], '/tag/'), 5, strlen(strstr($_SERVER['REQUEST_URI'], '/tag/')) - 5), '/', true) . '"/>';
		}
		if (isset($_GET['s'])) {
			echo '<input type="hidden" name="search" value="' . $_GET['s'] . '"/>';
		}
		?>
        <div id='filter_applay'></div>
        <input type='hidden' name='action' value='myfilter' />
    </form>
</div>