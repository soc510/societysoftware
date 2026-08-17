/*DataTable Init*/

"use strict";

$(document).ready(function() {
	"use strict";

    $('#datable_1').DataTable({"pageLength": 100});
    $('#datable_4').DataTable();
    $('#datable_2').DataTable({ "lengthChange": false});
} );