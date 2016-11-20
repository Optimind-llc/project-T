export const downloadCsv = (function() {
    const tableToCsvString = function(table) {
        var str = '\uFEFF';
        for (var i = 0, imax = table.length - 1; i <= imax; ++i) {
            var row = table[i];
            for (var j = 0, jmax = row.length - 1; j <= jmax; ++j) {
                str += '"' + row[j].replace('"', '""') + '"';
                if (j !== jmax) {
                    str += ',';
                }
            }
            str += '\n';
        }
        return str;
    };

    const createDataUriFromString = function(str) {
        return 'data:text/csv,' + encodeURIComponent(str);
    }

    const downloadDataUri = function(uri, filename) {
        var link = document.createElement('a');
        link.download = filename;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    return function(table, filename) {
        if (!filename) {
            filename = 'output.csv';
        }
        var uri = createDataUriFromString(tableToCsvString(table));
        downloadDataUri(uri, filename);
    };
})();