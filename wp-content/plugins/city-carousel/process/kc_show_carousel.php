<?php

function kc_show_carousel($content)
{
    $args = [
        'post_type' => $content['post_type'],
        'tag_in' => $content['tag_in'],
        'showposts' => $content['showposts'],
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ];
    if (false != get_option('kc_category_name')) {
        $args['tax_query'][0]['field'] = 'name';
        $args['tax_query'][0]['taxonomy'] = 'city_object_taxonomy';
        $args['tax_query'][0]['terms'] = $content['kc_category_name'];
    }
    $query = new WP_Query($args);
    $html = '';
    if ($query->have_posts()) {
        $html = '<section id="demos">
    <div class="row">
        <div class="large-12 columns">
            <div class="owl-carousel owl-theme">';
        while ($query->have_posts()) {
            $query->the_post();
            $html .= '<a href="' . get_permalink($query->post->ID) . '">';
            $html .= '<div class="item" style="background:url(';
            if (has_post_thumbnail()) {
                $html .= get_the_post_thumbnail_url($query->post->ID, 'thumbnail');
            } else {
                $html .= '/city-and-people11/wp-content/uploads/2022/08/IMG_0285-150x150.jpg';
            }
            $html .= ') #80808052 center;background-size:cover;"><h5>';
            $html .= $query->post->post_title;
            $html .= '</h5></div></a>';
        }
        $html .= ' </div>
    </div>
	</div>
	</section>';
    }
    wp_reset_postdata();
    return $html;
}