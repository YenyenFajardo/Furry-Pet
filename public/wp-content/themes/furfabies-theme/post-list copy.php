<?php 
/*
Template Name: Blog Lists
*/
get_header();
    while(have_posts()) {
        the_post();?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/puppies.jpg')?>"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_title() ?></h1>
                <div class="page-banner__intro">
                    <p>Don't forget to replace me later.</p>
                </div>
            </div>
        </div>

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
                <?php }
                ?>
            </div>
            <?php 
                $args = array(
                    'post_type'      => array('post'),
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'date',
                    'sort_order'     => 'ASC',
                );
                $query = new WP_Query( $args);
                $posts = $query->posts;
                ?>

            <?php if ( $query->have_posts()) { ?>
                <div class="generic-content">
                    <div class="blog-wrapper">
                        <button class="blog-btn" onclick="openModal('createPostModal')">Create New Post</button>
                        <ul>
                            <?php foreach($posts as $post) { ?>
                                <li>
                                    <?php $featured_img = get_field('featured_image', $post->ID);?>
                                    <div class="blog-image" style="background-image: url(<?=$featured_img ? $featured_img: get_theme_file_uri( '/images/singlepic.jpg' ); ?>"></div>
                                    <div class="blog-content">
                                        <a href="<?php echo get_permalink();?>"><h4><?=$post->post_title; ?></h4></a>
                                        <p class="blog-date"><?=get_the_author_meta( 'display_name',        $post->post_author); ?> | <?=relative_date($post->post_date); ?></p>
                                        <div class="blog-desc"><?=($post->post_content) ? ((strlen($post->post_content) <= 150) ? $post->post_content : substr(strip_tags($post->post_content), 0, 150).'...') : 'No Content'; ?></div>
                                        <div class="blog-actions">
                                            <a href="javascript:;" id="<?= $post->ID?>" class="blog-btn" onclick="editPost(<?=$post->ID?>)">Edit</a>
                                            <a href="javascript:;" class="blog-btnD" onclick="deletePost(<?=$post->ID?>)">Delete</a>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?> 
                        </ul>
                    </div>
                </div>
            <?php } ?> 
            <!-- The Modal -->
            <div id="createPostModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close" onclick="closeModal('createPostModal')">&times;</span>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="">Post Title</label>
                            <input type="text" class="form-input" id="post_title" name="post_title" placeholder="Post Title">
                        </div>
                        <div class="form-group">
                            <label for="">Post Description</label>
                            <textarea class="form-input" name="post_description" id="post_description" cols="30" rows="10"  placeholder="Post Description"></textarea>   
                        </div>
                        <div class="row">
                            <div class="col-25">
                                <label for="image">Image</label>
                            </div>
                            <div class="col-75">
                                <input type="file" id="image" name="image" accept=".png, .jpg">
                            </div>
                        </div>
                        <div class="row-btn">
                            <input type="submit" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php }
    get_footer();
?>
<script src="function.js"></script>
input[type="submit"] {
  color: #fff;
  background-color: #ab3d0d;
  border: 1px solid #333;
  border-radius: 25px;
  padding: 8px;
  margin: 0 0 0 20rem;
}

input[type="submit"]:hover{
  background-color: #e94f0d;
}