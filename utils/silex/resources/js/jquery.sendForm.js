$(function () {
    $.fn.extend({
        sendForm: function () {
            var $els = $(this), preventCallback, execCallback;

            preventCallback = function (event) {
                event.preventDefault();
            };
            execCallback = function () {
                var $el = $(this), elData = $el.data('send-form'), $form = $('<form method="post" action="' + $el.attr('href') + '">'), key;

                for (key in elData) {
                    $form.append('<input type="text" name="' + key + '" value="' + elData[key] + '" />');
                }
                $form.appendTo(document.body).submit().remove();
            };

            return $els.each(function () {
                $(this)
                    .on('click', preventCallback)
                    .on('click', execCallback);
            });
        }
    });
});