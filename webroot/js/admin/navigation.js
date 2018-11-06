/**
 * Menu déroulant des catégories
 * Modification le 06/10/14 Vu avec Bruno
 * Le menu ne se déroule plus au click.
 * Le menu est déroulé quand on est sur la page de la catégorie ou de la sous-catégorie
 */

$(document).ready(function(){

    $('.navigation a').each(function(){
        var submenu = $(this).siblings('ul');
        if($(this).hasClass('active')){
            $(this).parents('ul').show().siblings('a').addClass('active');
        }
    })

    $('.navigation a').on('click', function(event){
        var submenu = $(this).siblings('ul');
        if(submenu[0].childElementCount > 0){
            $(this).siblings('ul').slideToggle();
            event.preventDefault();
        }

    });

});
