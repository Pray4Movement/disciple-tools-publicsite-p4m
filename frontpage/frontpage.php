<?php
class DT_P4E_Frontpage_Config
{
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        $url = dt_get_url_path();
        add_action( "template_redirect", [ $this, 'my_theme_redirect' ] );
        add_filter( 'dt_front_page', [ $this, 'front_page' ] );

        add_filter( 'dt_custom_fields_settings', [ $this, 'dt_custom_fields_settings' ], 10, 2 );

        if ( empty($url) && ! dt_is_rest() ) {
            add_filter( 'dt_blank_access', function(){ return true;
            } );
            add_filter( 'dt_allow_non_login_access', function(){ return true;
            }, 100, 1 );

            add_filter( "dt_blank_title", [ $this, "_browser_tab_title" ] );
            add_action( 'dt_blank_head', [ $this, '_header' ] );
            add_action( 'dt_blank_footer', [ $this, '_footer' ] );
            add_action( 'dt_blank_body', [ $this, 'body' ] ); // body for no post key

            // load page elements
            add_action( 'wp_print_scripts', [ $this, '_print_scripts' ], 1500 );
            add_action( 'wp_print_styles', [ $this, '_print_styles' ], 1500 );

            add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 99 );
        }
    }

    public function dt_custom_fields_settings( $fields, $post_type ){
        if ( $post_type === 'contacts' ) {
            if ( ! isset( $fields['sources']['default']['dt_frontpage_p4m'] ) ) {
                $fields['sources']['default']['dt_frontpage_p4m'] = [
                    'label' => 'Pray4 Frontpage',
                    'key' => 'dt_frontpage_p4m',
                    'type' => 'other',
                    'description' => 'This lead came from the front page via the DT public site plugin.',
                    'enabled' => 1
                ];
            }
        }
        return $fields;
    }

    public function my_theme_redirect() {
        $url = dt_get_url_path();
        if ( empty($url) ){
            $path = get_theme_file_path('template-blank.php');
            include( $path );
            die();
        }
    }

    public function front_page( $page ){
        if ( is_user_logged_in() ) {
            return site_url( '/contacts/' );
        }
        return $page;
    }

    public function _browser_tab_title( $title ){
        $content = get_option('landing_content');
        return esc_html( $content['title'] ?? '' );
    }
    public function _header(){
        $content = get_option('landing_content');
        ?>
        <meta charset="utf-8">
        <title><?php echo esc_html( $content['title'] ?? '' ) ?></title>
        <meta name="description" content="<?php echo esc_html( $content['description'] ?? '' ) ?>">
        <meta name="author" content="<?php echo esc_html( $content['title'] ?? '' ) ?>">
        <?php
        wp_head();

        $this->header_style();
        $this->header_javascript();
    }
    public function header_style(){

    ?>
        <!-- CSS
        ================================================== -->
        <link rel="stylesheet" href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>css/base.css">
        <link rel="stylesheet" href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>css/vendor.css">
        <link rel="stylesheet" href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>css/main.css">
        <!-- favicons
        ================================================== -->
        <link rel="shortcut icon" href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>favicon.png" type="image/x-icon">
        <link rel="icon" href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>favicon.png" type="image/x-icon">
        <style>
            .header-logo {
                z-index: 501;
                display: inline-block;
                margin: 0;
                padding: 0;
                position: absolute;
                left: 110px;
                top: 50%;
                -webkit-transform: translateY(-50%);
                -ms-transform: translateY(-50%);
                transform: translateY(-50%);
            }
            .header-logo a {
                display: block;
                padding: 0;
                outline: 0;
                border: none;
                -webkit-transition: all .3s ease-in-out;
                transition: all .3s ease-in-out;
                background-image: url(<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/p4m-logo.png);
                background-repeat: no-repeat;
                background-size: 50px;
                background-position: left center;
                padding-left: 60px;
                font-size: 3em;
                font-weight: 900;
                color: #fff;
                font-family: 'times new roman';
            }
            @media only screen and (max-width: 1000px) {
                .header-logo a {
                    font-size: 1em;
                }
            }
        </style>
    <?php
    }
    public function header_javascript(){
        ?>
        <!-- script
        ================================================== -->
        <script src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>js/modernizr.js"></script>
        <script src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>js/pace.min.js"></script>
        <script>
            let jsObject = [<?php echo json_encode([
                'map_key' => DT_Mapbox_API::get_key(),
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'details' => $content = get_option('landing_content'),
                'translations' => [
                    'add' => __( 'Add Magic', 'disciple_tools' ),
                ],
            ]) ?>][0]

            jQuery(document).ready(function(){
                clearInterval(window.fiveMinuteTimer)
            })
        </script>
        <?php
        return true;
    }
    public function _footer(){
        wp_footer();
    }
    public function scripts() {
    }
    public function _print_scripts(){
        // @link /disciple-tools-theme/dt-assets/functions/enqueue-scripts.php
        $allowed_js = [
            'jquery',
            'lodash',
            'moment',
            'datepicker',
            'mapbox-gl',
            'mapbox-cookie',
            'mapbox-search-widget',
            'google-search-widget',
            'jquery-cookie',
        ];

        global $wp_scripts;

        if ( isset( $wp_scripts ) ){
            foreach ( $wp_scripts->queue as $key => $item ){
                if ( ! in_array( $item, $allowed_js ) ){
                    unset( $wp_scripts->queue[$key] );
                }
            }
        }
        unset( $wp_scripts->registered['mapbox-search-widget']->extra['group'] );
    }
    public function _print_styles(){
        // @link /disciple-tools-theme/dt-assets/functions/enqueue-scripts.php
        $allowed_css = [
            'foundation-css',
            'jquery-ui-site-css',
            'datepicker-css',
            'mapbox-gl-css'
        ];

        global $wp_styles;
        if ( isset( $wp_styles ) ) {
            foreach ($wp_styles->queue as $key => $item) {
                if ( !in_array( $item, $allowed_css )) {
                    unset( $wp_styles->queue[$key] );
                }
            }
        }
    }
    public function body(){
        $content = get_option('landing_content');
        ?>


        <!-- header
        ================================================== -->
        <header class="s-header">

            <div class="header-logo">
                <a class="site-logo" href="/">
                    <?php echo esc_html( $content['title'] ?? '' ) ?>
                </a>
            </div> <!-- end header-logo -->
            <nav class="header-nav">

                <a href="#0" class="header-nav__close" title="close"><span>Close</span></a>

                <div class="header-nav__content">
                    <h3><?php echo esc_html( $content['title'] ?? '' ) ?></h3>

                    <ul class="header-nav__list">
                        <li class="current"><a class="smoothscroll" href="#home" title="home">Home</a></li>
                        <li><a class="smoothscroll" href="#about" title="about">About</a></li>
                        <li><a class="smoothscroll" href="#values" title="our-plan">Values</a></li>
                        <li><a class="smoothscroll" href="#contact" title="contact">Join Us</a></li>
                        <?php if( $content['sample_section'] ?? 'yes' === 'yes' ) : ?>
                            <li><a class="smoothscroll" href="#works" title="works">Samples</a></li>
                        <?php endif; ?>
                    </ul>

                    <p><?php echo esc_html( $content['title'] ?? '' ) ?> serves as a connection point for anyone with a passion to see a disciple-making movement in
                        <?php echo esc_html( $content['location'] ?? '' ) ?>. Join our email prayer guide, follow us on social media, or join us for a
                        prayer-walk. Above all pray with us to see spiritual life poured out on our state.</p>

                    <ul class="header-nav__social">
                        <?php if( ! empty( $content['facebook_url'] ?? '') ) : ?>
                            <li>
                                <a href="<?php echo esc_html( $content['facebook_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener"><i class="fab fa-facebook"></i></a>
                            </li>
                        <?php endif; ?>
                        <?php if( ! empty( $content['twitter_url'] ?? '') ) : ?>
                            <li>
                                <a href="<?php echo esc_html( $content['twitter_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener"><i class="fab fa-twitter"></i></a>
                            </li>
                        <?php endif; ?>
                        <?php if( ! empty( $content['instagram_url'] ?? '') ) : ?>
                            <li>
                                <a href="<?php echo esc_html( $content['instagram_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener"><i class="fab fa-instagram"></i></a>
                            </li>
                        <?php endif; ?>
                    </ul>

                </div> <!-- end header-nav__content -->

            </nav>

            <a class="header-menu-toggle" href="#0">
                <span class="header-menu-icon"></span>
            </a>

        </header> <!-- end s-header -->


        <!-- home
        ================================================== -->

        <section id="home" class="s-home target-section" data-parallax="scroll" data-image-src="<?php echo esc_html( $content['background_image_url'] ?? '' ) ?>" data-natural-width="3000" data-natural-height="2000" data-position-y="top">


            <div class="shadow-overlay"></div>

            <div class="home-content">

                <div class="row home-content__main">
                    <h1>
                        Praying for a <br>disciple making movement <br>in <?php echo esc_html( $content['location'] ?? '' ) ?>.
                    </h1>

                    <h2 style="color:white;font-family: 'metropolis-light', 'sans-serif';font-size:3.8rem;margin-top:0;"><span style="color:#cc147f;">-</span> #<?php echo esc_html( $content['title'] ?? '' ) ?></h2>
                    <a href="#contact" class="smoothscroll"><button class="">Join Us</button></a>

                </div> <!-- end home-content__main -->

            </div> <!-- end home-content -->

            <ul class="home-sidelinks">
                <li><a class="smoothscroll" href="#about">About<span>who we are</span></a></li>
                <li><a class="smoothscroll" href="#values">Values<span>what we care about</span></a></li>
                <li><a class="smoothscroll" href="#contact">Join Us<span>be a part</span></a></li>
            </ul> <!-- end home-sidelinks -->

            <ul class="home-social">
                <li class="home-social-title">Join Us</li>
                <li>
                    <a href="#contact" class="smoothscroll">
                        <i class="fab fa-envelope"></i>
                        <span class="home-social-text">Email Guide</span>
                    </a>
                </li>
                <?php if( ! empty( $content['facebook_url'] ?? '') ) : ?>
                    <li><a href="<?php echo esc_html( $content['facebook_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener">
                            <i class="fab fa-facebook"></i>
                            <span class="home-social-text">Facebook</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if( ! empty( $content['twitter_url'] ?? '') ) : ?>
                    <li><a href="<?php echo esc_html( $content['twitter_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener">
                            <i class="fab fa-twitter"></i>
                            <span class="home-social-text">Twitter</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if( ! empty( $content['instagram_url'] ?? '') ) : ?>
                    <li><a href="<?php echo esc_html( $content['instagram_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener">
                            <i class="fab fa-instagram"></i>
                            <span class="home-social-text">Instagram</span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul> <!-- end home-social -->

            <a href="#about" class="home-scroll smoothscroll">
                <span class="home-scroll__text">Scroll Down</span>
                <span class="home-scroll__icon"></span>
            </a> <!-- end home-scroll -->

        </section> <!-- end s-home -->


        <!-- about
        ================================================== -->
        <section id="about" class="s-about">

            <div class="row section-header" data-aos="fade-up">
                <div class="col-full">
                    <h3 class="subhead">Who We Are</h3>
                    <h1 class="display-1">#<?php echo esc_html( $content['title'] ?? '' ) ?> serves as a connection point for anyone with a passion to see a disciple making movement in <?php echo esc_html( $content['location'] ?? '' ) ?>.</h1>
                </div>
            </div> <!-- end section-header -->

            <div class="row" data-aos="fade-up">
                <div class="col-full">
                    <p class="lead">
                        Join our email prayer guide, follow us on social media, or join us for a prayer-walk.
                        Above all, pray with us to see spiritual life poured out on our state.
                    </p>
                </div>
            </div> <!-- end about-desc -->

            <div class="row">

                <div class="about-process process block-1-2 block-tab-full">

                    <div class="process__vline-left"></div>
                    <div class="process__vline-right"></div>

                    <div class="col-block process__col" data-item="1" data-aos="fade-up">
                        <div class="process__text">
                            <h4>Email Prayer Guide</h4>

                            <p>
                                We offer a regular prayer guide that includes challenges, quotes, and scripture focusing on <?php echo esc_html( $content['location'] ?? '' ) ?> issues. These serve as a help to focus us and help us pray together.
                                <a href="#contact">Join our Prayer Guide</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-block process__col" data-item="2" data-aos="fade-up">
                        <div class="process__text">
                            <h4>Social Media</h4>

                            <p>
                                Our same email challenges can also be followed on social media. Join our online prayer
                                warriors.
                                <?php if( ! empty( $content['facebook_url'] ?? '') ) : ?>
                                    <a href="<?php echo esc_html( $content['facebook_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener">Facebook</a>
                                <?php endif; ?>
                                <?php if( ! empty( $content['twitter_url'] ?? '') ) : ?>
                                    , <a href="<?php echo esc_html( $content['twitter_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener">Twitter</a>
                                <?php endif; ?>
                                <?php if( ! empty( $content['instagram_url'] ?? '') ) : ?>
                                    , and <a href="<?php echo esc_html( $content['instagram_url'] ?? '' ) ?>" target="_blank" rel="noreferrer noopener">Instagram</a>
                                <?php endif; ?>

                            </p>
                        </div>
                    </div>
                    <div class="col-block process__col" data-item="3" data-aos="fade-up">
                        <div class="process__text">
                            <h4>Prayer Walks</h4>

                            <p>
                                Prayer walking is key. Our faith is intended to be a light on a hill. Getting out and praying
                                over sections of the city and over the people who are there has born fruit in every nation on
                                the planet. <?php echo esc_html( $content['location'] ?? '' ) ?> is no different. Check out our
                                <a href="<?php echo esc_html( $content['facebook_events_url'] ?? '' ) ?>">Facebook Events</a>
                                page.
                            </p>
                        </div>
                    </div>
                    <div class="col-block process__col" data-item="4" data-aos="fade-up">
                        <div class="process__text">
                            <h4>Movement Training</h4>
                            <p>
                                If you have a small group or sunday school that would like training on prayer walking or
                                discipleship training on making disciples who multiply, <a href="#contactform">contact us</a>.
                            </p>
                        </div>
                    </div>

                </div> <!-- end process -->

            </div> <!-- end about-stats -->

        </section> <!-- end s-about -->

        <!-- stats
        ================================================== -->
        <section id="stats" class="s-stats">

            <div class="row stats block-1-4 block-m-1-2 block-mob-full" data-aos="fade-up">

                <div class="col-block stats__col ">
                    <div class="stats__static"><?php echo esc_html( $content['stats_population'] ?? '0' ) ?></div>
                    <h5>Million</h5>
                </div>
                <div class="col-block stats__col">
                    <div class="stats__count"><?php echo esc_html( $content['stats_cities'] ?? '0' ) ?></div>
                    <h5>Cities</h5>
                </div>
                <div class="col-block stats__col">
                    <div class="stats__count"><?php echo esc_html( $content['stats_trainings'] ?? '0' ) ?></div>
                    <h5>New Trainings Needed</h5>
                </div>
                <div class="col-block stats__col">
                    <div class="stats__count"><?php echo esc_html( $content['stats_churches'] ?? '0' ) ?></div>
                    <h5>New Churches Needed</h5>
                </div>

            </div> <!-- end stats -->

        </section> <!-- end s-stats -->

        <!-- values
        ================================================== -->
        <section id="values" class="s-services light-gray">

            <div class="row section-header" data-aos="fade-up">
                <div class="col-full">
                    <h3 class="subhead">What We Care About</h3>
                    <h1 class="display-1">We know that every disciple making movement in history has begun with
                        Extraordinary Prayer.</h1>
                </div>
            </div> <!-- end section-header -->

            <div class="row" data-aos="fade-up">
                <div class="col-full">
                    <p class="lead">
                        “You do not have because you do not ask God.” – James 4:2<br>
                        “The story of every great Christian achievement is the history of answered prayer.” – E.M. Bounds
                    </p>
                </div>
            </div> <!-- end about-desc -->

            <div class="row services-list block-1-3 block-m-1-2 block-tab-full">

                <div class="col-block service-item " data-aos="fade-up">
                    <div class="service-icon service-icon--brand-identity">
                        <i class="icon-tv"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Loving</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> loves God, loves people, and helps Christians fulfill the Great Commission by
                            mobilizing prayer.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon  service-icon--illustration">
                        <i class="icon-group"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Strategic</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> promotes strategic prayer for movement, knowing that ( based upon research )
                            extraordinary prayer is found at the root of all modern movements.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon service-icon--mobile-dev">
                        <i class="icon-tv"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Safe</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> will never ask for money or market to you on behalf of others. The resources and
                            events we may mention will always be for the benefit and connection of the community.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon service-icon--mobile-dev">
                        <i class="icon-lego-block"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Open</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> welcomes collaboration of <strong>all</strong> Christians.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon service-icon--mobile-dev">
                        <i class="icon-lego-block"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Multiplying</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> cares that obedient disciples of Jesus Christ make more obedient disciples. Obeying
                            all that Jesus commanded includes making disciples who make disciples.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon  service-icon--ui-design">
                        <i class="icon-window"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Expectant</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> strives to neither under- nor over-estimate man’s role in disciple multiplication
                            movements. God declared prayer as the vehicle for seeking and receiving his kingdom in the place we live.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon  service-icon--web-design">
                        <i class="icon-earth"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Mobilizing</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> asks everyone to not only pray but also to mobilize prayer through relationships
                            and opportunities God provides.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon service-icon--ui-design">
                        <i class="icon-window"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Brief</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> is super brief, usually 1-to-3 lines long. We aim to provoke directed prayer, not
                            monologue.
                        </p>
                    </div>
                </div>

                <div class="col-block service-item" data-aos="fade-up">
                    <div class="service-icon service-icon--mobile-dev">
                        <i class="icon-tv"></i>
                    </div>
                    <div class="service-text">
                        <h3 class="h4">Not Political</h3>
                        <p>#<?php echo esc_html( $content['title'] ?? '' ) ?> is not a political agenda, rather an effort for sinners who have found forgiveness
                            in Christ to share this Good News with others.
                        </p>
                    </div>
                </div>

            </div> <!-- end services-list -->

        </section> <!-- end s-services -->

        <style>
            #email {
                display:none;
            }
            .form-error {
                display:none;
            }
            .loading-spinner {
                display:none;
            }
            .loading-spinner.active {
                display:block !important;
            }
        </style>
        <!-- contact
        ================================================== -->
        <section id="contact" class="s-contact">

            <div class="row section-header" data-aos="fade-up">
                <div class="col-full">
                    <h3 class="subhead subhead--light">Join Us</h3>
                    <h1 class="display-1 display-1--light">(1) Get our email prayer guide</h1>
                    <div class="contact-subscribe">
                        <!-- Begin Mailchimp Signup Form -->
                        <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
                        <style type="text/css">
                            #mc_embed_signup_scroll { color:white;}
                            #mc_embed_signup_scroll h2 { color:white;}
                            .mc-field-group label { color:white;}
                            /* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
                               We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                        </style>
                        <div id="mc_embed_signup" >
                            <form action="<?php echo esc_html( $content['mailchimp_form_url'] ?? '' ) ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="" style="padding-left:0;margin-left:0;">
                                <div id="mc_embed_signup_scroll" >
                                    <div class="mc-field-group" style="padding-bottom:0;">
                                        <label for="mce-FNAME">First Name </label>
                                        <input type="text" value="" name="FNAME" class="required" style="background:#fff; width: 50%;" id="mce-FNAME">
                                    </div>
                                    <div class="mc-field-group" style="padding-bottom:0;">
                                        <label for="mce-EMAIL">Email Address </label>
                                        <input type="email" value="" name="EMAIL" class="required email" style="background:#fff; width: 50%;" id="mce-EMAIL">
                                    </div>
                                    <div id="mce-responses" class="clear"  style="width:100%;">
                                        <div class="response" id="mce-error-response" style="display:none"></div>
                                        <div class="response" id="mce-success-response" style="display:none"></div>
                                    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="<?php echo esc_html( $content['mailchimp_form_hidden_id'] ?? '' ) ?>" tabindex="-1" value=""></div>
                                    <div class="mc-field-group">
                                        <button type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" style="margin-left:0;" >Subscribe</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script><script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='MMERGE3';ftypes[3]='zip';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                        <!--End mc_embed_signup-->
                    </div> <!-- end contact-subscribe -->
                </div>
            </div> <!-- end section-header -->

            <div class="row section-header" data-aos="fade-up">
                <div class="col-full">
                    <h1 class="display-1 display-1--light">(2) Follow us on social media</h1>
                    <p>
                        <?php if( ! empty( $content['facebook_url'] ?? '') ) : ?>
                            <button type="button" onclick="location.href = '<?php echo esc_html( $content['facebook_url'] ?? '' ) ?>'"><i class="fab fa-facebook"></i> Facebook</button>
                        <?php endif; ?>
                        <?php if( ! empty( $content['twitter_url'] ?? '') ) : ?>
                            <button type="button" onclick="location.href = '<?php echo esc_html( $content['twitter_url'] ?? '' ) ?>'"><i class="fab fa-twitter"></i> Twitter</button>
                        <?php endif; ?>
                        <?php if( ! empty( $content['instagram_url'] ?? '') ) : ?>
                            <button type="button" onclick="location.href = '<?php echo esc_html( $content['instagram_url'] ?? '' ) ?>'"><i class="fab fa-instagram"></i> Instagram</button>
                        <?php endif; ?>

                    </p>

                </div>

            </div> <!-- end section-header -->

            <span id="contactform" name="contactform"></span>

            <div class="row section-header" data-aos="fade-up">
                <div class="col-full">
                    <h1 class="display-1 display-1--light">(3) Contact us directly</h1>
                    <div id="contact_form" >
                        <div class="mc-field-group" style="padding-bottom:0;">
                            <label for="mce-FNAME">Full Name </label>
                            <input type="text" value="" name="FNAME" class="required" style="background:#fff; width: 50%;" id="name" />
                            <span id="name-error" class="form-error">You're name is required.</span>
                        </div>
                        <div class="mc-field-group" style="padding-bottom:0;">
                            <label for="mce-EMAIL">Email Address </label>
                            <input type="email" id="email" name="email" placeholder="Email" />
                            <input type="email" id="e2" name="email" style="background:#fff; width: 50%;" />
                            <span id="email-error" class="form-error">You're email is required.</span>
                        </div>
                        <div class="mc-field-group" style="padding-bottom:0;">
                            <label for="mce-FNAME">Phone Number </label>
                            <input type="text" value="" name="PHONE" style="background:#fff; width: 50%;" id="phone">
                            <span id="phone-error" class="form-error">You're phone is required.</span>
                        </div>
                        <div id="response_container"></div>
                        <span class="loading-spinner" style="background-color:whitesmoke;width:30px;">
                            <img style="width:30px;" src="<?php echo esc_url( trailingslashit( plugin_dir_url(__DIR__) ) . 'spinner.svg') ?>" />
                        </span>

                        <div>
                            <button type="button" id="submit_contact_form">Submit</button>
                        </div>
                    </div>
                    <script>
                        jQuery(document).ready(function($){
                            $('#submit_contact_form').on('click', function(){
                                let spinner = jQuery('.loading-spinner')
                                spinner.addClass('active')

                                let submit_button = jQuery('#submit_contact_form')
                                submit_button.prop('disabled', true)

                                let honey = jQuery('#email').val()
                                if ( honey ) {
                                    submit_button.html('Shame, shame, shame. We know your name ... ROBOT!').prop('disabled', true )
                                    spinner.removeClass('active')
                                    return;
                                }

                                let name_input = jQuery('#name')
                                let name = name_input.val()
                                if ( ! name ) {
                                    jQuery('#name-error').show()
                                    submit_button.removeClass('loading')
                                    name_input.focus(function(){
                                        jQuery('#name-error').hide()
                                    })
                                    submit_button.prop('disabled', false)
                                    spinner.removeClass('active')
                                    return;
                                }

                                let email_input = jQuery('#e2')
                                let email = email_input.val()
                                if ( ! email ) {
                                    jQuery('#email-error').show()
                                    submit_button.removeClass('loading')
                                    email_input.focus(function(){
                                        jQuery('#email-error').hide()
                                    })
                                    submit_button.prop('disabled', false)
                                    spinner.removeClass('active')
                                    return;
                                }

                                let phone_input = jQuery('#phone')
                                let phone = phone_input.val()
                                if ( ! phone ) {
                                    jQuery('#phone-error').show()
                                    submit_button.removeClass('loading')
                                    email_input.focus(function(){
                                        jQuery('#phone-error').hide()
                                    })
                                    submit_button.prop('disabled', false)
                                    spinner.removeClass('active')
                                    return;
                                }

                                $.ajax({
                                    type: "POST",
                                    data: JSON.stringify({ name: name, email: email, phone: phone }),
                                    contentType: "application/json; charset=utf-8",
                                    dataType: "json",
                                    url: jsObject.root + 'publicsite_p4m/v1/public_endpoint',
                                    beforeSend: function (xhr) {
                                        xhr.setRequestHeader('X-WP-Nonce', jsObject.nonce )
                                    }
                                })
                                .done(function(data) {
                                    console.log(data)
                                    spinner.removeClass('active')
                                    $('#response_container').html('<span style="color:white;">Success</span>')
                                    $('#name').val('')
                                    $('#e2').val('')
                                    $('#phone').val('')
                                })
                            })
                        })
                    </script>
                </div>
            </div> <!-- end section-header -->

            <div class="row">

            </div> <!-- end row -->
        </section>


        <?php if( $content['sample_section'] ?? 'yes' === 'yes' ) : ?>
            <!-- works
            ================================================== -->
            <section id="works" class="s-works">

                <div class="row section-header" data-aos="fade-up">
                    <div class="col-full">
                        <h3 class="subhead">Samples</h3>
                        <h1 class="display-1">These are some examples of the encouragements we'll send you.</h1>
                    </div>
                </div> <!-- end section-header -->

                <div class="row masonry-wrap">
                    <div class="masonry">

                        <div class="masonry__brick" data-aos="fade-up">
                            <div class="item-folio">

                                <div class="item-folio__thumb">
                                    <a href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-dmm1.jpg" class="thumb-link" title="Movement" data-size="800x800">
                                        <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/dmm1.jpg" srcset="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/dmm1.jpg 1x, <?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-dmm1.jpg 2x" alt="">
                                    </a>
                                </div>

                                <div class="item-folio__text">
                                    <h3 class="item-folio__title">
                                        Path to growth
                                    </h3>
                                    <p class="item-folio__cat">
                                        Movement principles
                                    </p>
                                </div>

                                <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                                    Link
                                </a>

                                <div class="item-folio__caption">
                                    <p>The great challenge that faces spiritual movement is not complexity of thought, but obedient action.</p>
                                </div>

                            </div> <!-- end item-folio -->
                        </div> <!-- end masonry__brick -->

                        <div class="masonry__brick" data-aos="fade-up">
                            <div class="item-folio">

                                <div class="item-folio__thumb">
                                    <a href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-pray.jpg" class="thumb-link" title="Pray" data-size="800x800">
                                        <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/pray.jpg" srcset="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/pray.jpg 1x, <?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-pray.jpg 2x" alt="">
                                    </a>
                                </div>

                                <div class="item-folio__text">
                                    <h3 class="item-folio__title">
                                        See where the kingdom isn't
                                    </h3>
                                    <p class="item-folio__cat">
                                        Compassion
                                    </p>
                                </div>

                                <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                                    Link
                                </a>

                                <span class="item-folio__caption">
                            <p>Have eyes to see where the kingdom is not yet.</p>
                        </span>

                            </div> <!-- end item-folio -->
                        </div> <!-- end masonry__brick -->

                        <div class="masonry__brick" data-aos="fade-up">
                            <div class="item-folio">

                                <div class="item-folio__thumb">
                                    <a href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-quote1.jpg" class="thumb-link" title="Woodcraft" data-size="800x800">
                                        <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/quote1.jpg" srcset="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/quote1.jpg 1x, <?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-quote1.jpg 2x" alt="">
                                    </a>
                                </div>

                                <div class="item-folio__text">
                                    <h3 class="item-folio__title">
                                        Heart of Prayer
                                    </h3>
                                    <p class="item-folio__cat">
                                        Prayer
                                    </p>
                                </div>

                                <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                                    Link
                                </a>

                                <span class="item-folio__caption">
                            <p>Revision what prayer is.</p>
                        </span>

                            </div> <!-- end item-folio -->
                        </div> <!-- end masonry__brick -->

                        <div class="masonry__brick" data-aos="fade-up">
                            <div class="item-folio">

                                <div class="item-folio__thumb">
                                    <a href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-scripture.jpg" class="thumb-link" title="Liberty" data-size="800x800">
                                        <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/scripture1.jpg" srcset="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/scripture1.jpg 1x, <?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-scripture.jpg 2x" alt="">
                                    </a>
                                </div>

                                <div class="item-folio__text">
                                    <h3 class="item-folio__title">
                                        The Word has Power
                                    </h3>
                                    <p class="item-folio__cat">
                                        Scripture
                                    </p>
                                </div>

                                <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                                    Link
                                </a>

                                <span class="item-folio__caption">
                            <p>Pray Continually</p>
                        </span>

                            </div> <!-- end item-folio -->
                        </div> <!-- end masonry__brick -->


                        <div class="masonry__brick" data-aos="fade-up">
                            <div class="item-folio">

                                <div class="item-folio__thumb">
                                    <a href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-quote4.jpg" class="thumb-link" title="Fuji" data-size="800x800">
                                        <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/quote4.jpg" srcset="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/quote4.jpg 1x, <?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-quote4.jpg 2x" alt="">
                                    </a>
                                </div>

                                <div class="item-folio__text">
                                    <h3 class="item-folio__title">
                                        Extraordinary Prayer
                                    </h3>
                                    <p class="item-folio__cat">
                                        Prayer
                                    </p>
                                </div>

                                <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                                    Link
                                </a>

                                <span class="item-folio__caption">
                            <p>The primary means of revival that everyone agrees on is extraordinary prayer.</p>
                        </span>

                            </div> <!-- end item-folio -->
                        </div> <!-- end masonry__brick -->

                        <div class="masonry__brick" data-aos="fade-up">
                            <div class="item-folio">

                                <div class="item-folio__thumb">
                                    <a href="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-quote3.jpg" class="thumb-link" title="Shutterbug" data-size="800x800">
                                        <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/quote3.jpg" srcset="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/quote3.jpg 1x, <?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/g-quote3.jpg 2x" alt="">
                                    </a>
                                </div>

                                <div class="item-folio__text">
                                    <h3 class="item-folio__title">
                                        Jesus Commanded More
                                    </h3>
                                    <p class="item-folio__cat">
                                        Vision
                                    </p>
                                </div>

                                <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                                    Link
                                </a>

                                <span class="item-folio__caption">
                            <p>Jesus commanded much more.</p>
                        </span>

                            </div> <!-- end item-folio -->
                        </div> <!-- end masonry__brick -->

                    </div> <!-- end masonry -->
                </div> <!-- end masonry-wrap -->

                <div class="testimonials-wrap" data-aos="fade-up">

                    <div class="row">
                        <div class="col-full testimonials-header">
                            <h2 class="h1">What Prayer Warriors Are Saying</h2>
                        </div>
                    </div>

                    <div class="row testimonials">

                        <div class="col-full testimonials__slider">

                            <div class="testimonials__slide">
                                <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/user-02.jpg" alt="Author image" class="testimonials__avatar">
                                <p>If we never pray for movement in <?php echo esc_html( $content['location'] ?? '' ) ?>, do we have the right to complain when it isn't happening?</p>
                                <div class="testimonials__author">
                                    Dave ~
                                    <span>Pastor</span>
                                </div>
                            </div> <!-- end testimonials__slide -->

                            <div class="testimonials__slide">
                                <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/user-01.jpg" alt="Author image" class="testimonials__avatar">
                                <p>The American church needs to catch up with the prayer commitment, passion, and priority of the church in Asia and Africa.</p>
                                <div class="testimonials__author">
                                    Chris ~
                                    <span>Worker, North America</span>
                                </div>
                            </div> <!-- end testimonials__slide -->

                            <div class="testimonials__slide">
                                <img src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>images/keller.jpg" alt="Author image" class="testimonials__avatar">
                                <p>The primary means of revival, that everyone agrees on, is extra-ordinary prayer.</p>
                                <div class="testimonials__author">
                                    Tim Keller
                                    <span>Pastor</span>
                                </div>
                            </div> <!-- end testimonials__slide -->



                        </div> <!-- end testimonials__slider -->

                    </div> <!-- end testimonials -->

                </div> <!-- end testimonials-wrap -->

            </section> <!-- end s-works -->

        <?php endif; ?>


        <!-- Footer -->
        <section id="footer" class="s-contact">
            <div class="row">
                <div class="col-full cl-copyright">
                <span><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright ©<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://zume.community" target="_blank" rel="noopener noreferrer">Zúme.Community</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></span>
                </div>
            </div>

            <div class="cl-go-top">
                <a class="smoothscroll" title="Back to Top" href="#top"><i class="icon-arrow-up" aria-hidden="true"></i></a>
            </div>

        </section> <!-- end s-contact -->


        <!-- photoswipe background
        ================================================== -->
        <div aria-hidden="true" class="pswp" role="dialog" tabindex="-1">

            <div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap">

                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>

                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <div class="pswp__counter"></div>
                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                        <button class="pswp__button pswp__button--share" title="Share"></button>
                        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                                <div class="pswp__preloader__cut">
                                    <div class="pswp__preloader__donut"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div>
                    </div>
                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>
                </div>

            </div>

        </div> <!-- end photoSwipe background -->


        <!-- preloader
        ================================================== -->
        <div id="preloader">
            <div id="loader">
            </div>
        </div>
        <!-- /wp:html -->

        <!-- Java Script
        ================================================== -->
        <script src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>js/jquery-3.2.1.min.js"></script>
        <script src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>js/plugins.js"></script>
        <script src="<?php echo trailingslashit( plugin_dir_url(__DIR__) ); ?>js/main.js"></script>

        <?php
    }


}
DT_P4E_Frontpage_Config::instance();
