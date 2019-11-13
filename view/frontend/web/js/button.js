define(['jquery', 'underscore', 'uiRegistry', 'Magento_Ui/js/form/components/button',
        'Magento_Ui/js/form/form'],
       function ($, _, uiRegistry, button) {
           var mydata = uiRegistry.get("funnyorder_form.funnyorder_form");
           return button.extend({
                                    action: function () {
                                        mydata.save(true,{});
                                    }
                                });
       });
