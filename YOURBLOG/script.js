$(document).ready(function (e) {
    $("#uploadimage").on('submit',(function(e) {
        e.preventDefault();
        $("#message").empty();
        var post_data= new FormData(this);
        var file = document.getElementById('file').files[0];
        post_data.append('file', file);
        $.ajax({
            url: "upload.php",
            type: "POST",
            data: post_data, 
            contentType: false,
            cache: false,
            processData:false,
            success: function(data) {
                $("#message").html(data);
            }
         });
}));


$(function() {
$("#file").change(function() {
$("#message").empty();
var file = this.files[0];
var imagefile = file.type;
var match= ["image/jpeg","image/png","image/jpg","image/gif"];
if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3]))) {
    $('#previewing').attr("src","noimage.png");
    $("#message").html("<p id='error'>Seleziona un'immagine valida</p>"+"<h4>Note</h4>"+"<span id='error_message'>Ammessi solamente le seguenti estensioni: jpeg, jpg e png </span>");
    return false;
}
else {
    var reader = new FileReader();
    reader.onload = imageIsLoaded;
    reader.readAsDataURL(this.files[0]);
}
});
});

function imageIsLoaded(e) {
$("#file").css("color","green");
$('#image_preview').css("display", "block");
$('#previewing').attr('src', e.target.result);
$('#previewing').attr('width', '250px');
$('#previewing').attr('height', '230px');
};
});

