<?php
class My_nearest_birthdays
{
	public function __construct()
	{
	}
	public function my_nearest_birthday_function()
	{
		$cidb = $_POST['current_id_birthday'];
		$other_posts = get_posts([
			'exclude' => $cidb, 'post_type' => 'city_object', 'posts_per_page' => -1, 'meta_key' => 'дата_народження', 'meta_value' => '',
			'meta_compare' => '!='
		]);
		uasort(
			$other_posts,
			function ($posts1, $posts2) use ($cidb) {
				$near_date1 = strtotime(get_post_meta($posts1->ID, 'дата_народження')[0]) - strtotime(get_post_meta($cidb, 'дата_народження')[0]);
				$near_date2 = strtotime(get_post_meta($posts2->ID, 'дата_народження')[0]) - strtotime(get_post_meta($cidb, 'дата_народження')[0]);
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
			$near_date = strtotime(get_post_meta($other_post->ID, 'дата_народження')[0]) - strtotime(get_post_meta($cidb, 'дата_народження')[0]);
			if (abs($near_date) <= $_POST['diapason_birthday'] * 31557600) {
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
				$link = '/city-and-people11/' . $other_post->post_name;
				echo "<a href = '" . $link . "'>" . $title . '</a> - ' . get_post_meta($other_post->ID, 'дата')[0] . '&nbsp;';
				echo '<br/>';
				$i++;
			}
		}
		if ($i == 0) {
			_e('There are not people in the given age difference from the given human.');
		}
		wp_reset_postdata();
		wp_die();
		return;
	}
}