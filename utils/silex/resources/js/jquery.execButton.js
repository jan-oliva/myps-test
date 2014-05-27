$(function () {
    $.execButton = {
        defaultOptions: {
            doneCallback: function () {
            },
            alwaysCallback: function () {
            },
            data: null,
            template: '<div class="alert alert-block alert-info fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4>%title%</h4><p>%output%</p><a class="btn btn-md btn-info" data-dismiss="alert">zavřít</a></div>',
            notificationsSelector: '#notifications',
            loadingText: 'pracuji ...'
        }
    };

    $.fn.extend({
        execButton: function (options) {
            var $els = $(this), preventCallback, execCallback;
            options = $.extend({}, $.execButton.defaultOptions, options);

            preventCallback = function (event) {
                event.preventDefault();
            };
            execCallback = function () {
                var $el = $(this), elText = $el.text();

                $el.off('click', execCallback);
                $el.toggleClass('disabled');
                $el.text(options.loadingText);

                $.ajax({
                    method: 'get',
                    dataType: 'json',
                    url: $el.attr('href'),
                    data: options.data
                }).done(function (data) {
                    var message = options.template
                        .replace(/%output%/g, data.output)
                        .replace(/%title%/g, 'Příkaz proběhl s exit kódem [' + data.exitcode + ']');
                    $(options.notificationsSelector).append(message);
                    $(document).scrollTop(0);

                    options.doneCallback(message);
                }).always(function () {
                    $el.text(elText);
                    $el.toggleClass('disabled');
                    $el.on('click', execCallback);

                    options.alwaysCallback();
                });
            };

            return $els.each(function () {
                $(this)
                    .on('click', preventCallback)
                    .on('click', execCallback);
            });
        }
    });
});