define(['jquery', 'Magento_Ui/js/modal/modal'], function ($, modal) {
    return function (config, node) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'Event order',
            buttons: []
        };
        modal(options, $('#vaimo_mytest_event_popup'));
        node.onclick = function () {
            $('#vaimo_mytest_event_popup').modal('openModal');
        };
    }
})