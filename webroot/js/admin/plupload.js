/*** UPLOAD DES IMAGES DE L'ANNONCE ***/

var container = $('.js-upload-container'),
    fileList = $('.js-upload-filelist'),
    elementError = $('.js-upload-error'),
    token = $('#token').data('token'),
//  maxFiles = 8,
    divisionId = $('[data-division-id]').data('division-id'),
    url = '/admin/Images/add/' + divisionId;



var uploader = new plupload.Uploader({
    runtimes: 'html5',
    browse_button: 'pickfiles',
    drop_element: container[0],
    url: url,
    headers : {'X-Requested-With' : 'XMLHttpRequest', 'X-CSRF-Token' : token},
    filters: {
        max_file_size: '3mb',
        prevent_duplicates: true,
        mime_types: [
            {
                title: "Image files",
                extensions: "jpg,jpeg,png"
            }
        ]
    },
    init: {
        PostInit: function (up) {

            var items = fileList.children('.upload__list-item');
            items.each(function (i) {
                var name = $(this).data('image-name');
                uploader.files.push(name);

                $(this).on('click', '.upload__btn-remove', function (e) {
                    var c = confirm('Etes vous sur de supprimer cette image ?');
                    if (c == true) {
                        var item = $(this).parents('.upload__list-item'),
                            imageId = item.data('image-id');
                        //  imageName = item.data('image-name');
                        var url = '/admin/Images/delete';

                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                image_id: imageId
                            },
                            beforeSend: function(xhr){
                                xhr.setRequestHeader('X-CSRF-Token', token);
                            }
                        })
                            .done(function(response) {
                                item.fadeOut(500, function () {
                                    $(this).remove();
                                });

                                uploader.files.splice(i, 1);
                            });
                    }
                });
            })
        },

        FilesAdded: function (up, files) {


            plupload.each(files, function (file) {

                var loader = new mOxie.Image(),
                    item = $('<tr></tr>').addClass('upload__list-item').prop('id', file.id).appendTo(fileList),

                    Td = $('<td></td>').appendTo(item),
                    image = $(new Image()).appendTo(Td),

                    TdisDefault = $('<td><input type="radio" name="is_default"></td>').addClass('text-center').appendTo(item),
                    Tdalt = $('<td><input type="text" name="alt"></td>').addClass('text-left').appendTo(item),

                    divPercent = $('<td></td>').addClass('text-center').appendTo(item),
                    loading = $('<div></div>').addClass('upload__loading').appendTo(divPercent),
                    loadingPercent = $('<span></span>').addClass('upload__loading-percent').text(file.percent + '%').prependTo(loading),
                    loadingBar = $('<span></span>').addClass('upload__loading-bar').appendTo(loading),
                    loadingProgress = $('<span></span>').addClass('upload__loading-progress').prependTo(loadingBar);

                loader.onload = function () {
                    loader.downsize(150, 150);
                    image.prop('src', loader.getAsDataURL());
                };

                loader.load(file.getSource());
            });
            up.start();
        },

        UploadProgress: function (up, file) {
            var element = $('#' + file.id);

            $('.upload__loading-percent').text(file.percent + "%");
            $('.upload__loading-progress').css({width: file.percent + '%'});

            if (file.percent == 100) {
                element.addClass('completed');
            }
        },
        Error: function (up, err) {

            elementError.text("\nErreur #" + err.code + " : " + err.message);
            elementError.slideDown(500, function () {
                $(this).addClass('active');
                setTimeout(function () {
                    elementError.slideUp(500, function () {
                        elementError.removeClass('active');
                    })
                }, 3000);
            });
        },
        FileUploaded: function (up, file, response) {

            var item = $('#' + file.id),
                loading = item.find('.upload__loading'),
                removeBtn = $('<span>&#x2716;</span>').addClass('upload__btn-remove').appendTo(loading),

                json = jQuery.parseJSON(response.response);
            var imageId = json.image_id;
            item.attr('id', 'img_' + imageId);

            removeBtn.on('click', function () {
                // Suppression du fichier dans la queue de plupload
                uploader.removeFile(file);

                item.fadeOut(500, function () {
                    $(this).remove();
                });

                var url = '/admin/Images/delete';

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        division_id: divisionId,
                        image_id: imageId,
                        name: file.name
                    },
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('X-CSRF-Token', token);
                    }
                });
            });
        }
    }
});
uploader.init();

$(document).on('change', "input[name='is_default']", function (e) {
    var img_id = ($(this).parents('.upload__list-item').data('image-id'));
    var img_id_loaded = ($(this).parents('.upload__list-item').attr('id').substring(4, 10));
    var image = img_id_loaded != undefined ? img_id_loaded : img_id;
    $.ajax({
        url:  '/admin/Images/is_default',
        type: 'POST',
        dataType: 'json',
        data: {
            realisation_id : divisionId,
            image_id : image
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', token);
        }
    });
    e.preventDefault();
})
    .on('change',"input[name='alt']", function (e) {
        var alt = $(this).val();
        var img_id = ($(this).parents('.upload__list-item').data('image-id'));
        var img_id_loaded = ($(this).parents('.upload__list-item').attr('id').substring(4, 10));
        var image = img_id_loaded != undefined ? img_id_loaded : img_id;
        $.ajax({
            url:  '/admin/Images/alt',
            type: 'POST',
            dataType: 'json',
            data: {
                alt : alt,
                image_id : image
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        });
        e.preventDefault();
    });

$('#filelist').sortable({ // initialisation de Sortable sur #list-photos
    update: function() {  // callback quand l'ordre de la liste est changé
        var order = $('[data-reorder]').sortable('serialize'); // récupération des données à envoyer
        $.ajax({
            url: $('[data-reorder]').data('reorder-url'),
            type: 'POST',
            dataType: 'json',
            data: order,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        })
    }

});

$('[data-reoder]').disableSelection(); // on désactive la possibilité au navigateur de faire des sélections
