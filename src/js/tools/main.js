(function(){
    window.getCookie = function(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');

        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];

            while (c.charAt(0)==' ') {
                c = c.substring(1);
            }

            if (c.indexOf(name) == 0) {
                return c.substring(name.length,c.length);
            }
        }

        return "";
    }

    window.setCookie = function(cname, cvalue, exdays) {
        exdays > 0 && setCookie(cname, '', -1);
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; path=/; " + expires;
    };

    window.deleteCookie = function(cname) {
        setCookie(cname, '', -1)
    }

    window.closeModals = function() {
        $('.modal--open, .inline-modal--open, .nav-modal--open').removeClass('modal--open inline-modal--open nav-modal--open');
        $('.modal-breakout').remove();
    };

    window.getSelectionQuery = function($selected)
    {
        var deepids = $selected.map(function(){
            return $(this).data('type') + ':' + $(this).data('id');
        }).get();

        return 'deepid=' + deepids.join(',');
    }

    window.getSelected = function()
    {
        return $('tr[data-id] .select-column input[type="checkbox"]:checked').closest('tr[data-id]');
    }

    $('#loginform').on('submit', function(e){
        e.preventDefault();

        $.ajax('/ajax/auth/login', {
            method: 'post',
            contentType: false,
            processData: false,
            data: JSON.stringify({username: $(this).find('[name="username"]').val(), password: $(this).find('[name="password"]').val()}),
            success: function(data) {
                if (typeof data == 'string') {
                    setCookie('token', data);
                    window.location.reload();
                } else {
                    alert(data.error || 'Unknown error');
                }
            },
            error: function(data){
                alert(data.responseJSON && data.responseJSON.error || 'Unknown error');
            }
        });
    });

    $('.trigger-logout').on('click', function(e){
        e.preventDefault();

        if (!getCookie('token') || !confirm('Logout?')) {
            return;
        }

        $.ajax('/ajax/auth/logout', {
            method: 'post',
            contentType: false,
            processData: false,
            beforeSend: function(request) {
                request.setRequestHeader("X-Auth", getCookie('token'));
            },
            success: function(data) {
                deleteCookie('token');
                window.location.href = '/';
            },
            error: function(data){
                alert(data.responseJSON && data.responseJSON.error || 'Unknown error');
            }
        });
    });

    $('#tokenform').on('submit', function(e){
        e.preventDefault();
        setCookie('token', $(this).find('[name="token"]').val());
        window.location.reload();
    });

    $('.fromtoday').on('click', function(e){
        e.preventDefault();
        var today = new Date();

        $(this).prevAll().each(function() {
            if ($(this).is('input')) {
                $(this).val(today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0'));
            }
        });
    });

    var onResize = function() {
        if ($('.calendar-month').length && $('body').height() < $(window).height()) {
            var avail = $(window).height() - $('.daterow').first().offset().top - ($('body').width() >= 800 && 10 || 0);
            var each = Math.min($(window).height() / 5, (Math.floor(avail / $('.eventrow').length) - $('.daterow').first().height())) + 'px';
            $('.eventcell').css('height', each);
        } else {
            $('.eventcell').css('height', '');
        }

        $('.cvdump-standin').css('height', $('.cvdump').height() + 'px');

        $('.samewidth').each(function(){
            var $children = $(this).find('> *');
            var max = 0;

            $children.css({width: '', display: 'inline-block'});

            $children.each(function(){
                max = Math.max(max, $(this).outerWidth());
            });

            $children.css({width: Math.ceil(max) + 'px', display: ''});
        });

        $('br + .navset').prev().remove();
        $('.navset:not(:first-child)').removeClass('navset--nobar');

        var prevNavsetTop = null;

        $('.navset').each(function(){
            var navsetTop = $(this).offset().top;
            var nobar = (prevNavsetTop == null || Math.abs(navsetTop - prevNavsetTop) > 10);

            $(this).toggleClass('navset--nobar', nobar);

            if (prevNavsetTop !== null && nobar) {
                $('<br class="navbr">').insertBefore($(this));
            }

            prevNavsetTop = navsetTop;
        });

        $('.navbar-placeholder').height($('.navbar').outerHeight() + 'px');

        $('body').toggleClass('wsidebar', $(window).width() >= 1200);
    };

    var resizeTimer = null;

    $(window).on('resize', function(){ clearTimeout(resizeTimer); resizeTimer = setTimeout(onResize, 300); });

    onResize();

    $('.modal-trigger').on('click', function(e){
        e.preventDefault();

        var done = false;
        var $modal = $('#' + $(this).data('for'));

        $modal.addClass('modal--open');
        $('body').append($('<div class="modal-breakout">'));
    });

    $('body').on('click', '.modal-breakout', closeModals);
    $('.close-modal').on('click', closeModals);

    $(document).on('keyup', function(event) {
        if (event.key == "Escape") {
            closeModals();
        }
    });

    $('.inline-modal-trigger, .nav-modal-trigger').on('click', function(e){
        e.preventDefault();

        var prefix = 'inline';

        if ($(this).is('.nav-modal-trigger')) {
            prefix = 'nav';
        }

        var done = false;

        $(this).prevAll().each(function() {
            if (done || !$(this).is('.' + prefix + '-modal')) {
                return;
            }

            $(this).addClass(prefix + '-modal--open');
            $(this).css({width: '', left: '', right: ''});

            var that = this;

            var leftHidden = function () {
                return $(that).offset().left < 15;
            };

            var rightHidden = function () {
                return $(that).offset().left + $(that).width() > $(window).width() - 15;
            };

            if (leftHidden() && !rightHidden()) {
                var right = 0;
                for (var right = 0; right < 1000 && leftHidden() && !rightHidden(); right++) {
                    $(this).css('right', -right + 'px');
                }
            } else if (rightHidden() && !leftHidden()) {
                var left = 0;
                for (var left = 0; left < 1000 && rightHidden() && !leftHidden(); left++) {
                    $(this).css({width: $(this).width() + 'px', left: -left + 'px'});
                }
            }

            $('<div class="modal-breakout">').insertAfter(this);
            done = true;
        });
    });



    var repeaterChanged = function(){
        if ($('.repeater-select').val()) {
            var r = new RegExp($('.repeater-select').val());

            $('.repeater-modal [data-repeaters]').each(function(){
                $(this).toggle($(this).data('repeaters').match(r) !== null);
            });
        } else {
            $('.repeater-modal [data-repeaters]').hide();
        }
    };

    $('.repeater-select').on('change', repeaterChanged);
    repeaterChanged();

    $('.easy-table tr .selectall').on('click', function(e){
        var $table = $(this).closest('table');
        var $tbody = $(this).closest('tbody');
        var $block;

        if ($tbody.length) {
            $block = $tbody;
        } else {
            $block = $table;
        }

        var $boxes = $block.find('tr[data-id] .select-column input[type="checkbox"]');
        var checked = $boxes.filter(':checked').length > 0;
        $boxes.prop('checked', !checked);
        $boxes.each(function(){
            $(this).closest('tr[data-id]').toggleClass('selected', $(this).is(':checked'));
        });
    });

    $('.toggle-selecting').on('click', function(){
        let $table = $(this).closest('.easy-table');
        let selecting = $table.hasClass('selecting');
        selecting = !selecting;

        $table.toggleClass('selecting', selecting);

        if (!selecting) {
            $table.find('.select-column input[type="checkbox"]').prop('checked', false).each(function(){
                $(this).closest('tr[data-id]').removeClass('selected');
            });
        }
    });

    $('.select-column input').on('click', function(event) {
        event.stopPropagation();
    });

    $('.select-column input').on('change', function() {
        $(this).closest('tr[data-id]').toggleClass('selected', $(this).is(':checked'));
    });

    $('.file-field-controls__delete, .file-field-controls__generate, .file-field-controls__cancel, .file-field-controls__change').click(function(){
        var $controls = $(this).closest('.file-field-controls');
        var $input = $controls.find('.file-field-controls__input');
        var $actions = $controls.find('.file-field-controls__actions');
        var $generate = $controls.find('.file-field-controls__generate');
        var $willdelete = $controls.find('.file-field-controls__willdelete');
        var $willgenerate = $controls.find('.file-field-controls__willgenerate');
        var name = $controls.find('input[type="file"]').attr('name');
        var hasValue = $controls.data('hasvalue');

        if ($(this).hasClass('file-field-controls__delete')) {
            $willdelete.append($('<input type="hidden" name="' + name + '_delete" value="1">'));
            $input.hide();
            $willdelete.show();
            $actions.hide();
            $generate.show();
        } else if ($(this).hasClass('file-field-controls__change')) {
            $input.show();
            $willdelete.hide();
            $actions.hide();
            $generate.hide();
        } else if ($(this).hasClass('file-field-controls__generate')) {
            $willgenerate.append($('<input type="hidden" name="' + name + '_generate" value="1">'));
            $input.hide();
            $willgenerate.show();
            $actions.hide();
        } else if ($(this).hasClass('file-field-controls__cancel')) {
            $controls.find('input[type="hidden"]').remove();
            $willdelete.hide();
            $willgenerate.hide();
            $generate.show();

            if (hasValue) {
                $actions.show();
                $input.hide();
            } else {
                $actions.hide();
                $input.show();
            }
        }
    });

    $('.adhoc-toggle').on('click', function(){
        var adhocvalue = prompt("New value");

        if (adhocvalue) {
            var $select = $(this).prev();
            var $option = $('<option>' + adhocvalue + '</option>');

            $option.insertAfter($select.children().first());
            $select.val(adhocvalue);
            $select.change();
        }
    });
})();

(function() {
    // Graphs

    var $canvas = $('#bg');

    var onResize = function() {
        var width = Math.floor(window.innerWidth - 200 - (window.innerWidth >= 800 && 238 || 0));
        var height = Math.floor(window.innerHeight - 200);

        $('#bg-container').css({width: width + 'px', height: height + 'px'});

        $canvas.attr('width', width).attr('height', height);

        var c = document.getElementById("bg");
        var ctx = c.getContext("2d");
        width = $canvas.width() - 2;
        height = $canvas.height() - 2;

        ctx.lineWidth = 1;
        ctx.lineJoin = 'miter';
        ctx.strokeStyle = "#efefef";

        ctx.beginPath();

        if (typeof divs != 'undefined') {
            for (var i = 0; i < divs.length; i++) {
                ctx.moveTo(width * divs[i] + 1, 0);
                ctx.lineTo(width * divs[i] + 1, height + 1);
                ctx.stroke();
            }
        }

        ctx.strokeStyle = "#bbb";
        ctx.lineWidth = 2;

        ctx.beginPath();
        var xAxis = height * (1 - xAxisProp);

        ctx.moveTo(0, xAxis + 2);
        ctx.lineTo(width + 2, xAxis + 2);

        ctx.moveTo(0, 0);
        ctx.lineTo(width + 2, 0);

        ctx.moveTo(0, height + 2);
        ctx.lineTo(width + 2, height + 2);

        ctx.moveTo(0, 0);
        ctx.lineTo(0, height + 2);

        ctx.moveTo(width + 2, 0);
        ctx.lineTo(width + 2, height + 2);

        ctx.stroke();

        if (today) {
            ctx.lineWidth = 2;
            ctx.strokeStyle = '#' + highlight;
            ctx.beginPath();
            ctx.moveTo(today * width + 1, 1);
            ctx.lineTo(today * width + 1, height + 1);
            ctx.stroke();
        }

        ctx.lineWidth = 1;
        ctx.lineJoin = 'round';

        var seriesNum = 0;

        for (const seriesName in graphSeries) {
            var points = graphSeries[seriesName].points;
            var color = graphSeries[seriesName].color;

            ctx.strokeStyle = '#' + color;
            ctx.beginPath();
            ctx.moveTo(width * points[0][0] + 1, height * (1 - points[0][1]) + 1);

            for (var i = 1; i < points.length; i++) {
                ctx.lineTo(Math.round(width * points[i][0] + 1), Math.round(height * (1 - points[i][1]) + 1));
                ctx.stroke();
            }

            seriesNum++;
        }
    };

    if ($canvas.length && window.graphSeries) {
        $(window).on('resize', onResize);
        onResize();
    }
})();