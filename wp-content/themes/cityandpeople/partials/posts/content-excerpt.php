<!-- Blog Post -->
<div class='col-lg-4 mb-4'>
    <?php if (has_post_thumbnail()) {
		the_post_thumbnail('full', ['class' => 'card-img-top']);
	} else {
		echo '<img src="/city-and-people11/wp-content/uploads/2022/08/IMG_0285-150x150.jpg" class="card-img-top wp-post-image" alt="" loading="lazy" width="341" height="341">';
	}
	?>
    <div class='card h-100'>
        <h4 class='card-header'><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h4>
        <div class='card-footer text-muted'>
            <?php
				if (get_post_type() == 'city_object') {
					$taxonomies = '';
					$taxonomies = get_the_terms(get_the_ID(), 'city_object_taxonomy');
					if ($taxonomies != '') {
						_e('Object Categories: ');
						$i = 0;

						// так как функция вернула массив, то логично будет прокрутить его через foreach()
						foreach ($taxonomies as $taxonomy) {
							echo '<a href="' . get_term_link($taxonomy) . '">' . $taxonomy->name . '</a>';
							if ($i != count($taxonomies) - 1)
								echo ', ';
							$i++;
						}
					}
				}
				else {
					_e('Post category: ');
				};
			echo ' ';
			the_category(' ') ?>
            <?php _e('Posted on');
			echo ' ' . get_the_date() . ' ';
			_e('by'); ?>
            <a href='<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>'><?php the_author() ?></a>
            <?php _e('Comments: ', 'cityandpeople');
			comments_number("0");
			the_excerpt();
			?>
            <a href="<?php the_permalink() ?>" class="btn btn-primary"><?php _e("Read More &rarr;") ?></a>
        </div>
    </div>
</div>