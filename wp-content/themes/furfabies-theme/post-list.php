<?php 
/*
Template Name: Blog Lists
*/
get_header();
    while(have_posts()) {
        the_post();?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/cover.png')?>"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_title() ?></h1>
                <div class="page-banner__intro">
                    <p>Don't forget to replace me later.</p>
                </div>
            </div>
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
                    <button class="blog-btn" onclick="newPost()"><i class="fa-solid fa-plus"></i> Create New Post</button>
                    <ul>
                        <?php foreach($posts as $post) { ?>
                        <li id="post_item-<?=$post->ID?>">
                            <?php $featured_img = get_field('featured_image', $post->ID);
                                $is_featured = (get_field('featured_post', $post->ID) =='Yes'? 'is-featured-post' :'');
                            ?>
                            <button class="featured-post <?=$is_featured?>" onclick="setAsFeatured(<?=$post->ID?>, '<?=$is_featured;?>', this)"><i class="fa fa-heart"></i></button>
                            <a href="<?php echo get_permalink(); ?>"><div class="blog-image" style="background-image: url(<?=$featured_img ? $featured_img: get_theme_file_uri( '/images/no_pic.jpg' ); ?>"></div></a>
                            <div class="blog-content">
                                <a href="<?php echo get_permalink();?>"><h4><?=$post->post_title; ?></h4></a>
                                <p class="blog-date"><?=get_the_author_meta( 'display_name',        $post->post_author); ?> | <?=relative_date($post->post_date); ?></p>
                                <div class="blog-desc"><?=($post->post_content) ? ((strlen($post->post_content) <= 150) ? $post->post_content : substr(strip_tags($post->post_content), 0, 150).'...') : 'No Content'; ?></div>
                                <div class="blog-actions">
                                    <a href="javascript:;" id="<?= $post->ID?>" class="blog-btn" onclick="editPost('<?=$post->ID?>')"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="javascript:;" class="blog-btnD" onclick="deletePost('<?=$post->ID?>','<?=$post->post_title?>')"><i class="fa-solid fa-trash"></i></a>
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
            <div class="modal-content" id="modal-wrapper">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal_title"> <i class="fa fa-plus"></i> Create New Post</h6>
                    <span class="close" onclick="closeModal('createpostModal')">&times;</span>
                </div>
                <!-- Waiting Loader -->
                <div class="waiting-loader d-none" id="post-loader"><i class="fa fa-spin fa-spinner"></i> <span>Loading post. Please wait...</span></div>
                <!-- Featured Image -->
                <div class="modal-image" id="featured-img-view" style="background-image: url(<?= get_theme_file_uri('/images/no_pic.jpg')?>;"></div>
                <!-- Post Form -->
                <form action="" method="post" id="post-form">
                    <div class="form-group">
                        <label for="">Post Title</label>
                        <input type="text" class="form-input" id="post_title" name="post_title" placeholder="Post Title" required>
                    </div>
                    <div class="form-group">
                        <label for="">Post Description</label>
                        <textarea class="form-input" name="post_description" id="post_description" cols="30" rows="10"  placeholder="Post Description" required></textarea>   
                    </div>
                    <div class="form-group">
                        <label for="subject">Make it Featured?</label>
                        <select name="featured_post" id="featured_post" class="form-input">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Image</label>
                        <input type="file" class="form-input" id="post_image" name="post_image" accept=".png, .jpg">
                        <input type="hidden" name="action" value="create_post">
                        <input type="hidden" name="post_id" id="post_id" value="0">
                        <!-- ask kuya for this -->
                    </div>
                    <div class="form-group">
                        <button onclick="submit_btn(this)" id="submit-btn" class="modal-btn" type="button"><i class="fa-solid fa-check"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    <?php }
get_footer();
?>
<script src="function.js"></script>

