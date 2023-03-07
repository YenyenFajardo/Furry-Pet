var close; // used for closing modal by windows.onlick
function openModal(modalID) {
    var modal = document.getElementById(modalID);
    modal.style.display = "block";
    close = modal;
}

function closeModal() {
  close.style.display = "none";
}

window.onclick = function (event) {
  if (event.target == close) {
    close.style.display = "none";
  }
}
function newPost(){
    
    $('#featured-img-view').attr('style', 'background-image: url(http://furbabies.local/wp-content/themes/furfabies-theme/images/no_pic.jpg');
    $('#featured-img-view').removeClass('d-none');
    $('#post-form').removeClass('d-none');
    $('#post-loader').addClass('d-none');
    openModal('createPostModal');
    // Reset the Form and Return back the background image default
    $('#post_id').val(0); // Set back the post ID to 0
    $('#post-form')[0].reset();
    $('#modal_title').html('<i class="fa fa-pencil"></i> Create New Post');
    $('#submit-btn').html('<i class="fa fa-check"></i> Submit'); 
}
function editPost(id){
    $('#featured-img-view').addClass('d-none');
    $('#post-form').addClass('d-none');
    $('#post-loader').removeClass('d-none');

    $('#modal_title').html('<i class="fa fa-pencil"></i> Editing Post');
    $('#submit-btn').html('<i class="fa fa-check"></i> Update');
    openModal('createPostModal');

    $.ajax({
        type: 'POST',
        url: $('#ajaxUrl').val(), 
        data: {
            post_id: id,
            action: 'get_this_post'
        },
        success:(resp)=>{
            var res = JSON.parse(resp.slice(0, -1));
            if(res.status==true){
                console.log(res.data)

                $('#post_id').val(res.data.post_id);
                $('#post_title').val(res.data.post_title);
                $('#post_description').val(res.data.post_description);
                $('#featured_post').val(res.data.featured_post);
                $('#featured-img-view').css('background-image', `url(${res.data.featured_image})`);

                $('#featured-img-view').removeClass('d-none');
                $('#post-form').removeClass('d-none');
                $('#post-loader').addClass('d-none');

            }else{
                // Error message
                toastr.error('We found out that you have an issue with your system');
            }
        },
        error: (e)=>{
            toastr.error('We found out that you have an issue with your system');
        }
    });
}
function deletePost(id, title){
    swal(
    {
        title: `Delete ${title}?`,
        text: "Please note, you will not be able to recover this. Continue?",
        type: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        confirmButtonColor: "#e11641",
    },
    () => {
        $.ajax({
            type: 'POST',
            url: $('#ajaxUrl').val(), // Target the function name to the WordPress. Ex: "get_create_posts_form_example"
            // data: $('#post-form').serialize(),
            // data: {
            //     post_title: $('#post_title').val(),
            //     post_description: $('#post_description').val(),
            // },
            data: {
                post_id: id,
                action: 'delete_catch'
            },
            success:(resp)=>{
                var res = JSON.parse(resp.slice(0, -1)); // We need this code to remove the number 1 on the return
                if(res.status==true){
                    // Success Message
                    swal("Deleted", `Successfully deleted ${title}.`, "success");
                    $('#post_item-'+id).remove();
                }else{
                    // Error message
                    swal("Error", `Failed to delete ${title}.`, "error");
                }
            },
            error: (e)=>{
                swal("Error", `Failed to delete ${title}.`, "error");
            }
        });
    }
    );
}
function submit_btn(e) {
    if($('#post_title').val() && $('#post_description').val()){
        $(e).html('<i class="fa fa-spin fa-spinner"></i> Posting...');
        $(e).attr('disabled','disabled');

        var formData = new FormData(document.getElementById('post-form'));
        $.ajax({
            type: 'POST',
            url: $('#ajaxUrl').val(), 
            data: formData,
            contentType: false,
            processData: false,
            success:(response)=>{
                var res = JSON.parse(response.slice(0, -1)); // We need this code to return
                if(res.status==true){
                    // Success Message
                swal({
                            title: "Post Saved!",
                            text: res.msg,
                            type: "success",
                        },
                        () => {
                            window.location.href='/my-blog/'
                        });

                }else{
                    // Error message
                    swal("Oops", "Post failed to save.", "error");
                }
            },
            error: (e)=>{
                swal("Oops", "Post failed to save.", "error");
            }
        });
    }else{
        // Error message
        swal("Oops", "Please input title or description.", "error");
    }
}

function setAsFeatured(id, feature, e){
    $.ajax({
        type: 'POST',
        url: $('#ajaxUrl').val(), 
        data: {
            post_id: id,
            feature: ((feature =='is-featured-post') ? 'No' :'Yes'), 
            action: 'feature_change',
        },
        success:(response)=>{
            var res = JSON.parse(response.slice(0, -1)); // We need this code to return
            if(res.status==true){
                // Success Message
                if(feature=='is-featured-post'){ // Remove Featured
                    toastr.success('Removed as featured');
                    $(e).removeClass('is-featured-post');
                    $(e).attr('onclick', `setAsFeatured(${id}, '', this)`);
                }else{ //Set to Featured
                    toastr.success('Post set as featured.');
                    $(e).addClass('is-featured-post');
                    $(e).attr('onclick', `setAsFeatured(${id}, 'is-featured-post', this)`);
                }
            }else{
                // Error message
                toastr.error('We found out that you have an issue with your system');
            }
        },
        error: (e)=>{
            swal("Oops", "Post failed to save.", "error");
        }
        
    });
    
}
function newPetPost(){
    
    $('#featured-img-view').attr('style', 'background-image: url(http://furbabies.local/wp-content/themes/furfabies-theme/images/pawp.png');
    $('#featured-img-view').removeClass('d-none');
    $('#pet-post-form').removeClass('d-none');
    $('#post-loader').addClass('d-none');
    openModal('createPetPost');
    // Reset the Form and Return back the background image default
    $('#post_id').val(0); // Set back the post ID to 0
    $('#pet-post-form')[0].reset();
    $('#modal_t').html('<i class="fa fa-pencil"></i> Add New Pet');
    $('#save-btn').html('<i class="fa fa-check"></i> Submit'); 
}
function editPetPost(id){
    $('#featured-img-view').addClass('d-none');
    $('#pet-post-form').addClass('d-none');
    $('#post-loader').removeClass('d-none');

    $('#modal_t').html('<i class="fa fa-pencil"></i> Editing Pet Post');
    $('#save-btn').html('<i class="fa fa-check"></i> Update');
    openModal('createPetPost');

    $.ajax({
        type: 'POST',
        url: $('#ajaxUrl').val(), 
        data: {
            post_id: id,
            action: 'get_pet_post'
        },
        success:(resp)=>{
            var res = JSON.parse(resp.slice(0, -1));
            if(res.status==true){
                console.log(res.data)

                $('#post_id').val(res.data.post_id);
                $('#pet_name').val(res.data.pet_name);
                $('#pet_description').val(res.data.pet_description);
                $('#pet_breed').val(res.data.pet_breed);
                $('#pet_age').val(res.data.pet_age);
                $('#pet_gender').val(res.data.pet_gender);
                $('#pet_weight').val(res.data.pet_weight);
                $('#pet_rescued').val(res.data.pet_rescued);
                $('#featured_post').val(res.data.featured_post);
                $('#featured-img-view').css('background-image', `url(${res.data.featured_image})`);

                $('#featured-img-view').removeClass('d-none');
                $('#pet-post-form').removeClass('d-none');
                $('#post-loader').addClass('d-none');

            }else{
                // Error message
                toastr.error('We found out that you have an issue with your system');
            }
        },
        error: (e)=>{
            toastr.error('We found out that you have an issue with your system');
        }
    });
}
function save(e){
    if($('#pet_name').val() && $('#pet_description').val() && $('#pet_age').val() && $('#pet_gender').val() && $('#pet_weight').val() && $('#pet_rescued').val())
    {
        $(e).html('<i class="fa fa-spin fa-spinner"></i> Posting...');
        $(e).attr('disabled','disabled');

        var formData = new FormData(document.getElementById('pet-post-form'));
        $.ajax({
            type: 'POST',
            url: $('#ajaxUrl').val(), 
            data: formData,
            contentType: false,
            processData: false,
            success:(response)=>{
                var res = JSON.parse(response.slice(0, -1)); // We need this code to return
                if(res.status==true){
                    console.log(res.data);
                    // Success Message
                    swal({
                        title: "Post Saved!",
                        text: res.msg,
                        type: "success",
                    },
                    () => {
                        window.location.href='/furry-pet/'
                    });
                }else{
                    console.log(res.data);
                    // Error message
                    swal("Oops", "Post failed to save.", "error");
                }
            },
            error: (e)=>{
                swal("Oops", "Post failed to save.", "error");
            }
        });
    }
}
// function submit(e) {
//     if($('#pet_name').val() && $('#pet_description').val() && $('#pet_age').val() && $('#pet_gender').val() && $('#pet_weight').val() && $('#pet_rescued').val()){
//         $(e).html('<i class="fa fa-spin fa-spinner"></i> Posting...');
//         $(e).attr('disabled','disabled');

//         var formData = new FormData(document.getElementById('post-form'));
//         $.ajax({
//             type: 'POST',
//             url: $('#ajaxUrl').val(), 
//             data: formData,
//             contentType: false,
//             processData: false,
//             success:(response)=>{
//                 var res = JSON.parse(response.slice(0, -1)); // We need this code to return
//                 if(res.status==true){
//                     // Success Message
//                 swal({
//                             title: "Post Saved!",
//                             text: res.msg,
//                             type: "success",
//                         },
//                         () => {
//                             window.location.href='/my-blog/'
//                         });

//                 }else{
//                     // Error message
//                     swal("Oops", "Post failed to save.", "error");
//                 }
//             },
//             error: (e)=>{
//                 swal("Oops", "Post failed to save.", "error");
//             }
//         });
//     }else{
//         // Error message
//         swal("Oops", "Please fill in the required fields.", "error");
//     }
// }
