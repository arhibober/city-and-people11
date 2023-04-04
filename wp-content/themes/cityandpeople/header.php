<!DOCTYPE html>
<html lang='en'>

<head>

    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <meta name='description' content=''>
    <meta name='author' content=''>

    <title><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></title>
    <?php wp_head(); ?>

</head>

<body>
    <?php
    global $user_ID;
    if ($user_ID > 0)
        echo '<style>
				nav.fixed-top
				{
					top: 20px;
				}
			</style>';
    ?>
    <!-- Navigation -->
    <nav class='navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top'>
        <div class='container'>
            <a class='navbar-brand' href='<?php echo get_home_url(); ?>'><?php bloginfo('name'); ?></a>
            <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse'
                data-target='#navbarResponsive' aria-controls='navbarResponsive' aria-expanded='false'
                aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarResponsive'>
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'depth' => 3,
                        'container' => false,
                        'menu_class' => 'navbar-nav ml-auto',
                        'fallback_cb' => false,

                        'walker' => new Cityandpeople_Nav_Walker(),
                    ]);
                }
                ?>

            </div>
        </div>
    </nav>

    <header>
        <?php if (is_front_page()) { ?>
        <div id='carouselExampleIndicators' class='carousel slide' data-ride='carousel'>
            <ol class='carousel-indicators'>
                <li data-target='#carouselExampleIndicators' data-slide-to='0' class='active'></li>
                <li data-target='#carouselExampleIndicators' data-slide-to='1'></li>
                <li data-target='#carouselExampleIndicators' data-slide-to='2'></li>
            </ol>
            <div class='carousel-inner' role='listbox'>
                <!-- Slide One - Set the background image for this slide in the line below -->

                <div class='carousel-item active'>
                    <img src='<?php bloginfo('template_directory') ?>/assets/images/IMG_0274.JPG' class='d-block w-100'
                        alt='...' />
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Вхід у метро Архітектора Бекетова</h3>
                        <p>Квітень 2013</p>
                    </div>
                </div>
                <!-- Slide Two - Set the background image for this slide in the line below -->

                <div class='carousel-item'>
                    <img src='<?php bloginfo('template_directory') ?>/assets/images/IMG_0275.JPG' class='d-block w-100'
                        alt='...' />
                    <div class='carousel-caption d-none d-md-block'>
                        <h3>Харківський Національний Університет імені Каразіна</h3>
                        <p>Квітень 2013</p>
                    </div>
                </div>
                <!-- Slide Three - Set the background image for this slide in the line below -->

                <div class='carousel-item'>
                    <img src='<?php bloginfo('template_directory') ?>/assets/images/IMG_0931.JPG' class='d-block w-100'
                        alt='...' />
                    <div class='carousel-caption d-none d-md-block'>
                        <h3>Річка Харків у центрі міста</h3>
                        <p>Травень 2014</p>
                    </div>
                </div>
                <div class='carousel-item'>
                    <img src='<?php bloginfo('template_directory') ?>/assets/images/IMG_20210625_111030.jpg'
                        class='d-block w-100' alt='...' />
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Вулиця Політехнична</h3>
                        <p>Червень 2021</p>
                    </div>
                </div>
                <!-- Slide Two - Set the background image for this slide in the line below -->

                <div class='carousel-item'>
                    <img src='<?php bloginfo('template_directory') ?>/assets/images/IMG_4708.JPG' class='d-block w-100'
                        alt='...' />
                    <div class='carousel-caption d-none d-md-block'>
                        <h3>Покровський монастир</h3>
                        <p>Лютий 2018</p>
                    </div>
                </div>
                <!-- Slide Three - Set the background image for this slide in the line below -->

            </div>
            <a class='carousel-control-prev' href='#carouselExampleIndicators' role='button' data-slide='prev'>
                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                <span class='sr-only'>Previous</span>
            </a>
            <a class='carousel-control-next' href='#carouselExampleIndicators' role='button' data-slide='next'>
                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                <span class='sr-only'>Next</span>
            </a>
        </div>
        <?php } ?>
    </header>