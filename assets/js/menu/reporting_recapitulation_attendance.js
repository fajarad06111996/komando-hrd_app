$(document).ready(async function(){
	    
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    }).datepicker("setDate",'now');

    waiting();

    await listLoadType();

    Swal.close();
    
    $('#frmSearch').submit(function(event){ 
        event.preventDefault();
        if (document.getElementById('from_date').value === '') { warningMessage('Mohon isi parameter tanggal awal dulu!'); }
        else if (document.getElementById('to_date').value === '') { warningMessage('Mohon isi parameter tanggal akhir dulu!'); }
        else if (document.getElementById('load_type').value === '') { warningMessage('Mohon pilih parameter jenis muatan dulu!'); }
        else {
            waiting();
            $.ajax({
                url: getUrl('ReportingRecapitulationAttendance/listData'),
                type: "post",
                data: {
                    "from_date" : document.getElementById('from_date').value,
                    "to_date" : document.getElementById('to_date').value,
                    "load_type" : document.getElementById('load_type').value,
                    "load_type_name" : document.getElementById('load_type').options[document.getElementById('load_type').selectedIndex].text
                },
                success: function (response) {
                    var json = $.parseJSON(response);
                    if(json.status === false) {
                        warningMessage(json.message);
                    }
                    else {
                        document.getElementById("listData").innerHTML = json.output_html;
                        Swal.close();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    errorConnectionMessage();
                }
            });
        }
    });

    $("#btnExport").click(function(e) {

        let file = new Blob([$('#listData').html()], {type:"application/vnd.ms-excel"});
        let url = URL.createObjectURL(file);
        let a = $("<a />", {
            href: url,
            download: "laporan_rekapitulasi_absensi_kendaraan.xls"}).appendTo("body").get(0).click();
        e.preventDefault();
    });
    
});

async function listLoadType() {
    try {
        await $.ajax({
            url: getUrl('GlobalData/listLoadType'),
            type: "post",
            success: function (response) {
                var json = $.parseJSON(response);
                if(json.status === true) {
                    var i;
                    var data = json.data;
                    var select = document.getElementById('load_type');
                    for (i = 0; i < data.length; ++i) {
                        var opt = document.createElement('option');
                        opt.value = data[i].status;
                        opt.innerHTML = data[i].status_name;
                        select.appendChild(opt);
                    }
                    $('.selectpicker').selectpicker('refresh');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                errorConnectionMessage();
            }
        });
        
        
    } catch (error) {
      errorConnectionMessage();
    }
}

function goRefresh() {

    waiting();
    window.location.replace(getUrl('ReportingRecapitulationAttendance')); 

}

function printReport() {
    // var paramReport = '<center><h4>REPORT BALANCE SHEET</h4></center><br>' +
    //                     '<label>To Date : ' + document.getElementById('to_date').value + '</label><br>';
    var paramReport = '';
    var detailReport = document.getElementById('listData').innerHTML;

    document.getElementById('printArea').innerHTML = paramReport + detailReport;

    window.print();

    document.getElementById('printArea').innerHTML = '';
}