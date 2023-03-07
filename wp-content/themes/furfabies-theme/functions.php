<?php 
function furbabies_files() {
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('awesome_font', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css');
    wp_enqueue_style('dancing', '//fonts.googleapis.com/css2?family=Dancing+Script&display=swap');
    wp_enqueue_style('furbabies_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('furbabies_main_vendor', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('sweetalert_style', get_theme_file_uri('/sweetalert/sweetalert.min.css'));
    wp_enqueue_style('toastr_style', get_theme_file_uri('/toastr/toastr.min.css'));

    wp_enqueue_script('jquery-plugin', 'https://code.jquery.com/jquery-3.6.3.min.js', NULL, '1.0', true);
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=yourkeygoeshere', NULL, '1.0', true);
    wp_enqueue_script('furbabies_main_script', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_script('sweetalert_js', get_theme_file_uri('/sweetalert/sweetalert.min.js'), array('jquery'), '1.0', true);
    wp_enqueue_script('toastr_js', get_theme_file_uri('/toastr/toastr.min.js'), array('jquery'), '1.0', true);
    wp_enqueue_script('function_js', get_theme_file_uri('/js/function.js'), array('jquery'), '1.0', true);

    wp_localize_script('furbabies_main_script', 'universityData', array('root_url' => get_site_url(), 'nonce' => wp_create_nonce('wp_rest') ));
}
add_action('wp_enqueue_scripts', 'furbabies_files');

function furbabies_features() {
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'furbabies_features');

function relative_date($time) {
    $time    = strtotime($time);
    $today   = strtotime(date('M j, Y'));
    $hrs     = date("h:i A", $time);
    $reldays = ($time - $today)/86400;

    if ($reldays >= 0 && $reldays < 1) {
        return 'Today, '.$hrs;
    } else if ($reldays >= 1 && $reldays < 2) {
        return 'Tomorrow, '.$hrs;
    } else if ($reldays >= -1 && $reldays < 0) {
        return 'Yesterday, '.$hrs;
    }
        
    if (abs($reldays) < 7) {
        if ($reldays > 0) {
            $reldays = floor($reldays);
            return 'In ' . $reldays . ' day' . ($reldays != 1 ? 's' : '');
        } else {
            $reldays = abs(floor($reldays));
            return $reldays . ' day' . ($reldays != 1 ? 's' : '') . ' ago';
        }
    }
        
    if (abs($reldays) < 182) {
        return date('l, j F',$time ? $time : time());
    } else {
        return date('l, j F, Y',$time ? $time : time());
    }
}

add_action( 'wp_ajax_nopriv_delete_catch', 'delete_catch' );
add_action( 'wp_ajax_delete_catch', 'delete_catch' );
function delete_catch() {
    /* 
        * Extract the data in from this code "$_POST"
        * Ajax or Javascript sends you this data from the form in an array
    */ 
    $post_id = $_POST['post_id'] ; // This is the post ID you are deleting
    $delete = wp_delete_post($post_id);
    if($delete){
        // Return data to Javascript
        echo json_encode([
            'code'     => 200,
            'status'    => true,
            'data'      => $_POST['post_id'], 
            'msg'      => 'Post deleted successfully.',
    ]);
    }else{
        // Return data to Javascript
        echo json_encode([
            'code'     => 200,
            'status'    => true,
            'data'      => $_POST['post_id'], 
            'msg'      => 'Failed to delete.',
        ]);
    } 
}

add_action('wp_ajax_create_post', 'create_post');
add_action('wp_ajax_nopriv_create_post', 'create_post');
function create_post() {
    /* 
        * This code is for Insert and Update Post
        * Update the Post if the "$_POST['post_id']" is not 0
    */
    $new_post = array(
        'post_title' => $_POST['post_title'],
        'post_content' => $_POST['post_description'],
        'post_type' => 'post',
        'post_status' => 'publish'
    );
   
    if($_POST['post_id']){
        $new_post['ID'] = $_POST['post_id'];
        wp_update_post( $new_post );
        $post_id = $_POST['post_id'];;
    }else{
        $post_id = wp_insert_post( $new_post );
    }

    if( $post_id ){
        // Upload galleries
        $post_img_id = upload_files_and_save($post_id, '');
        if($post_img_id){
            update_field('featured_image', $post_img_id, $post_id);
        }
        update_field('featured_post', $_POST['featured_post'], $post_id);
    }
     echo json_encode([
        'code'     => 200,
        'status'    => ($post_id) ? true: false,
        'data'      => [], 
        'msg'      => ($post_id) ? 'Post added successfully.': 'Post failed to add. Please try again',
    ]);
}

add_action('wp_ajax_get_this_post', 'get_this_post');
add_action('wp_ajax_nopriv_get_this_post', 'get_this_post');
function get_this_post() {
    $args = array(
        'post_type'      => array('post'),
        'p'             => $_POST['post_id'], 
    );
    $query = new WP_Query( $args );
    $post = $query->posts[0];

    // // Return data to Javascript
    echo json_encode([
        'code' => 200,
        'status' => true,
        'data' => array(
            'post_id'          => $post->ID,
            'post_title'       => $post->post_title,
            'post_description' => $post->post_content,
            'featured_post'    => (get_field('featured_post', $post->ID) == 'Yes') ? 'Yes' : 'No',
            'featured_image'   => (get_field('featured_image', $post->ID)) ? get_field('featured_image', $post->ID) : get_theme_file_uri( '/images/no_pic.jpg' ),
        ),
        'msg' => 'Post fetched successfully.',
    ]);
     
}

// Upload Files
function upload_files_and_save($post_id, $attach_id) {
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    // for multiple file upload.
    $upload_overrides = array( 'test_form' => false );
    $files = $_FILES['post_image'];
    if($_FILES['post_image']['name']){
        if ( $files['name'] ) {
            $file = array(
                'name'         => $files['name'],
                'type'         => $files['type'],
                'tmp_name'     => $files['tmp_name'],
                'error'     => $files['error'],
                'size'         => $files['size']
            );
    
            $movefile = wp_handle_upload( $file, $upload_overrides );

            if ( $movefile && !isset($movefile['error']) ) {
                $wp_upload_dir = wp_upload_dir();
                $attachment = array(
                    'guid'              => $wp_upload_dir['url'] . '/' . basename($movefile['file']),
                    'post_mime_type' => $movefile['type'],
                    'post_title'      => preg_replace( '/\.[^.]+$/','', basename($movefile['file'])),
                    'post_content'      => '',
                    'post_status'      => 'inherit'
                );
                $attach_id = wp_insert_attachment($attachment, $movefile['file']);
                if($attach_id){
                    set_post_thumbnail( $post_id, $attach_id );
                }else{
                    set_post_thumbnail( $post_id, $post_img_id );
                }
            }
        }
    }
    return $attach_id;
}
add_action( 'wp_ajax_nopriv_feature_change', 'feature_change' );
add_action( 'wp_ajax_feature_change', 'feature_change' );
function feature_change() {
    update_field('featured_post', $_POST['feature'], $_POST['post_id']);
    
    echo json_encode([
        'code'     => 200,
        'status'    => true,
        'data'      => $_POST['post_id'], 
        'msg'      => 'Post set to featured or not featured successfully.',
    ]);
}
add_action('wp_ajax_get_pet_post', 'get_pet_post');
add_action('wp_ajax_nopriv_get_pet_post', 'get_pet_post');
function get_pet_post() {
    $args = array(
        'post_type'      => array('furry-pet'),
        'p'             => $_POST['post_id'], 
    );
    $query = new WP_Query( $args );
    $post = $query->posts[0];

    // // Return data to Javascript
    echo json_encode([
        'code' => 200,
        'status' => true,
        'data' => array(
            'post_id'          => $post->ID,
            'pet_name'       => $post->post_title,
            'pet_description' => $post->post_content,
            'pet_breed' => $post->breed,
            'pet_age' => $post->age,
            'pet_gender' => $post->gender,
            'pet_weight' => $post->weight,
            'pet_rescued' => $post->rescued_from,
            'featured_post'    => (get_field('featured_post', $post->ID) == 'Yes') ? 'Yes' : 'No',
            'featured_image'   => (get_field('featured_image', $post->ID)) ? get_field('featured_image', $post->ID) : get_theme_file_uri( '/images/no_pic.jpg' ),
        ),
        'msg' => 'Post fetched successfully.',
    ]);
}
add_action('wp_ajax_create_petPost', 'create_petPost');
add_action('wp_ajax_nopriv_create_petPost', 'create_petPost');
function create_petPost() {
    $new_post = array(
        'post_title' => $_POST['pet_name'],
        'post_content' => $_POST['pet_description'],
        'post_type' => 'furry-pet',
        'post_status' => 'publish',
        'meta_input' => array(
            'breed' => $_POST['pet_breed'],
            'age' => $_POST['pet_age'],
            'gender' => $_POST['pet_gender'],
            'weight' => $_POST['pet_weight'],
            'rescued_from' => $_POST['pet_rescued'],
        )
    );
    if($_POST['post_id']){
        $new_post['ID'] = $_POST['post_id'];
        wp_update_post( $new_post );
        $post_id = $_POST['post_id'];;
    }else{
        $post_id = wp_insert_post( $new_post );
    }

    if( $post_id ){
        // Upload galleries
        $post_img_id = upload_files_and_save($post_id, '');
        if($post_img_id){
            update_field('featured_image', $post_img_id, $post_id);
        }
        update_field('featured_post', $_POST['featured_post'], $post_id);
    }
     echo json_encode([
        'code'     => 200,
        'status'    => ($post_id) ? true: false,
        'data'      => [], 
        'msg'      => ($post_id) ? 'Post added successfully.': 'Post failed to add. Please try again',
    ]);
}
