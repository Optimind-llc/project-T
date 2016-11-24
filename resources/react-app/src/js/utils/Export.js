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



    function handleDownload() {
        var content = 'あいうえお';
        var blob = new Blob([ content ], { "type" : "text/plain" });

        if (window.navigator.msSaveBlob) { 
            window.navigator.msSaveBlob(blob, "test.txt"); 

            // msSaveOrOpenBlobの場合はファイルを保存せずに開ける
            window.navigator.msSaveOrOpenBlob(blob, "test.txt"); 
        } else {
            document.getElementById("download").href = window.URL.createObjectURL(blob);
        }
    }



})();