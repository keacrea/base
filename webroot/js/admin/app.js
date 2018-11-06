// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation({});

$(document).ready(function () {
    var token = $('#token').data('token');
    $('#checkAll').on('click', function () {
        if ($(this).is(':checked')) {
            $('.checkbox').prop('checked', true);
        } else {
            $('.checkbox').prop('checked', false);
        }
    });


    $('#action-groupee').on('click', function (e) {
        var url = $('#form-groupee').attr('action'),
            datas = $(".checkbox").serializeArray(),
            action = $('#type-action').val();

        if (typeof datas !== 'undefined' && datas.length > 0) {
            if (action === 'delete') {
                $('#delAllModal').foundation('reveal', 'open');
                $('#validDel').on('click', function (e) {
                    $(this).text('Patientez...').prop('disabled',true);
                    AjaxCall(url,action, datas, token);
                    e.preventDefault();
                })
            } else {
                AjaxCall(url,action, datas, token);
            }
        }
        e.preventDefault();
    });

    var AjaxCall = function (url,action, datas, token) {
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: action,
                datas: datas
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        })

            .done(function (response) {
                if (response == 1) {
                    window.location.reload(true);
                }
            });
    };

    $('.online').on('change', function () {
        var form = $(this).parents('form');
        var url = form.attr('action');
        var data = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        }).done(function (response) {});
    })

    $('.label__online').on('click', function () {
        var that = $(this),
            active = $(this).data('active'),
            content_id = $(this).data('content-id'),
            token = $("#token").data('token'),
            url = $(this).data('url');
        that.text('Patientez...');
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                online :active,
                id: content_id
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        }).done(function (response) {

            if (response.active) {
                that.removeClass('alert').addClass('success').text("Actif");
            } else {
                that.removeClass('success').addClass('alert').text("Inactif");
            }

        });
    });


    $('.cancel').on('click', function (e) {
        var id = ($(this).parent('div').attr('id'));
        $('#' + id).foundation('reveal', 'close');
        e.preventDefault();
    })


    $('.alert-box').show(function () {
        $(this).delay(1500).fadeOut(1500, function () {
            $(this).remove()
        })
    })

    $('a.delimg').click(function () {
        var link = $(this).attr('href');
        $.ajax({
            url: link,
            type: 'POST',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        }).done(function (response) {
            if (response.statut === 1) {
                $('#delImg').foundation('reveal', 'close');
                $(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
                    $('#img_' + response.type).fadeOut();
                    $('.upload__input_' + response.type).val('');
                    // $('.upload__input').val('');
                });
            }
        });

        return false;
    })

    $('a.delimgMultiple').click(function () {
        var link = $(this).attr('href');
        $.ajax({
            url: link,
            type: 'POST',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        }).done(function (response) {
            if (response.statut == 1) {
                $('#delImg').foundation('reveal', 'close');
                $(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
                    $('#img_' + response.type).fadeOut();
                    $('.upload__input-' + response.type).val('');
                });
            }
        });
        return false;
    })

    /*** FANCYBOX pour ouvrir l'editeur d'images***/
    $('.lightbox').fancybox({
        width: 900,
        height: 1200,
        minHeight: 600,
        type: 'iframe',
        autoScale: false
    });
    /*** FANCYBOX END ***/


    $('#published').datetimepicker({
        lang: 'fr',
        format: 'd/m/Y H:i',
        dayOfWeekStart: 1,
        step: 60
    });

    var $start = $('#start'),
        $end = $('#end');

    $.datetimepicker.setLocale('fr');
    $start.datetimepicker({
        format: 'd/m/Y H:i',
        dayOfWeekStart: 1,
        step: 60
    });
    $end.datetimepicker({
        format: 'd/m/Y H:i',
        dayOfWeekStart: 1,
        step: 60
    });


    $(".select2").select2({
        language: "fr",
        width: '100%'
    });

    $('#SubmitForm').on('click',function(e){
        e.preventDefault();
        $('.error-message').remove();
        var form = $(this).parents('form');
        var url = form.attr('action');
        var data = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        })
            .done(function (response) {
                var $data = JSON.parse(response.response);
                console.log($data); debugger;
                if($data.status === 0) {
                    $('.field').each(function () {
                        var elt = $(this);
                        var champ = elt.attr('id');
                        console.log(champ);
                        if ($data.errors[champ]) {
                            elt.next('.error-message').remove();
                            elt.addClass('is-invalid-input');
                            $("#"+champ).after('<div class="error-message">' + $data.errors[champ] + '</div>')
                           // $('<div class="error-message">' + $data.errors[champ] + '</div>').appendTo($("#"+champ));
                        }
                    })
                }else{
                    window.location.reload(true);
                }
            });
    })



    $('#addNewStep').on('click',function(e){
        e.preventDefault();
        var form = $(this).parents('form');
        var url = form.attr('action');
        var data = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        })
            .done(function (response) {
                var $data = response;
                if($data.status === 0) {
                    $('.field').each(function () {
                        var elt = $(this);
                        var champ = elt.attr('id');
                        console.log(champ);
                        if ($data.errors[champ]) {
                            elt.next('.error-message').remove();
                            elt.addClass('is-invalid-input');
                            $("#"+champ).after('<div class="error-message">' + $data.errors[champ] + '</div>')
                            // $('<div class="error-message">' + $data.errors[champ] + '</div>').appendTo($("#"+champ));
                        }
                    })
                }else{
                    window.location.reload(true);
                }
            });
    })

})
