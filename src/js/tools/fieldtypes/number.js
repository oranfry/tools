(function() {
    let spec = {
        create: function(spec) {
            let $wrapper = $('<span style="white-space: nowrap">');

            let step = 1 / Math.pow(10, spec.dp ?? 0);

            let $field = $('<input class="field value" type="number" autocomplete="off" style="width: 8em">')
                .attr('name', spec.name)
                .attr('step', step);

            if (spec.readonly) {
                $field.prop('disabled', true);
            }

            let $negate = $('<span class="button noedit-invisible">±</span>').on('click', function (e) {
                e.preventDefault();
                $field.val(0 - $field.val());
            });

            $wrapper.append($field, $negate);

            return $wrapper;
        },
        set: function ($field, value) {
            $field.val(value);
        },
        get: function ($field) {
            return $field.val();
        }
    };

    window.fieldtypes.types.number = spec;
    window.fieldtypes.types.float = spec;
})();