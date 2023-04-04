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

    <hr />

    <!-- Date/Time -->
    <p><?php the_time(get_option('date_format'));
			echo " ";
			the_time(get_option('time_format')); ?></p>

    <hr />

    <!-- Preview Image -->
    <!-- <img class="img-fluid rounded" src="http://placehold.it/900x300" alt=""> -->
    <?php
		if (has_post_thumbnail()) {
			the_post_thumbnail('full', ['class' => 'card-img-top']);
		} else {
			echo '<img src="/city-and-people11/wp-content/uploads/2022/08/IMG_0285.jpg" class="card-img-top wp-post-image" alt="" loading="lazy">';
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

    <!-- Pagination -->
    <ul class='pagination justify-content-center mb-4'>
        <li class='page-item'>
            <?php previous_post_link(); ?>
        </li>
        <li class='page-item'>
            <?php next_post_link(); ?>
        </li>
    </ul>
    <?php
		$date_symbol = substr(get_option('date_format'), 1, 2);
		echo '<h3>';
		_e('Key dates');
		echo '</h3>' . get_field('дата') . '<br/>';
		// необязательно, но в некоторых случаях без этого не обойтись
		global $post;

		// тут можно указать post_tag (подборка постов по схожим меткам) или даже массив array( 'category', 'post_tag' ) - подборка и по меткам и по категориям
		$related_tax = 'city_object_taxonomy';

		// получаем ID всех элементов (категорий, меток или таксономий), к которым принадлежит текущий пост
		$cats_tags_or_taxes = wp_get_object_terms($post->ID, $related_tax, array('fields' => 'ids'));

		// массив параметров для WP_Query
		$args = array(
			'posts_per_page' => -1, // сколько похожих постов нужно вывести,
			'tax_query' => array(
				array(
					'taxonomy' => $related_tax,
					'field' => 'id',
					'include_children' => true, // нужно ли включать посты дочерних рубрик
					'terms' => $cats_tags_or_taxes,
					'operator' => 'IN', // если пост принадлежит хотя бы одной рубрике текущего поста, он будет отображаться в похожих записях, укажите значение AND и тогда похожие посты будут только те, которые принадлежат каждой рубрике текущего поста
					'post__not_in' => array($post->ID)
				)
			),
			'post__not_in' => array($post->ID)
		);
		$my_query = new WP_Query($args);
		$taxonomies = '';
		$taxonomies = get_the_terms(get_the_ID(), 'city_object_taxonomy');
		if ($taxonomies != '') {
			$i = 0;

			// так как функция вернула массив, то логично будет прокрутить его через foreach()
			foreach ($taxonomies as $taxonomy) {
				echo '<a href="' . get_term_link($taxonomy) . '">' . $taxonomy->name . '</a>';
				if ($i != count($taxonomies) - 1)
					echo ', ';
				$i++;
			}
		}
		if (has_tag()) {
			echo '<h3>';
			_e('Tags');
			echo '</h3>';
			the_tags('', ', ');
		}

		// если посты, удовлетворяющие нашим условиям, найдены
		if ($my_query->have_posts()) :

			// выводим заголовок блока похожих постов
			echo '<h3>';
			_e('Similar Objects');
			echo '</h3>';

			// запускаем цикл
			while ($my_query->have_posts()) : $my_query->the_post();
				// в данном случае посты выводятся просто в виде ссылок
				echo '<a href="' . get_permalink($my_query->post->ID) . '">' . $my_query->post->post_title . '</a><br/>';
			endwhile;
		endif;

		// не забудьте про эту функцию, её отсутствие может повлиять на другие циклы на странице
		wp_reset_postdata();
		$args = array(
			'posts_per_page' => -1,
			'post_type'  => 'city_object',
			'meta_query' => array(
				array(
					'key'     => 'дата',
					'value' => substr(get_post_meta($post->ID, 'дата')[0], 0, 4),
					'compare' => 'LIKE'
				)
			),
			'post__not_in' => array($post->ID)
		);
		$my_query = new WP_Query($args);

		// если посты, удовлетворяющие нашим условиям, найдены
		if ($my_query->have_posts()) :

			// выводим заголовок блока похожих постов
			echo '<h3>';
			_e('Similar in date Objects');
			echo '</h3>';

			// запускаем цикл
			while ($my_query->have_posts()) : $my_query->the_post();
				// в данном случае посты выводятся просто в виде ссылок
				echo '<a href="' . get_permalink($my_query->post->ID) . '">' . $my_query->post->post_title . '</a><br/>';
			endwhile;
		endif;

		// не забудьте про эту функцию, её отсутствие может повлиять на другие циклы на странице
		wp_reset_postdata();
		?>
    <?php
		$images = get_field('галерея_обєкту');
		$size = 'small'; // (thumbnail, medium, large, full или произвольный размер)
		if ($images) : ?>
    <ul class='object-gallery owl-theme city-people-gallery'>
        <?php foreach ($images as $image) : ?>
        <li class='item'>
            <a href='<?php echo $image['url']; ?>'
                alt='<?php echo get_post_meta($image['ID'], '_wp_attachment_image_alt')[0]; ?>'
                title='<?php echo get_the_excerpt($image['ID']) ?>'>
                <?php echo wp_get_attachment_image($image['ID'], $size) ?>
            </a>
            <div><?php echo get_the_content(NULL, NULL, $image['ID']) ?></div>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif;
		if (get_post_meta($post->ID, 'виноски', true) !== '') {
			echo '<h3>';
			_e('Sources');
			echo '</h3>';
			while (have_rows('виноски')) :
				the_row();
				echo "<a href='" . get_sub_field('виноска')['url'] . "'>" . get_sub_field('виноска')['title'] . '</a><br/>';
			endwhile;
		}
		if (get_post_meta($post->ID, 'дивись_також', true) !== '') {
			echo '<h3>';
			_e('See also');
			echo '</h3>';
			while (have_rows('дивись_також')) :
				the_row();
				echo "<a href='" . get_sub_field('url') . "'>" . get_sub_field('title') . '</a><br/>';
			endwhile;
		}
		if (strstr(get_the_content(), 'map_center')) {
			$wide = strstr(substr(strstr(get_the_content(), 'map_center'), 12, strlen(strstr(get_the_content(), "map_center")) - 12), ",", true);
			$long = strstr(substr(strstr(strstr(get_the_content(), 'map_center'), ','), 1, strlen(strstr(strstr(get_the_content(), "map_center"), ",")) - 1), '"', true);
			delete_post_meta($post->ID, 'широта');
			delete_post_meta($post->ID, 'довгота');
			add_post_meta($post->ID, 'широта', $wide);
			add_post_meta($post->ID, 'довгота', $long);
			echo '<h3>';
			_e('The Nearest Objects');
			echo '</h3>					
				<p>';
			_e('Choose a diapason');
			echo '</p>
				<form action="' . site_url() . '/wp-admin/admin-ajax.php" method="POST" id="diapason_form">
				<input type="range" name="diapason" id="diapason" min="0" max="60">
				<span id="range_value">30</span>&nbsp;';
			_e('km');
			echo '<input type="hidden" name="current_id" value="' . $post->ID . '"/>
				<input type="hidden" name="action" id="action" value="my_nearest">
				<div id="nearest">';
			$args = array(
				'post_type' => 'city_object',
				'post__not_in' => array($post->ID),
				'posts_per_page' => -1
			);
			$wide = strstr(substr(strstr(get_the_content(null, null, $post->ID), 'map_center'), 12, strlen(strstr(get_the_content(null, null, $post->ID), 'map_center')) - 12), ',', true);
			$long = strstr(substr(strstr(strstr(get_the_content(null, null, $post->ID), 'map_center'), ','), 1, strlen(strstr(strstr(get_the_content(null, null, $post->ID), 'map_center'), ',')) - 1), '"', true);
			$my_query = new WP_Query($args);
			$content_only = array();
			if ($my_query->have_posts()) {
				while ($my_query->have_posts()) {
					$my_query->the_post();
					if (strstr($my_query->post->post_content, 'map_center'))
						$content_only[$my_query->post->ID] = $my_query->post->post_content;
				}
				uasort(
					$content_only,
					function ($content1, $content2) use ($wide, $long) {
						$wide_near1 = strstr(substr(strstr($content1, 'map_center'), 12, strlen(strstr($content1, 'map_center')) - 12), ',', true);
						$long_near1 = strstr(substr(strstr(strstr($content1, 'map_center'), ','), 1, strlen(strstr(strstr($content1, 'map_center'), ',')) - 1), '"', true);
						$is_near1 = 12742000 * asin(sqrt(pow(sin(($wide_near1 - $wide) * pi() / 360), 2) + cos($wide_near1 * pi() / 180) * cos($wide * pi() / 180) * pow(sin(($long_near1 - $long) * pi() / 360), 2)));
						$wide_near2 = strstr(substr(strstr($content2, 'map_center'), 12, strlen(strstr($content2, 'map_center')) - 12), ',', true);
						$long_near2 = strstr(substr(strstr(strstr($content2, 'map_center'), ','), 1, strlen(strstr(strstr($content2, 'map_center'), ',')) - 1), '"', true);
						$is_near2 = 12742000 * asin(sqrt(pow(sin(($wide_near2 - $wide) * pi() / 360), 2) + cos($wide_near2 * pi() / 180) * cos($wide * pi() / 180) * pow(sin(($long_near2 - $long) * pi() / 360), 2)));
						if ($is_near1 == $is_near2) {
							return 0;
						}
						if ($is_near1 > $is_near2) {
							return 1;
						}
						if ($is_near1 < $is_near2) {
							return -1;
						}
					}
				);
				$i = 0;
				foreach ($content_only as $id => $content) {
					$wide_near = strstr(substr(strstr($content, 'map_center'), 12, strlen(strstr($content, 'map_center')) - 12), ',', true);
					$long_near = strstr(substr(strstr(strstr($content, 'map_center'), ','), 1, strlen(strstr(strstr($content, 'map_center'), ',')) - 1), '"', true);
					$distance_near = 12742 * asin(sqrt(pow(sin(($wide_near - $wide) * pi() / 360), 2) + cos($wide_near * pi() / 180) * cos($wide * pi() / 180) * pow(sin(($long_near - $long) * pi() / 360), 2)));
					if ($distance_near <= 30) {
						$title = get_the_title($id);
						$link = get_permalink($id);
						echo "<a href = '" . $link . "'>" . $title . '</a> - ' . round($distance_near, 1) . '&nbsp;';
						_e('km');
						echo '<br/>';
						$i++;
					}
				}
				if ($i == 0) {
					_e('There are not objects in the given distanse from the given object.');
				}
			}
			echo '</div>
				</form>';
		}
		wp_reset_postdata();
		echo '<h3>';
		_e('The Nearest Objects by date');
		echo '</h3>					
			<p>';
		_e('Choose a diapason');
		echo '</p>
			<form action="' . site_url() . '/wp-admin/admin-ajax.php" method="POST" id="diapason_date_form">
			<input type="range" name="diapason_date" id="diapason_date" min="0" max="60">
			<span id="range_value_date">30</span>&nbsp;';
		_e('years');
		echo '<input type="hidden" name="current_id_date" value="' . $post->ID . '"/>
			<input type="hidden" name="action" id="action_date" value="my_nearest_dates">
			<div id="nearest_dates">';
		$other_posts = get_posts(['exclude' => $post->ID, 'post_type' => 'city_object', 'posts_per_page' => -1]);
		uasort(
			$other_posts,
			function ($posts1, $posts2) use ($post) {
				$near_date1 = strtotime(get_post_meta($posts1->ID, 'дата')[0]) - strtotime(get_post_meta($post->ID, 'дата')[0]);
				$near_date2 = strtotime(get_post_meta($posts2->ID, 'дата')[0]) - strtotime(get_post_meta($post->ID, 'дата')[0]);
				if ($near_date1 == $near_date2) {
					return 0;
				}
				if ($near_date1 < $near_date2) {
					return 1;
				}
				if ($near_date1 > $near_date2) {
					return -1;
				}
			}
		);
		$i = 0;
		$is_positive = true;
		foreach ($other_posts as $other_post) {
			$near_date = strtotime(get_post_meta($other_post->ID, 'дата')[0]) - strtotime(get_post_meta($post->ID, 'дата')[0]);
			if (abs($near_date) <= 946728000) {
				if (($i == 0) && ($near_date > 0)) {
					echo "<h4>";
					_e('The later objects');
					echo "</h4>";
				}
				if (($near_date < 0) && ($is_positive)) {
					echo "<h4>";
					_e('The earler objects');
					echo "</h4>";
					$is_positive = false;
				}
				$title = $other_post->post_title;
				$link = '/city-and-people11/' . $other_post->post_name;
				echo "<a href = '" . $link . "'>" . $title . '</a> - ' . get_post_meta($other_post->ID, 'дата')[0] . '&nbsp;';
				echo '<br/>';
				$i++;
			}
		}
		if ($i == 0) {
			_e('There are not objects in the given age difference from the given object.');
		}
		echo '</div>
			</form>';
		wp_reset_postdata();
		$terms = get_the_terms($post->ID, 'city_object_taxonomy');
		$args = array();
		$args['post_type'] = 'city_object';
		$args['tax_query'][0]['taxonomy'] = 'city_object_taxonomy';
		$args['post__not_in'] = array($post->ID);
		$args['posts_per_page'] = -1;
		if (gettype($terms) == 'array')
			foreach ($terms as $term) {
				if ($term->parent == get_term_by('slug', 'zviazuiuchi-taksonomii', 'city_object_taxonomy')->term_id) {
					$args['tax_query'][0]['terms'][] = $term->term_id;
				}
			}
		$my_query = new WP_Query($args);
		if ($my_query->have_posts()) {
			echo '<h3>';
			_e('Connected Posts');
			echo '</h3>';
			while ($my_query->have_posts()) {
				$my_query->the_post();
				echo "<a href = '" . get_permalink($my_query->post->ID) . "'>" . $my_query->post->post_title . '</a>';
			}
			wp_reset_postdata();
		}
		$children_document = false;
		$term_document = get_term_by('slug', 'dokument', 'city_object_taxonomy');
		$term_children = get_term_children($term_document->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($post->ID, 'city_object_taxonomy', $term_child)) {
				$children_document = true;
				break;
			}
		}
		$children_people = false;
		$term_people = get_term_by('slug', 'liudyna', 'city_object_taxonomy');
		$term_children = get_term_children($term_people->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($post->ID, 'city_object_taxonomy', $term_child)) {
				$children_people = true;
				break;
			}
		}
		if ((!is_object_in_term($post->ID, 'city_object_taxonomy', 'dokument')) && (!$children_document) && (!is_object_in_term($post->ID, 'city_object_taxonomy', 'liudyna')) && (!$children_people)) {
			if (get_post_meta($post->ID, 'мапа', true) !== '') {
				echo '<h3>';
				_e('Map');
				echo '</h3>';
				$iframe = get_field('мапа');

				// Use preg_match to find iframe src.
				preg_match('/src="(.+?)"/', $iframe, $matches);
				$src = $matches[1];

				// Add extra parameters to src and replace HTML.
				$params = array(
					'controls'  => 0,
					'hd'        => 1,
					'autohide'  => 1
				);
				$new_src = add_query_arg($params, $src);
				$iframe = str_replace($src, $new_src, $iframe);

				// Add extra attributes to iframe HTML.
				$attributes = 'frameborder="0"';
				$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);

				// Display customized HTML.
				echo $iframe;
			}
			if (get_post_meta($post->ID, 'широта', true) !== '') {
				echo '<h3>';
				_e('Latitude');
				echo '</h3>' . get_field('широта');
			}
			if (get_post_meta($post->ID, 'довгота', true) !== '') {
				echo '<h3>';
				_e('Longitude');
				echo '</h3>' . get_field('довгота');
			}
		}

		$pid = $post->ID;
		if ((is_object_in_term($pid, 'city_object_taxonomy', 'liudyna')) || $children_people) {
			if (get_post_meta($pid, 'стать', true) !== '') {
				echo '<h3>';
				_e('Sex');
				echo '</h3>' . get_field('стать');
			}
			if (get_post_meta($pid, 'батько', true) !== '') {
				$father_id = get_field('батько', false, false);
				echo '<h3>';
				_e('Father');
				echo '</h3><a href=' . get_the_permalink($father_id) . "'>" . get_the_title($father_id) . "</a>";
			}
			if (get_post_meta($pid, 'мати', true) !== '') {
				$mother_id = get_field('мати', false, false);
				echo '<h3>';
				_e('Mother');
				echo '</h3><a href=' . get_the_permalink($mother_id) . "'>" . get_the_title($mother_id) . "</a>";
			}
			if (get_post_meta($pid, 'чоловікжінка', true) !== '') {
				$spouse_id = get_field('чоловікжінка', false, false);
				echo '<h3>';
				_e('Spouse');
				echo '</h3><a href=' . get_the_permalink($spouse_id) . "'>" . get_the_title($spouse_id) . "</a>";
			}
			if (get_post_meta($pid, 'дата_народження', true) !== '') {
				echo '<h3>';
				_e('Birthday');
				echo '</h3>' . get_field('дата_народження') .
					'<h3>';
				_e('The Nearest People by birthday');
				echo '</h3>					
					<p>';
				_e('Choose a diapason');
				echo '</p>
				<form action="' . site_url() . '/wp-admin/admin-ajax.php" method="POST" id="diapason_birthday_form">
				<input type="range" name="diapason_birthday" id="diapason_birthday" min="0" max="60">
				<span id="range_value_birthday">30</span>&nbsp;';
				_e('years');
				echo '<input type="hidden" name="current_id_birthday" value="' . $post->ID . '"/>
				<input type="hidden" name="action" id="action_birhday" value="my_nearest_birthdays">
				<div id="nearest_birthdays">';
				$other_posts = get_posts(['exclude' => $pid, 'post_type' => 'city_object', 'posts_per_page' => -1, 'meta_key' => 'дата_народження', 'meta_value' => '', 'meta_compare' => '!=']);
				uasort(
					$other_posts,
					function ($posts1, $posts2) use ($pid) {
						$near_date1 = strtotime(get_post_meta($posts1->ID, 'дата_народження')[0]) - strtotime(get_post_meta($pid, 'дата_народження')[0]);
						$near_date2 = strtotime(get_post_meta($posts2->ID, 'дата_народження')[0]) - strtotime(get_post_meta($pid, 'дата_народження')[0]);
						if ($near_date1 == $near_date2) {
							return 0;
						}
						if ($near_date1 < $near_date2) {
							return 1;
						}
						if ($near_date1 > $near_date2) {
							return -1;
						}
					}
				);
				$i = 0;
				$is_positive = true;
				foreach ($other_posts as $other_post) {
					$near_date = strtotime(get_post_meta($other_post->ID, 'дата_народження')[0]) - strtotime(get_post_meta($pid, 'дата_народження')[0]);
					if (abs($near_date) <= 946728000) {
						if (($i == 0) && ($near_date > 0)) {
							echo "<h4>";
							_e('The janger people');
							echo "</h4>";
						}
						if (($near_date < 0) && ($is_positive)) {
							echo "<h4>";
							_e('The older people');
							echo "</h4>";
							$is_positive = false;
						}
						$title = $other_post->post_title;
						$link = '/city-and-1/' . $other_post->post_name;
						echo "<a href = '" . $link . "'>" . $title . '</a> - ' . get_post_meta($other_post->ID, 'дата_народження')[0] . '&nbsp;';
						echo '<br/>';
						$i++;
					}
				}
				if ($i == 0) {
					_e('There are not people in the given age difference from the given human.');
				}
				echo '</div>
					</form>';
				wp_reset_postdata();
			}
			if (get_post_meta($pid, 'місце_народження', true) !== '') {
				$birth_id = get_field('місце_народження', false, false);
				$birth_type = get_the_terms($birth_id, 'city_object_taxonomy');
				echo '<h3>';
				_e('Place of Birth');
				echo '</h3><a href=' . get_the_permalink($birth_id) . "'>" . $birth_type[0]->name . " " . get_the_title($birth_id) . "</a>";
			}
			if (get_post_meta($pid, 'дата_смерті', true) !== '') {
				echo '<h3>';
				_e('Date of Die');
				echo '</h3>' . get_field('дата_смерті') .
					'<h3>';
				_e('The Nearest People by die date');
				echo '</h3>					
					<p>';
				_e('Choose a diapason');
				echo '</p>
				<form action="' . site_url() . '/wp-admin/admin-ajax.php" method="POST" id="diapason_die_form">
				<input type="range" name="diapason_die" id="diapason_die" min="0" max="60">
				<span id="range_value_die">30</span>&nbsp;';
				_e('years');
				echo '<input type="hidden" name="current_id_die" value="' . $post->ID . '"/>
				<input type="hidden" name="action" id="action_die" value="my_nearest_die">
				<div id="nearest_die">';
				$other_posts = get_posts(['exclude' => $pid, 'post_type' => 'city_object', 'posts_per_page' => -1, 'meta_key' => 'дата_смерті', 'meta_value' => '', 'meta_compare' => '!=']);
				uasort(
					$other_posts,
					function ($posts1, $posts2) use ($pid) {
						$near_date1 = strtotime(get_post_meta($posts1->ID, 'дата_смерті')[0]) - strtotime(get_post_meta($pid, 'дата_смерті')[0]);
						$near_date2 = strtotime(get_post_meta($posts2->ID, 'дата_смерті')[0]) - strtotime(get_post_meta($pid, 'дата_смерті')[0]);
						if ($near_date1 == $near_date2) {
							return 0;
						}
						if ($near_date1 < $near_date2) {
							return 1;
						}
						if ($near_date1 > $near_date2) {
							return -1;
						}
					}
				);
				$i = 0;
				$is_positive = true;
				foreach ($other_posts as $other_post) {
					$near_date = strtotime(get_post_meta($other_post->ID, 'дата_смерті')[0]) - strtotime(get_post_meta($pid, 'дата_смерті')[0]);
					if (abs($near_date) <= 946728000) {
						if (($i == 0) && ($near_date > 0)) {
							echo "<h4>";
							_e('The people that died later');
							echo "</h4>";
						}
						if (($near_date < 0) && ($is_positive)) {
							echo "<h4>";
							_e('The people that died earlier');
							echo "</h4>";
							$is_positive = false;
						}
						$title = $other_post->post_title;
						$link = '/city-and-people11/' . $other_post->post_name;
						echo "<a href = '" . $link . "'>" . $title . '</a> - ' . get_post_meta($other_post->ID, 'дата_смерті')[0] . '&nbsp;';
						echo '<br/>';
						$i++;
					}
				}
				if ($i == 0) {
					_e('There are not objects in the given die date difference from the given human.');
				}
				echo '</div>
					</form>';
				wp_reset_postdata();
			}
			if (get_post_meta($pid, 'місце_смерті', true) !== '') {

				$birth_id = get_field('місце_смерті', false, false);
				$birth_type = get_the_terms($birth_id, 'city_object_taxonomy');
				echo '<h3>';
				_e('Place of Die');
				echo '</h3><a href=' . get_the_permalink($birth_id) . "'>" . $birth_type[0]->name . " " . get_the_title($birth_id) . "</a>";
			}
			$args1 = array();
			$args1['post_type'] = 'city_object';
			$args1['post__not_in'] = array($pid);
			$args1['posts_per_page'] = -1;
			$my_query1 = new WP_Query($args1);
			$children = 0;
			if ($my_query1->have_posts()) {
				while ($my_query1->have_posts()) {
					$my_query1->the_post();
					if (($pid == get_field('мати', $my_query1->post->ID, false)) || ($pid == get_field('батько', $my_query1->post->ID, false))) {
						$children++;
					}
				}
				if ($children > 0) {
					echo '<h3>';
					if ($children == 1) {
						while ($my_query1->have_posts()) {
							$my_query1->the_post();
							if (($pid == get_field('мати', $my_query1->post->ID, false)) || ($pid == get_field('батько', $my_query1->post->ID, false))) {
								switch (get_field('стать', $my_query1->post->ID)) {
									case 'Чоловіча':
										_e('Son');
										break;
									case 'Жіноча':
										_e('Daughter');
										break;
									default:
										_e('Child');
										break;
								}
								echo '</h3><a href="' . get_the_permalink($my_query1->post->ID) . '">' . $my_query1->post->post_title . '</a>';
								break;
							}
						}
					} else {
						_e('Children');
						echo '</h3>';
						$is_last = '';
						while ($my_query1->have_posts()) {
							$my_query1->the_post();
							if (($pid == get_field('мати', $my_query1->post->ID, false)) || ($pid == get_field('батько', $my_query1->post->ID, false))) {
								echo '<a href="' . get_the_permalink($my_query1->post->ID) . '">' . $my_query1->post->post_title . '</a>';
								$is_last++;
								if ($is_last != $children)
									echo ', ';
							}
						}
					}
				}
			}
			$args1 = array();
			$args1['post_type'] = 'city_object';
			$args1['post__not_in'] = array($pid);
			$args1['posts_per_page'] = -1;
			$my_query1 = new WP_Query($args1);
			$brothers = array();
			$sisters = array();
			$bsus = array();
			if (get_post_meta($pid, 'мати', true) !== '') {
				if ($my_query1->have_posts()) {
					while ($my_query1->have_posts()) {
						$my_query1->the_post();
						if (get_field('мати', $pid, false) == get_field('мати', $my_query1->post->ID, false)) {
							switch (get_field('стать', $my_query1->post->ID)) {
								case 'Чоловіча':
									$brothers[$my_query1->post->ID] = $my_query1->post->post_title;
									break;
								case 'Жіноча':
									$sisters[$my_query1->post->ID] = $my_query1->post->post_title;
									break;
								default:
									$bsus[$my_query1->post->ID] = $my_query1->post->post_title;
									break;
							}
						}
					}
				}
			}
			if (get_post_meta($pid, 'батько', true) !== '') {
				if ($my_query1->have_posts()) {
					while ($my_query1->have_posts()) {
						$my_query1->the_post();
						if (get_field('батько', $pid, false) == get_field('батько', $my_query1->post->ID, false)) {
							switch (get_field('стать', $my_query1->post->ID)) {
								case 'Чоловіча':
									if (!array_key_exists($my_query1->post->ID, $brothers)) {
										$brothers[$my_query1->post->ID] = $my_query1->post->post_title;
									}
									break;
								case 'Жіноча':
									if (!array_key_exists($my_query1->post->ID, $sisters)) {
										$sisters[$my_query1->post->ID] = $my_query1->post->post_title;
									}
									break;
								default:
									if (!array_key_exists($my_query1->post->ID, $bsus)) {
										$bsus[$my_query1->post->ID] = $my_query1->post->post_title;
									}
									break;
							}
						}
					}
				}
			}
			if (count($brothers) > 0) {
				echo '<h3>';
				if (count($brothers) == 1) {
					_e('Brother');
					foreach ($brothers as $brother_id => $brother_title) {
						echo '</h3><a href="' . get_the_permalink($brother_id) . '">' . $brother_title . '</a>';
					}
				} else {
					_e('Brothers');
					echo '</h3>';
					$i = 0;
					foreach ($brothers as $brother_id => $brother_title) {
						$i++;
						echo '<a href="' . get_the_permalink($brother_id) . '">' . $brother_title . '</a>';
						if ($i < count($brothers))
							echo ', ';
					}
				}
			}
			if (count($sisters) > 0) {
				echo '<h3>';
				if (count($sisters) == 1) {
					_e('Sister');
					foreach ($sisters as $sister_id => $sister_title) {
						echo '</h3><a href="' . get_the_permalink($sister_id) . '">' . $sister_title . '</a>';
					}
				} else {
					_e('Sisters');
					echo '</h3>';
					$i = 0;
					foreach ($sisters as $sister_id => $sister_title) {
						$i++;
						echo '<a href="' . get_the_permalink($sister_id) . '">' . $sister_title . '</a>';
						if ($i < count($sisters))
							echo ', ';
					}
				}
			}
			if (count($bsus) > 0) {
				echo '<h3>';
				if (count($bsus) == 1) {
					_e('Brother/Sister (unknown sex)');
					foreach ($bsus as $bsus_id => $bsus_title) {
						echo '</h3><a href="' . get_the_permalink($bsus_id) . '">' . $bsus_title . '</a>';
					}
				} else {
					_e('Brothers/Sisters (unknown sex)');
					echo '</h3>';
					$i = 0;
					foreach ($bsus as $bsus_id => $bsus_title) {
						$i++;
						echo '<a href="' . get_the_permalink($bsus_id) . '">' . $bsus_title . '</a>';
						if ($i < count($bsus))
							echo ', ';
					}
				}
			}
		}
		$children_house = false;
		$term_house = get_term_by('slug', 'budynok', 'city_object_taxonomy');
		$term_children = get_term_children($term_house->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($pid, 'city_object_taxonomy', $term_child)) {
				$children_house = true;
				break;
			}
		}
		if ((is_object_in_term($pid, 'city_object_taxonomy', 'budynok')) || $children_house) {
			if (get_post_meta($pid, 'адреса', true) !== '') {
				echo '<h3>';
				_e('Address');
				echo '</h3>' . get_field('адреса');
			}
			if (get_post_meta($pid, 'висота', true) !== '') {
				echo '<h3>';
				_e('Height');
				echo '</h3>' . get_field('висота');
			}
		}
		$children_street = false;
		$term_street = get_term_by('slug', 'vulytsia', 'city_object_taxonomy');
		$term_children = get_term_children($term_street->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($pid, 'city_object_taxonomy', $term_child)) {
				$children_street = true;
				break;
			}
		}
		if ((is_object_in_term($pid, 'city_object_taxonomy', 'vulytsia')) || $children_street) {
			if (get_post_meta($pid, 'длина_вулиці', true) !== '') {
				echo '<h3>';
				_e('Street long');
				echo '</h3>' . get_field('длина_вулиці');
			}
		}
		$children_hight_school = false;
		$term_hight_school = get_term_by('slug', 'vnz', 'city_object_taxonomy');
		$term_children = get_term_children($term_hight_school->term_id, 'city_object_taxonomy');
		foreach ($term_children as $term_child) {
			if (is_object_in_term($pid, 'city_object_taxonomy', $term_child)) {
				$children_hight_school = true;
				break;
			}
		}
		if ((is_object_in_term($pid, 'city_object_taxonomy', 'vnz')) || $term_hight_school) {
			if (get_post_meta($pid, 'список_факультетів', true) !== '') {
				echo '<h3>';
				_e('Facultaty List');
				echo '</h3>' . get_field('список_факультетів');
			}
			if (get_post_meta($pid, 'рейтинг', true) !== '') {
				echo '<h3>';
				_e('Rating');
				echo '</h3>' . get_field('рейтинг');
			}
			if (get_post_meta($pid, 'список_ректорів', true) !== '') {
				echo '<h3>';
				_e("Rectors' List");
				echo '</h3>' . get_field('список_ректорів');
			}
		}
		wp_reset_postdata();
		?>
    <!-- Post Author Info -->
    <div class='card'>
        <div class='card-header'>
            <strong>
                <?php _e('Posted by'); ?>
                <a href="<?php echo $author_URL; ?>"><?php the_author(); ?></a>
            </strong>
        </div>
        <div class='card-body'>
            <div class='author-image'>
                <?php echo get_avatar($author_ID, 90, '', false, ['class' => 'img-circle']); ?>
            </div>
            <?php echo nl2br(get_the_author_meta('description')); ?>
        </div>
    </div>

    <!-- Post Single - Author End -->

    <?php
		if (comments_open() || get_comments_number()) {
			comments_template();
		}
	}
	?>
</div>
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
		echo '<ul>';
		$tax_id = array();
		foreach ($taxonomies as $taxonomy) {
			array_push($tax_id, $taxonomy->term_id);
		}
		Hierarchical::child_list($tax_hierarchies, $tax_id);
		?>
        <div id='filter_applay'></div>
        <input type='hidden' name='action' value='myfilter' />
        <input type='radio' name='sort' value='date_posted' /><?php echo _e('By date posted'); ?><br />
        <input type='radio' name='sort' value='ABC' /><?php echo _e('By ABC'); ?><br />
        <input type='radio' name='sort' value='date_city_object' /><?php echo _e('By city object date'); ?><br />
    </form>
</div>