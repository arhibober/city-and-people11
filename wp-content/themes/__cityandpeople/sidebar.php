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
			$queried_object = get_queried_object();
			$taxonomies = get_terms([
				'taxonomy'     => 'city_object_taxonomy',
				'type'         => 'city_object',
				'orderby'      => 'name',
				'order'        => 'ASC',
				'hide_empty'   => 0,
				'hierarchical' => 1,
				'show_count' => 1,
				'pad_counts' => 0,
				'child_of' => $queried_object->term_id
				// полный список параметров смотрите в описании функции http://wp-kama.ru/function/get_terms
			]);
			$tax_hierarchies = array();
			Hierarchical::sort_terms_hierarchicaly($taxonomies, $tax_hierarchies);
			echo '<h4>';
			_e('Object categories:');
			echo '</h4>
			<ul>';
			Hierarchical::child_list($tax_hierarchies);
			?>
            <button><?php _e('Apply filter'); ?></button>
            <input type='hidden' name='action' value='myfilter' />
    </form>
</div>