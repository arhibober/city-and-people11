<?php

/**
 * Template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage city-and-people
 * @since City and People1 1.0
 */

get_header('v2'); ?>

<!-- Page Content -->
<div class='container container-up'>

    <!-- Marketing Icons Section -->
    <div class='row'>
        <div class='col-md-8'>
            <!-- Blog Entries Column -->
            <div id="object_archive" class='row'>

                <!-- Page Heading/Breadcrumbs -->
                <h1 class='mt-4 mb-3'>404
                    <small>
                        <?php _e('Page Not Found') ?>
                    </small>
                </h1>

                <div class='jumbotron'>
                    <p>
                        <?php _e("The page you're looking for could not be found") ?>
                </div>
                <!-- /.jumbotron -->
                <?php get_search_form(); ?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>
<!-- /.container -->

<?php get_footer(); ?>