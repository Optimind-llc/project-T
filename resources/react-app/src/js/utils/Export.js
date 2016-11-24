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
            filename = 'reference.csv';
        }
        var uri = createDataUriFromString(tableToCsvString(table));
        downloadDataUri(uri, filename);
    };
})();



export function handleDownload(table) {
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

    var content = tableToCsvString(table);
    var file_name = 'test.csv';

    // var blob = new Blob([ content ], { "type" : "text/plain" });
    var blob = new Blob([content] , {
        type: "text/csv;charset=utf-8;"
    });

    if (window.navigator.msSaveOrOpenBlob) {
        //IEの場合
        navigator.msSaveBlob(blob, file_name);
    } else {
        //IE以外(Chrome, Firefox)
        var downloadLink = document.createElement('a');
        downloadLink.setAttribute('href', window.URL.createObjectURL(blob));
        downloadLink.setAttribute('download', file_name);
        downloadLink.setAttribute('target', '_blank');

        document.getElementsByTagName('body')[0].appendChild(downloadLink);
        // $('body').append(downloadLink);
        downloadLink.click();
        downloadLink.remove();
    }
}
