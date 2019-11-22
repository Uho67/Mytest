define(['jquery', 'Magento_Ui/js/modal/modal'], function ($, modal) {
    return function (config, node) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'Event order',
            buttons: []
        };
        modal(options, $('#eventPopup'));
        node.onclick = function () {
            $('#eventPopup').modal('openModal');
        };
    }
})