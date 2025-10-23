/*
Disable Submit button while uploading file
change the fileuplaod and submit button selector
*/

jQuery(document).ready(function($){
    const fileUpload = $('#ff_596_file-upload_1'); //Change the file upload field id
    const submitbutton = $('.ff-btn.ff-btn-submit'); //Change the submit button class
    
    fileUpload.on('fileuploadstart', function (e, data) {
        submitbutton.prop('disabled', true);
        
    });

    fileUpload.on('fileuploaddone', function (e, data) {
        submitbutton.prop('disabled', false);
    });
});