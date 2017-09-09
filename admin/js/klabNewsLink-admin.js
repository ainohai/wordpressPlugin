(function( $ ) {
    'use strict';

    $(function() {

        jQuery("klab_in_media_fetch_url").val('#klab_lab_in_media_klabInMedia_url');

        $("#klab_inMediaFetchButton").click(function () {
            console.log("there");
            var url =  document.getElementById("klab_in_media_fetch_url").value;

            event.preventDefault();
            console.log("clicked");
            var data = {
                'action': 'klab_in_media_fetch',
                'nonce': document.getElementById("klab_in_media_fetch_nonce").value,
                'url': url
            };
            jQuery.ajax({
                url: ajaxurl,
                method: "POST",
                data: data,
                success: function (response) {
                    console.log("here");
                    console.log(response);
                    if (response !== null || !response.empty()) {
                        console.log(response['og:title']);
                        console.log(response['og:image']);
                        console.log(response['og:description']);
                        console.log(response['og:site_name']);

                        var title = response['og:title'];

                        jQuery('#title').val(response['og:title']);
                        jQuery('#klab_lab_in_media_klabInMediaUrl').val(url);
                        jQuery('#klab_lab_in_media_klabInMedia_image').val(response['og:image']);
                        jQuery('#klab_lab_in_media_klabInMedia_site_name').val(response['og:site_name']);

                        tinymce.activeEditor.execCommand('mceInsertContent', false, response['og:description']);
                        }

                },
                error: function (data, status, err) {
                    alert("Sorry, couldn't fetch data from http://" + url);
                }
            });

        });

    });

    })( jQuery );




