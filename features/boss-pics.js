(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery', 'CropAvatar'], factory);
    } else if (typeof exports === 'object') {
        // Node / CommonJS
        factory(require('jquery'), require('CropAvatar'));
    } else {
        factory(jQuery,CropAvatar);
    }
})(function ($, CropAvatar) {

    'use strict';

    var console = window.console || { log: function () {} };

    $(function () {

        var reader = new FileReader();
        var cropper;

        reader.onload = function (e) {
            if(cropper) {
                cropper.cropper('destroy');
            }
            $('#nn-boss-manual-preview-img').attr('src', e.target.result);
            cropper = enableCropper($('#nn-boss-manual-preview-img'));
        };

        function readURL(input) {
            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }

        function enableCropper($img) {
            $('#nn-boss-crop-data').val('{}');
            return $img.cropper({
                aspectRatio: 1,
                crop: function(data) {
                    console.log(data);
                    $('#nn-boss-crop-data').val(JSON.stringify(data));
                }
            });
        }

        $("#nn-boss-file").change(function(){
            readURL(this);
        });

        var updatePreviewImages = function () {

            if ($(this).is(":checked")) {
                $(".nn-boss-gravatar-preview").removeClass("hidden");
                $(".nn-boss-manual-preview").addClass("hidden");
            }
            else {
                $(".nn-boss-gravatar-preview").addClass("hidden");
                $(".nn-boss-manual-preview").removeClass("hidden");

            }
        };

        var gravatarCheckbox = $("#nn-boss-use-gravatar");

        gravatarCheckbox.change(updatePreviewImages);
        updatePreviewImages(gravatarCheckbox);


    });

});