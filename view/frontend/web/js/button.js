define([
           'jquery',
           'mage/storage',
           'uiRegistry',
           'Magento_Ui/js/form/components/button',
           'Magento_Ui/js/form/form'],
       function ($, storage, uiRegistry, button) {
           var mydataSource = uiRegistry.get("funnyorderfront_form.funnyorderfront_form_data_source");
           var mydata = uiRegistry.get("funnyorderfront_form.funnyorderfront_form");
           return button.extend({

                                    action: function () {
                                        mydata.validate();
                                        $('#vaimo_mytest_event_popup').trigger('processStart');
                                        var ajaxVal = mydataSource.data;
                                        delete ajaxVal.hello;
                                        var serviceUrl = 'rest/all/V1/fannyorder';
                                        var payload = {
                                            order: ajaxVal,
                                        };
                                        storage.post(
                                            serviceUrl,
                                            JSON.stringify(payload)
                                        ).done(function (response) {
                                            $('input[name ="hello"]').css('color', 'green');
                                            $('#vaimo_mytest_event_popup').trigger('processStop');
                                            $('#vaimo_mytest_event_popup input').attr('value', '');
                                            $('input[name ="hello"]').val('Your order was saved № '+ response);
                                            setTimeout(function () {
                                                $('#vaimo_mytest_event_popup').modal('closeModal');
                                                $('input[name ="hello"]').val('Chose time please');
                                            }
                                            ,3000);
                                        }).fail(function (response) {
                                            $('input[name ="hello"]').css('color', 'red');
                                            $('#vaimo_mytest_event_popup').trigger('processStop');
                                            $('input[name ="hello"]').val(JSON.parse(response.responseText).parameters[0]);
                                        });
                                    }
                                });
       });