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

    window.blends_api = {
        login: function(username, password) {
            $.ajax('/api/auth/login', {
                method: 'post',
                contentType: false,
                processData: false,
                data: JSON.stringify({username: username, password: password}),
                success: function(data) {
                    if (typeof data.token != 'undefined') {
                        setCookie('token', data.token);
                        window.location.reload();
                    } else {
                        alert(data.error || 'Unknown error');
                    }
                },
                error: function(data){
                    alert(data.responseJSON && data.responseJSON.error || 'Unknown error');
                }
            });
        },
        updateBlend: function(blend, query, data) {
            $.ajax('/api/blend/' + blend + '/update?' + query, {
                method: 'post',
                contentType: false,
                processData: false,
                data: JSON.stringify(data),
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(data){
                    alert(data.responseJSON && data.responseJSON.error || 'Unknown error');
                }
            });
        },
        linetypeAdd: function(linetype, repeater, range_from, range_to, data){
            $.ajax('/api/' + linetype + '/add?repeater=' + repeater + '&from=' + range_from + '&to=' + range_to, {
                method: 'post',
                contentType: false,
                processData: false,
                data: JSON.stringify(data),
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(data){
                    alert(data.responseJSON && data.responseJSON.error || 'Unknown error');
                }
            });
        },
        blendDelete: function(blend, query){
            $.ajax('/api/blend/' + blend + '?' + query, {
                method: 'delete',
                contentType: false,
                processData: false,
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(data){
                    alert(data.responseJSON.error);
                }
            });
        },
        blendPrint: function(blend, query) {
            $.ajax('/api/blend/' + blend + '/print?' + query, {
                method: 'post',
                contentType: false,
                processData: false,
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                error: function(data){
                    alert(data.responseJSON.error);
                }
            });
        },
        linePrint: function(linetype, id) {
            $.ajax('/api/' + linetype + '/print?id=' + id, {
                method: 'post',
                contentType: false,
                processData: false,
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                success: function(data) {
                    $('#output').html(data.messages.join(', '));
                }
            });
        },
        lineDelete: function(linetype, id) {
            $.ajax('/api/' + linetype + '?id=' + id, {
                method: 'delete',
                contentType: false,
                processData: false,
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                success: function() {
                    window.location.reload();
                },
                error: function(data){
                    alert(data.responseJSON.error);
                }
            });
        },
        lineUnlink: function(linetype, id, parent) {
            $.ajax('/api/' + linetype + '/' + id + '/unlink/' + parent, {
                method: 'post',
                contentType: false,
                processData: false,
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                success: function() {
                    window.location.reload();
                },
                error: function(data){
                    alert(data.responseJSON.error);
                }
            });
        },
        lineSave: function(linetype, line, back) {
            $.ajax('/api/' + linetype, {
                method: 'post',
                contentType: false,
                processData: false,
                beforeSend: function(request) {
                    request.setRequestHeader("X-Auth", getCookie('token'));
                },
                data: JSON.stringify([line]),
                success: function(data) {
                    window.location.href = back;
                },
                error: function(data){
                    alert(data.responseJSON.error);
                }
            });
        },
        logout: function() {
            $.ajax('/api/auth/logout', {
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
        }
    };

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
})();
