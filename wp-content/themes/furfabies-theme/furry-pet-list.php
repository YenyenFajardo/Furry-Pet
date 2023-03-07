<?php 
/*
Template Name: Furry Pet Lists
*/

get_header();?>
        <!-- Search Code -->
        <?php
            $paged                     = 1;
            $paginate_url            = explode("/", $_SERVER['REQUEST_URI']);
            // Search Field
            $keyword = isset($_GET['search']) ? $_GET['search'] : '';

            if(count($paginate_url) == 5){
                if($paginate_url[2] == 'page'){
                    $paged = $paginate_url[3];
                }
            }
            function pagination( $paged = '', $max_page = '' )
            {
                if( !$paged )
                    $paged = get_query_var('paged');
                if( !$max_page )
                    $max_page = $wp_query->max_num_pages;
                return paginate_links( array(
                    'current'    => max(1, $paged),
                    'total'      => $max_page,
                    'mid_size'   => 1,
                    'prev_text'  => __('<i class="fa-solid fa-chevron-left"></i>'),
                    'next_text'  => __('<i class="fa-solid fa-chevron-right"></i>'),
                    'type'       => 'block'
                ) );
            }
        ?>
        <div class="hero-slider">
            <div data-glide-el="track" class="glide__track">
                <div class="glide__slides">
                    <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/adorable.jpg') ?>)">
                        <div class="hero-slider__interior container">
                            <div class="hero-slider__overlay">
                                <h2 class="headline headline--medium t-center">FURRY PET</h2>
                                <p class="t-center no-margin">ADOPT</br>DON'T SHOP</br>AND SAVE A LIFE!</p>
                            </div>
                        </div>
                    </div>
                <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/doggies.jpg')?>)">
                    <div class="hero-slider__interior container"></div>
                </div>
                <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/lying.jpg')?>)">
                    <div class="hero-slider__interior container"></div>
                </div>
            </div>
            <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
        </div>
        
        <?php 
            $args = array(
                'post_type'      => array('furry-pet'),
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'orderby'        => 'date',
                'sort_order'     => 'ASC',
                'paged'          => $paged,
                's'             => $keyword,
            );
            $query = new WP_Query( $args);
            $posts = $query->posts;

            $post_count = ($posts)?$query->found_posts: 0;
            $pets_text = ($post_count > 1) ? 'PETS' : 'PET';
        ?>

        <?php if ($posts){?>
            <div class="generic-content">
                <div class="blog-wrapper">
                    <div class="quote">
                        <img class="img" src="<?=get_theme_file_uri( '/images/wence.JPG' );?>"></img>
                        <h3>I AM RESCUED<i class="fa-solid fa-paw"></i><p>You didn't care how I looked </br>or that I wasn't a pedigree</br>You showed me I am not disposable</br>and that i am loved</br>You brought back the sparkle in my eye</br>and the shine of my coat</br>You restored my spirit</br>so my tail can wag again</br>You took a chance on me</br>to see what I can become</br>You gave me a place to call home</br>and a family to call MY OWN<i class="fa-solid fa-heart"></i></p></h3>
                    </div>
                    <div class="count-pet">
                        <h3>Give them a forever home <i class="fa fa-home" aria-hidden="true"></i></h3> <i class="fa-solid fa-paw"></i> <?php echo $post_count; ?> FURRY <?php echo $pets_text; ?> WAITING FOR YOU
                    </div>
                    <div class="custom-search-wrapper">
                        <form action="/pets/" method="get">
                            <!-- <i class="ti-search"></i> -->
                            <input type="search" name="search" id="search-input" class="input-search" value="<?=$keyword;?>" placeholder="Search here...">
                            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>
                    </div>
                    <button class="blog-btn" onclick="newPetPost()"><i class="fa-solid fa-plus"></i> Add New Pet</button>
                    <ul>
                        <?php foreach($posts as $post) { ?>
                        <li id="post_item-<?=$post->ID?>">
                            <?php $featured_img = get_field('featured_image', $post->ID);
                                $is_featured = (get_field('featured_post', $post->ID) =='Yes'? 'is-featured-post' :'');
                            ?>
                            <button class="featured-post <?=$is_featured?>" onclick="setAsFeatured(<?=$post->ID?>, '<?=$is_featured;?>', this)"><i class="fa-solid fa-paw"></i></button>
                            <a href="<?php echo get_permalink(); ?>">
                            <div class="blog-image" style="background-image: url(<?=$featured_img ? $featured_img: get_theme_file_uri( '/images/pawp.png' ); ?>"></div></a>
                            <div class="blog-content">
                                <a href="<?php echo get_permalink();?>"><h4><?=$post->post_title;?></h4></a>
                                <p class="blog-gender"><?=$gender = get_field('gender', $post->ID);?></p>
                                <!-- <p class="blog-date"><//?= get_the_author_meta( 'display_name',        $post->post_author); ?> | <//?=relative_date($post->post_date); ?></p> -->
                                <div class="blog-desc"><?=($post->post_content) ? ((strlen($post->post_content) <= 150) ? $post->post_content : substr(strip_tags($post->post_content), 0, 150).'...') : 'No Content'; ?></div>
                                <div class="blog-actions">
                                    <a href="javascript:;" id="<?= $post->ID?>" class="blog-btn" onclick="editPetPost('<?=$post->ID?>')"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="javascript:;" class="blog-btnD" onclick="deletePost('<?=$post->ID?>','<?=$post->post_title?>')"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </div>
                        </li>
                        <?php } ?> 
                    </ul>
                </div>
            </div>
            <!-- Pagination -->
            <?php if(pagination($paged, $query->max_num_pages)){ ?>
                <div class="pagination-list">
                    <?=pagination($paged, $query->max_num_pages); ?>
                </div>
            <?php } ?>
        <?php }else{ ?>
            <div class="custom-alert">
                <strong>Oops!</strong> We don't have any available posts for now <i class="fa-solid fa-paw"></i>
            </div>
        <?php } ?>
        <!-- The Modal -->
        <div id="createPetPost" class="modal">
            <!-- Modal content -->
            <div class="modal-content" id="modal-wrapper">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal_t"> <i class="fa fa-plus"></i> Add New Pet</h6>
                    <span class="close" onclick="closeModal('createPetPost')">&times;</span>
                </div>
                <!-- Waiting Loader -->
                <div class="waiting-loader d-none" id="post-loader"><i class="fa fa-spin fa-spinner"></i> <span>Loading post. Please wait...</span></div>
                <!-- Featured Image -->
                <div class="modal-image" id="featured-img-view" style="background-image: url(<?= get_theme_file_uri('/images/pawp.png')?>;"></div>
                <!-- Post Form -->
                <form action="" method="post" id="pet-post-form">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-input" id="pet_name" name="pet_name" placeholder="Pet Name" required>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea class="form-input" name="pet_description" id="pet_description" cols="30" rows="10"  placeholder="Pet Description" required></textarea>   
                    </div>
                    <div class="form-group">
                        <label for="">Breed</label>
                        <input type="text" class="form-input" id="pet_breed" name="pet_breed" placeholder="Pet Breed" required>
                    </div>
                    <div class="form-group">
                        <label for="">Age</label>
                        <input type="text" class="form-input" name="pet_age" id="pet_age" cols="30" rows="10"  placeholder="Pet Age" required></input>   
                    </div>
                    <div class="form-group">
                        <label for="">Gender</label>
                        <select name="pet_gender" id="pet_gender" class="form-input" required>
                            <option value="" disabled selected></option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>                    
                    </div>
                    <div class="form-group">
                        <label for="">Weight</label>
                        <input type="text" class="form-input" name="pet_weight" id="pet_weight" cols="30" rows="10"  placeholder="Pet Weight" required></input>   
                    </div>
                    <div class="form-group">
                        <label for="">Rescued From</label>
                        <textarea class="form-input" name="pet_rescued" id="pet_rescued" cols="30" rows="10"  placeholder="Rescued From" required></textarea>   
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
                        <input type="hidden" name="action" value="create_petPost">
                        <input type="hidden" name="post_id" id="post_id" value="0">
                    </div>
                    <div class="form-group">
                        <button onclick="save(this)" id="save-btn" class="modal-btn" type="button"><i class="fa-solid fa-check"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    <?php
get_footer();
?>
