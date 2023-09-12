define([
    'jquery',
    'Magento_Ui/js/form/components/insert-form',
], function($, InsertForm) {
    return InsertForm.extend({
        defaults: {
            imports: {
                entryId: "${ $.provider }:data.entry_id"
            },
            modules: {
                queueEntriesListing: '${ $.columnsProvider }'
            }
        },

        currentEntryId: null,

        confirm: function (action) {
            let params = [];
            const currentEntryId = $('#current_entry_id').val();
            if (currentEntryId) {
                this.currentEntryId = currentEntryId;
            }

            if (!this.currentEntryId) {
                this.currentEntryId = this.entryId;
            }

            $.ajax({
                url: this.confirmUrl,
                method: 'post',
                data: {
                    entry_id: this.currentEntryId
                },
                success: function (res) {
                    console.log(res)
                    this.queueEntriesListing().reload();
                }.bind(this),
                error: function (err) {
                    console.error(err)
                }
            });
        }
    });
});