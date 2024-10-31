(function() {
    window.fieldtypes.types.string = {
        create: function(spec) {
            let $field;

            if (typeof spec.options !== 'undefined') {
                $field = $('<select style="width: 80%">')
                    .attr('name', spec.name);

                if (spec.readonly) {
                    $field.prop('disabled', true);
                }

                if (spec.constrained || spec.options.length > 1) {
                    $field.append($('<option>'));
                }

                $.each(spec.options, function () {
                    let $option = $('<option>')
                        .attr('value', this)
                        .html(this);

                    $field.append($option);
                });

                if (!spec.constrained) {
                    $field.append($('<button type="button" class="adhoc-toggle">&hellip;</button>'));
                }
            } else {
                $field = $('<input class="field value" type="text" autocomplete="off">')
                    .attr('name', spec.name);

                if (spec.readonly) {
                    $field.prop('disabled', true);
                }
            }

            return $field;
        },
        set: function ($field, value) {
            if ($field.is('select') && !$field.find('option[value="' + value + '"]').length) {
                $field.prepend($('<option>').html(value).prop('value', value));
            }

            $field.val(value);
        },
        get: function ($field) {
            return $field.val();
        }
    };
})();