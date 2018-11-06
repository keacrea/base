
tinymce.init({
    selector: "textarea.resume",
    theme: "modern",
    language : 'fr_FR',
    width:'100%',
    height: 150,
    statusbar: false,
    menubar: false,
    relative_urls: false,
    forced_root_block : false,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons paste textcolor"
    ],
    external_filemanager_path: "/filemanager/",
    filemanager_access_key: $('#tiny').data('tiny') ,
    filemanager_title: "Responsive Filemanager",
    external_plugins: {"filemanager": "/filemanager/plugin.min.js"},
    toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink anchor | print preview code ",
    image_advtab: true,
    content_css : "/css/app.css",
    body_class: "text-editor"
});


/**
 * Created by bruno on 24/03/14.
 */
tinymce.init({
    selector: "textarea.tiny",
    language: 'fr_FR',
    width: '100%',
    height: 500,
    relative_urls: false,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons paste textcolor"
    ],
    toolbar1: "undo redo | bold italic underline |  fontsizeselect | styleselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
    toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
    image_advtab: true,
    fontsize_formats: "10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 30px",
    external_filemanager_path: "/filemanager/",
    filemanager_access_key: $('#tiny').data('tiny'),
    filemanager_title: "Responsive Filemanager",
    external_plugins: {"filemanager": "/filemanager/plugin.min.js"},
    content_css: "/css/app.css",
    body_class: "text-editor",
    plugin_preview_width: "1000",
    plugin_preview_height: "700",
    style_formats: [
        {
            title: 'Image Left',
            selector: 'img',
            styles: {'float': 'left', 'margin': '0 10px 10px 0'}
        },
        {
            title: 'Image Right',
            selector: 'img',
            styles: {'float': 'right', 'margin': '0 0 10px 10px'}
        },
        {
            title: "Blocks",
            items: [
                {title: "Paragraphe", block: "p"},
                {title: "Blockquotes", block: "blockquote"}
            ]
        },
        {
            title: "Headers",
            items: [
                {title: "Header 2", block: "h2"},
                {title: "Header 3", block: "h3"},
                {title: "Header 4", block: "h4"}
            ]
        },
        {
            title: "Liste",
            items: [
                {title: "2 colonnes", block: "ul", classes:'double'},
                {title: "3 colonnes", block: "ul", classes:'triple'}
            ]
        },
        {
            title: "Puces",
            items: [
                {title: "Check", selector: "li", classes:'check-list'},
            ]
        },
        {
            title: "Teléchargement",
            selector: 'a',
            classes: 'download'
        },
        {
            title: "Boutons",
            items: [
                {
                    title: 'Rouge',
                    selector: 'a',
                    classes: 'btn'
                },
                {
                    title: 'Blanc',
                    selector: 'a',
                    classes: 'btn btn__white'
                }
            ]
        }
    ]
});
