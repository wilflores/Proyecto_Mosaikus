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
       if(! $('#search').is(":visible")) {
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

    initPanels: function (id) {
        $('#main-content'+id+' .search-show').click(function (event) {
            event.preventDefault();
            PanelOperator.showSearch(id);
        })

        $('#search-bar'+id+' .close-search').click(function (event) {
            event.preventDefault();
            PanelOperator.hideSearch(id);
        })

        $('#main-content'+id+' .close-detail').click(function (event) {
            event.preventDefault();
            PanelOperator.hideDetail(id);
        })

        $('#main-content'+id+' .detail-show').click(function (event) {
            event.preventDefault();
            PanelOperator.showDetail(id);
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
            var slideoutMenu = $('#nav');
            $('#nav').toggleClass("open");
            slideoutMenu.animate({
                width: "56px"
            }, 250);

            $('ul.submenu').hide();
            $(this).hide();
        });

        /****************HOVER DEL MENU*/
        $('#nav').hover(function (event) {
            event.preventDefault();
            var delay = 500;
            var slideoutMenu = $('#nav');

            setTimeoutConst = setTimeout(function () {

                if (!slideoutMenu.hasClass("open")) {
                    slideoutMenu.animate({
                        width: "250px"
                    }, 250);
                    $('#nav').toggleClass("open");
                    $('.hide-menu').show();
                }
            }, delay);
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
            }
            var ul = $(this).attr('href');
            $('ul.submenu').hide();;
            $(ul).toggle();
            $('.li-parent').removeClass('active');
            $(this).parent('.li-parent').addClass('active');

        });

        $('#menu > li.li-parent ul.nav-pills > li >a ').on('click', function (event) {
            event.preventDefault();

            $('#menu > li.li-parent ul.nav-pills > li').removeClass('active');
            $(this).parent('.nav-pills > li').addClass('active');

        });







    }

};


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
    PanelOperator.initPanels('-aux');


    /**********codigo para pruebas **************/


});