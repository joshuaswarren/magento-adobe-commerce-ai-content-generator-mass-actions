define([
    'jquery',
    'Magento_Ui/js/modal/modal-component',
    'underscore',
    'uiRegistry'
], function ($, Modal, _, registry) {
    'use strict';

    return Modal.extend({
        defaults: {
            imports: {
                entryId: "${ $.provider }:data.entry_id"
            }
        },

        currentEntryId: null,

        triggerAction: function (action) {
            let params = [];

            const currentEntryId = $('#current_entry_id').val();
            if (currentEntryId) {
                this.currentEntryId = currentEntryId;
            }

            if (!this.currentEntryId) {
                this.currentEntryId = this.entryId;
            }

            params.push({
                prev_entry_id: this.currentEntryId,
                mass_action: 1
            });

            action.params = params;

            this._super(action)
        }
    });
});