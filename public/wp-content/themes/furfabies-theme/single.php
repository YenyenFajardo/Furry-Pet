<?php get_header();
    while(have_posts()) {
        the_post();
        // How to get the value of the custom fields on a current or single post
        $post_id = get_the_ID(); // Get current post ID
        $featured_img = get_field('featured_image', $post_id);
    ?>
            
    
    <div class="page-banner"> 
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title() ?></h1>
            <div class="page-banner__intro">
                <p>Author: <?php the_author(); ?></p>
                <p>Date Posted:  <?php the_date('F j, Y g:i a'); ?></p>
            </div>
        </div>
    </div>
    <div class="page-banner__bg-image" style="background-image: url(<?=$featured_img ? $featured_img: get_theme_file_uri( '/images/singlepic.jpg' ); ?>"></div>
    <div class="container container--narrow page-section">      
        <?php
            $theParent = wp_get_post_parent_id(get_the_ID());
            if ($theParent) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> 
                    <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
        <?php }?>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>
    </div>
    <?php
        $args = array(
            'post_type'      => array('post'),
            'post_status'    => 'publish',
            'posts_per_page' => -1, // -1 is unlimited display, adding positive numbers will limit the display
            'orderby'        => 'date',
            'sort_order'     => 'ASC',
        );
        $query = new WP_Query( $args );
        $posts = $query->posts;
    ?>

<?php }
    get_footer();
?>
