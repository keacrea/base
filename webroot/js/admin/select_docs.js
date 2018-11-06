/**
 * Created by bruno on 03/11/2015.
 */

$(document).ready(function() {

    function format(state) {
        if (!state.id) return state.text; // optgroup
        return "<img class='flag' src='" + state.img +"'/>" + "<span class='item_name_doc'>" + state.text +"</span>";
    }

    function formatSelect(state) {
        if (!state.id) return state.text; // optgroup
        return "<span class='item_name_doc'>" + state.text +"</span>";
    }

    $('#DocPage').select2({
        language: "fr",
        width:'100%',
        multiple: true,
        minimumInputLength: 0,
        ajax: {
            url: '/admin/Docs/search_ajax',
            dataType: "json",
            quietMillis: 250,
            data: function(term) {
                return {
                    q: term
                };
            },
            results: function (data) {
                var results = [];
                console.log(data);
                $.each(data, function(index, item){
                    results.push({
                        id: item.name,
                        text: item.name,
                        img: item.image

                    });
                });
                return {
                    results: results
                };
            }
        },
        initSelection: function(element, callback) {
            var data = [];
            console.log(element.val());
            $(element.val().split(",")).each(function () {
                data.push({id: this, text: this});
            });
            callback(data);
        },
        formatResult: format,
        formatSelection: formatSelect,
        escapeMarkup: function(m) { return m; }
    });


});
