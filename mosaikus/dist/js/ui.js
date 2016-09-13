/** Obejto encargado del manejo de los Panels **/

var PanelOperator = {

    max: 24,


    actualSize: function (target) {
        var classList = $(target).attr('class').split(/\s+/);
        if ($(target).height() > 0) {
            var out;
            $.each(classList, function (index, item) {

                if (item.toLowerCase().indexOf("col-xs-") >= 0) {

                    out = item.split('-')[2];
                    return;
                }
            });

            return out;
        }
        return 0;
    },

    /**
     *  Serch o primeal columna
     */
    hideSearch: function (id) {
        var one = this.actualSize('#search'+id);
        var main = this.actualSize('#main-content'+id);        
        $('#main-content'+id).removeClass('col-xs-' + main.toString());
        $('#main-content'+id).addClass('col-xs-' + (parseInt(main) + parseInt(one)).toString());
        $('#main-content'+id).addClass('col-md-offset-1');
        $('#main-content'+id+' .search-show').show();
        $('#search'+id).hide();


    },
    
    showSearch: function (id) {
        if (!$('#search'+id).is(":visible")) {
            var one = this.actualSize('#search'+id);
           var main = this.actualSize('#main-content'+id);

           $('#main-content'+id).removeClass('col-xs-' + main.toString());
           $('#main-content'+id).addClass('col-xs-' + (parseInt(main) - parseInt(one)).toString());
           $('#main-content'+id).removeClass('col-md-offset-1');
           $('#search'+id).show();
           $('#main-content'+id+' .search-show').hide();

        }

    },

    /**
     * Deatil Columna Derecha
     */

    hideDetail: function (id) {
        var one = this.actualSize('#detail-content'+id);
        var main = this.actualSize('#main-content'+id);
        $('#main-content'+id).removeClass('col-xs-' + main.toString());
        $('#main-content'+id).addClass('col-xs-' + (parseInt(main) + parseInt(one)).toString());
        $('#detail-content'+id).hide();
        /**
         * Muestra los titulos de las acciones del panel
         */
        $('#main-content'+id+' .panel-actions').toggleClass('small');


    },


    showDetail: function (id) {
        if(! $('#detail-content'+id).is(":visible")) {
            var one = this.actualSize('#detail-content'+id);
            var main = this.actualSize('#main-content'+id);
            $('#main-content'+id).removeClass('col-xs-' + main.toString());
            $('#main-content'+id).addClass('col-xs-' + (parseInt(main) - parseInt(one)).toString());
            $('#detail-content'+id).show();
            /**
             * Oculta los titulos de las acciones del panel
             */
            $('#main-content'+id+' .panel-actions').toggleClass('small');
        }


    },

   
    resize : function () {
        var top = 0;
        $('.panels .content').height('auto');
        var bigger = null;
       $('.panels .content').each(function( index ) {

           var itemHeight = $(this).height();

           if(itemHeight > top ){
               top = itemHeight;
               bigger = this;
           }
       });
        $('.panels .content').height(top);
        $(bigger).height('auto');




    },

    initPanels: function (id) {


        $('#main-content'+id+' .search-show').click(function (event) {
            event.preventDefault();
            PanelOperator.showSearch(id);
            PanelOperator.resize();
        })

        $('#search-bar'+id+' .close-search').click(function (event) {
            event.preventDefault();
            PanelOperator.hideSearch(id);

        })

        $('#detail-content'+id+' .close-detail').click(function (event) {
            event.preventDefault();
            PanelOperator.hideDetail(id);
        })

        $('.detail-show').click(function (event) {
            event.preventDefault();
            PanelOperator.showDetail(id);
        });

        //$('.panels .panel').resize(function (event) {
        $('.panels .panel').resize(function (event) {
            event.preventDefault();
            PanelOperator.resize();
        });

        $('.collapsible').on('shown.bs.collapse', function () {
            PanelOperator.resize();
        })
        
        $('.collapsible').on('hidden.bs.collapse', function () {
            PanelOperator.resize();
        })


    }





};

var MenuHandler = {

    initMenu: function () {
        var setTimeoutConst;
        $('#menu li > ul.submenu').hide();
        $('.hide-menu').hide();

        $('.hide-menu').on('click', function (event) {
            event.preventDefault();
            MenuHandler.hideMenu();
        });

        /****************HOVER DEL MENU*/
        $('#nav').hover(function (event) {
            event.preventDefault();
            MenuHandler.showMenu("500px");

        }, function () {
            clearTimeout(setTimeoutConst);

        });


        $('#menu > li > a').on('click', function (event) {
            event.preventDefault();
            var slideoutMenu = $('#nav');

            if (slideoutMenu.hasClass("open")) {
                slideoutMenu.animate({
                    width: "500px"
                }, 'fast');
            }else{
                slideoutMenu.finish();
                MenuHandler.showMenu("500px");
            }
            var ul = $(this).attr('href');
            $('ul.submenu').hide();;
            $(ul).toggle();

        });

        $('#menu  li > a ').on('click', function (event) {
            event.preventDefault();

            $(this).closest('li').siblings().removeClass('active');
            $(this).closest('li').addClass('active');

        });

        $(document).click(function (e) {
            e.stopPropagation();
            var container = $("#menu");

            //check if the clicked area is dropDown or not
            if (container.has(e.target).length === 0) {
               MenuHandler.hideMenu();
            }
        })

    },

    hideMenu :  function (){
        var slideoutMenu = $('#nav');
        slideoutMenu.toggleClass("open");
        slideoutMenu.animate({
            width: "56px"
        }, 250);

        $('ul.submenu').hide();
        $('.hide-menu').hide();
    },

    showMenu : function(width){
        var delay = 500;
        var slideoutMenu = $('#nav');

        setTimeoutConst = setTimeout(function () {

            if (!slideoutMenu.hasClass("open")) {
                slideoutMenu.animate({
                    width: width
                }, 'fast');
                slideoutMenu.toggleClass("open");
                $('.hide-menu').show();
            }
        }, delay);
    }
};

var ScrollBar ={
    initScroll: function(){
        $('.scrollable').perfectScrollbar();
    }
}

var Loader ={
    loading: function(){
        $('#MustraCargando').show();
    },
    done:function () {
        $('#MustraCargando').hide();
    }
}

/**
 * Created by Juziel Indriago on 11/29/2015.
 */
$(function () {
    /**
     * JSON TREE activator
     */
    $('.jstree-container').jstree();


    /*** PANELS CHANGE ***/


    /**MAIN MENU ******/
    MenuHandler.initMenu();
    PanelOperator.initPanels('');
    ScrollBar.initScroll();


    /**********codigo para pruebas **************/


});


