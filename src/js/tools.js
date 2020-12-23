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

    window.getJsonFromUrl = function(url)
    {
        var question = url.indexOf("?");
        var hash = url.indexOf("#");

        if (hash==-1 && question==-1) {
            return {};
        }

        if (hash==-1) {
            hash = url.length;
        }

        var query = question == -1 || hash == question + 1 ? url.substring(hash) : url.substring(question + 1, hash);
        var result = {};

        query.split("&").forEach(function(part) {
            if (!part) {
                return;
            }

            part = part.split("+").join(" "); // replace every + with space, regexp-free version

            var eq = part.indexOf("=");
            var key = eq > -1 ? part.substr(0, eq) : part;
            var val = eq > -1 ? decodeURIComponent(part.substr(eq + 1)) : "";
            var from = key.indexOf("[");

            if (from==-1) {
                result[decodeURIComponent(key)] = val;
            } else {
                var to = key.indexOf("]", from);
                var index = decodeURIComponent(key.substring(from + 1,to));
                key = decodeURIComponent(key.substring(0, from));

                if (!result[key]) {
                    result[key] = [];
                }

                if (!index) {
                    result[key].push(val);
                } else {
                    result[key][index] = val;
                }
            }
        });

        return result;
    }

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

    var $instanceform = $('#instanceform');

    $('.instances-trigger').on('click', function(){
        $('.instances').toggleClass('open');
    });

    $('#loginform').on('submit', function(e){
        e.preventDefault();

        blends_api.login(
            $(this).find('[name="username"]').val(),
            $(this).find('[name="password"]').val()
        );
    });

    $('.trigger-logout').on('click', function(){
        if (!confirm('Logout ' + username + '?')) {
            return;
        }

        blends_api.logout();
    });

    $('#tokenform').on('submit', function(e){
        e.preventDefault();
        setCookie('token', $(this).find('[name="token"]').val());
        window.location.reload();
    });

    $('.open-custom-daterange:not(.current)').on('click', function(e){
        e.preventDefault();
        $('.custom-daterange, .standard-daterange').toggle();
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

    window.getQueryParams = function() {
        var existingData = getJsonFromUrl(location.href);
        var instanceData = Object.fromEntries(new FormData($instanceform[0]));
        var data = $.extend(existingData, instanceData);

        // remove nullish
        for (var prop in data) {
            if (Object.prototype.hasOwnProperty.call(data, prop)) {
                if (!data[prop]) {
                    delete data[prop];
                }
            }
        }

        return data;
    };

    window.changeInstance = function() {
        var base = location.href.split('?')[0];
        var data = getQueryParams();

        delete data._returnurl;

        var query = $.param(data);
        var link = base + (query && '?' || '') + query;

        window.location.href = link;
    };

    window.getFiltersQuery = function() {
        var queryParams = getQueryParams();

        delete queryParams._returnurl;
        delete queryParams.back;

        return $.param(queryParams);
    };

    window.getJsonFromUrl = function(url)
    {
        var question = url.indexOf("?");
        var hash = url.indexOf("#");

        if (hash==-1 && question==-1) {
            return {};
        }

        if (hash==-1) {
            hash = url.length;
        }

        var query = question == -1 || hash == question + 1 ? url.substring(hash) : url.substring(question + 1, hash);
        var result = {};

        query.split("&").forEach(function(part) {
            if (!part) {
                return;
            }

            part = part.split("+").join(" "); // replace every + with space, regexp-free version

            var eq = part.indexOf("=");
            var key = eq > -1 ? part.substr(0, eq) : part;
            var val = eq > -1 ? decodeURIComponent(part.substr(eq + 1)) : "";
            var from = key.indexOf("[");

            if (from==-1) {
                result[decodeURIComponent(key)] = val;
            } else {
                var to = key.indexOf("]", from);
                var index = decodeURIComponent(key.substring(from + 1,to));
                key = decodeURIComponent(key.substring(0, from));

                if (!result[key]) {
                    result[key] = [];
                }

                if (!index) {
                    result[key].push(val);
                } else {
                    result[key][index] = val;
                }
            }
        });

        return result;
    }

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
        var prefix = 'inline';

        if ($(this).is('.nav-modal-trigger')) {
            prefix = 'nav';
        }

        e.preventDefault();

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

    var manip = function(){
        var manips_string = $(this).data('manips');

        if (!manips_string) {
            return;
        }

        var manips = manips_string.split('&');

        for (var i = 0; i < manips.length; i++) {
            var cv_name = manips[i].split('=')[0];
            var cv_value = manips[i].split('=')[1];
            var matches = cv_value.match(/^base64:(.*)/);
            var value;

            if (matches !== null) {
                value = atob(matches[1]);
            } else {
                value = cv_value;
            }

            $('[name="' + cv_name + '"]').val(value);
        }
    }

    $('a.cv-manip').on('click', function(e) {
        e.preventDefault();
        manip.call(this);
        changeInstance();
    });

    $('input.cv-manip:not(.cv-surrogate), select.cv-manip:not(.cv-surrogate)').on('change', function(e) {
        manip.call(this);
        changeInstance();
    });

    $('.cv-surrogate').on('change', function(e){
        e.preventDefault();
        var for_cv = $(this).data('for');
        var value = $(this).is('[type="checkbox"]') && $(this).is(':checked') || $(this).val() || null;
        var $for = $instanceform.find('[name="' + for_cv + '"]');

        $for.val(value);

        if ($(this).is('.cv-manip')) {
            manip.call(this);
        }

        if (!$(this).is('.no-autosubmit')) {
            changeInstance();
        }
    });

    $('.cv').on('change', function(e){
        changeInstance();
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
        var $willdelete = $controls.find('.file-field-controls__willdelete');
        var $willgenerate = $controls.find('.file-field-controls__willgenerate');
        var name = $controls.find('input[type="file"]').attr('name');

        if ($(this).hasClass('file-field-controls__delete')) {
            $willdelete.append($('<input type="hidden" name="' + name + '_delete" value="1">'));
            $input.hide();
            $willdelete.show();
            $actions.hide();
        } else if ($(this).hasClass('file-field-controls__change')) {
            $input.show();
            $willdelete.hide();
            $actions.hide();
        } else if ($(this).hasClass('file-field-controls__generate')) {
            $willgenerate.append($('<input type="hidden" name="' + name + '_generate" value="1">'));
            $input.hide();
            $willgenerate.show();
            $actions.hide();
        } else if ($(this).hasClass('file-field-controls__cancel')) {
            $controls.find('input[type="hidden"]').remove();
            $willdelete.hide();
            $willgenerate.hide();

            if ($actions.length) {
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
