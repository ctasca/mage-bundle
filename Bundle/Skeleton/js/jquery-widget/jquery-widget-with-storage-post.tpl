define([
    'jquery',
    'mage/storage'
], function ($, storage) {
    'use strict';

    $.widget('{{widget_name}}', {
        /**
         * Default options
         */
        options: {
            storagePostUrl: null,
        },

        /**
         * Creates and initializes widget
         * @private
         */
        _create: function() {

        },

        /**
         * @private
         */
        _storagePostRequest: function () {
            const self = this;
            let data = { };
            storage.post(self.options.storagePostUrl, JSON.stringify(data), true)
                .done(response => {

                })
                .complete(() => {

                })
                .fail(() => {

                });
        }
    });

    return $.{{widget_name}};
})
