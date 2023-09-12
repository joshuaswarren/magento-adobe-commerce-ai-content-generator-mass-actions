define([
    'jquery',
    'Magento_Ui/js/form/components/button',
    'uiRegistry',
    'wysiwygAdapter',
    'Magento_Ui/js/modal/alert',
    'loader',
    'mage/translate'
], function ($, Button, uiRegistry, wysiwygAdapter, alert) {
    'use strict';

    const loaderStart = function () {
        $(document.body).trigger('processStart')
    };

    const loaderStop = function () {
        $(document.body).trigger('processStop')
    };

    const displayAlert = function (title, msg, buttons) {
        alert({
            title: title,
            content: msg,
            actions: {
                always: function(){}
            },
            buttons: buttons
        })
    };

    return Button.extend({

        initialize: function () {
            this._super();
            this.disabled(true);
            const interval = setInterval(function() {
                if (!this.getSourceField()) {
                    return;
                }
                this.getSourceField().value.subscribe(function (val) {
                    this.disabled(!(val && val.length));
                    this.visible(val && val.length);
                }.bind(this));
                clearInterval(interval);
            }.bind(this), 100);
        },

        initObservable: function () {
            this._super()
                .observe([
                    'productId'
                ]);

            return this;
        },

        action: function () {
            try {
                this.run();
            } catch (e) {
                console.error(e);
            }
        },

        getSourceField: function () {
            return uiRegistry.get(this.sourceField);
        },

        getGeneratedContent: function () {
            const source = this.getSourceField();
            if (source.formElement === 'wysiwyg') {
                const editor = wysiwygAdapter.get(source.wysiwygId);

                return editor.getContent();
            }

            return source.value();
        },

        setApplyBtnDisableSate: function (state) {
            const applyBtn = uiRegistry.get(this.applyBtn);
            if (applyBtn) {
                applyBtn.disabled(state)
            }
        },

        run: function () {
            const attribute = this.attribute;
            const content = this.getGeneratedContent();
            loaderStart();
            $.ajax({
                url: this.actionUrl,
                data: {
                    product_id: this.productId(),
                    attribute: attribute,
                    content: content
                }}).done(function () {
                    displayAlert($.mage.__('Success'), $.mage.__('Generated content has been saved in product successfully!'));
                }).fail(function (jqXHR) {
                    const errorMsg = jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : 'Unknown error occurred!';
                    displayAlert($.mage.__('Error'), errorMsg);
                }).always(function () {
                    loaderStop();
                })
        }
    })
})