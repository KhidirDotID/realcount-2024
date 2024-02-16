(function ($, DataTable) {
    "use strict";

    DataTable.ext.buttons.create = {
        className: "buttons-create",

        text: function (dt) {
            return (
                '<i class="fas fa-plus"></i> ' +
                dt.i18n("buttons.create", "Create")
            );
        },

        action: function (e, dt, button, config) {
            window.location =
                window.location.href.replace(/\/+$/, "") + "/create";
        },
    };
})(jQuery, jQuery.fn.dataTable);
