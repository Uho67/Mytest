define(['jquery', 'underscore', 'uiRegistry', 'Magento_Ui/js/form/components/button',
        'Magento_Ui/js/form/form'],
       function ($, _, uiRegistry, button) {

           var mydataSource = uiRegistry.get("funnyorderfront_form.funnyorderfront_form_data_source");
           var mydata = uiRegistry.get("funnyorderfront_form.funnyorderfront_form");
           return button.extend({
                                    action: function () {
                                        $.ajax({
                                                   type: 'POST',
                                                   url: '/newmagento/rest/all/V1/fannyorder',
                                                   data:  {
                                                       order : {
                                                           phone: '99999999'
                                                       }
                                                   },
                                                   success: function (response) {
                                                       console.log(response);
                                                   }
                                               })
                                    }
                                });
       });
