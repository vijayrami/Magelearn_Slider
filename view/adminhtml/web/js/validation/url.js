require([
    'jquery',
    'Magento_Ui/js/lib/validation/validator',
    'jquery-ui-modules/widget',
    'mage/translate'
], function ($, validator) {
    'use strict';

    validator.addRule(
        'magelearn-validate-slider-url',
        function (v) {
            if ($.mage.isEmptyNoTrim(v)) {
                return true;
            }

            v = (v || '').replace(/^\s+/, '').replace(/\s+$/, '');

            return (/^(http|https|ftp):\/\/(([A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))(\.[A-Z0-9]([A-Z0-9_-]*[A-Z0-9]|))*)(:(\d+))?(\/[A-Z0-9~](([A-Z0-9_~-]|\.)*[A-Z0-9~]|))*\/?(.*)?$/i).test(v) || //eslint-disable-line max-len
                (/^\/(\/[A-Z0-9~](([A-Z0-9_~-]|\.)*[A-Z0-9~]|))*\/?(.*)?$/i).test(v);
        },
        $.mage.__('Please enter a valid absolute or relative URL. For example http://www.example.com or /page.')
    );
});
