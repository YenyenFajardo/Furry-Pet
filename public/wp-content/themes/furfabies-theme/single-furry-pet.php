<?php get_header();
    while(have_posts()) {
        the_post();
        
        // How to get the value of the custom fields on a current
        $post_id = get_the_ID(); // Get current post ID
        $featured_img = get_field('featured_image', $post_id);
        $breed = get_field('breed', $post_id);
        $age = get_field('age', $post_id);
        $gender = get_field('gender', $post_id);
        $weight = get_field('weight', $post_id);
        $recuedFrom = get_field('rescue_story', $post_id);
        ?>
        
        <div class="page-banner-pet">
            <h1 class="page-banner__title"><?php the_title() ?> <i class="fa-solid fa-paw"></i></h1>
        </div>
        <div class="pet-single">
            <div class="home">
                <h3>Be my forever home <i class="fa-solid fa-heart"></i></h3>
            </div>
            <div class="pet-details">
                <img class="pet-image" src="<?=$featured_img ? $featured_img: get_theme_file_uri( '/images/pawp.png' ); ?>"></img>
                <table class="card">
                    <tr>
                        <th>Description</th>
                        <td><?= the_content();?></td>
                    </tr>
                    <tr>
                        <th>Breed</th>
                        <td><?= $breed;?></td>
                    </tr>
                    
                    <tr>
                        <th>Age</th>
                        <td><?= $age;?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?= $gender;?></td>
                    </tr>
                    <tr>
                        <th>Weight</th>
                        <td><?= $weight;?></td>
                    </tr>
                    <tr>
                        <th>Rescue Story</th>
                        <td><?= $recuedFrom;?></td>
                    </tr>
                </table>
            </div>
            <div class="bg-single" style="background-image: url(<?php echo $featured_img ? $featured_img: get_theme_file_uri( '/images/pawp.png' );?>"></div>
        </div>
         <?php
    $args = array(
        'post_type'      => array('furry-pet'),
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
