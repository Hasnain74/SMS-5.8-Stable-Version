let DataTableFilter = (function () {

    let init = function (url, params)
    {
        LaravelDataTables.dataTableBuilder.ajax.url({
            url: url,
            type: 'GET',
            data: function (d) {
                for (const property in params)
                {
                    let value = $(params[property]).val();

                    if( value.length > 0)
                        d[property] = value;
                }
            }
        });
    };

    return {
        init: init
    }
})();
