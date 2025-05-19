$(document).ready(async function(){

    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    }).datepicker("setDate",'now');

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $('#frmEntry').submit(async function(event){ 
        event.preventDefault();
        waiting(); 
        if (document.getElementById('from_date').value === '') { warningMessage('Mohon isi tanggal mulai izin dulu'); }
        else if (document.getElementById('to_date').value === '') { warningMessage('Mohon isi tanggal akhir izin dulu'); }
        else if (document.getElementById('permission_type').value === '') { warningMessage('Mohon pilih alasan izin dulu'); }
        else {

            var destinationFile;
            var fileName;
            var uploadAttachment;
            const d = new Date();
            let time = d.getTime();

            if (document.getElementById('customFile').value) {
                if(document.getElementById("filename_attachment").value === '' || document.getElementById("filename_attachment").value === null) {
                    // Assuming you're interested in the first file selected
                    const file = document.getElementById('customFile').files[0];

                    // Get the file name
                    const fileNameAttachment = file.name;

                    // Get the file extension
                    const fileExtension = fileNameAttachment.slice((fileNameAttachment.lastIndexOf('.') - 1 >>> 0) + 2);
                    console.log(fileExtension);
                    fileName = 'PERMISSION_EMPLOYEE_' + time + '.' + fileExtension;
                }
                else {
                    fileName = document.getElementById("filename_attachment").value;
                }

                destinationFile = getFolderCompany() + '/attachment_permission/' + fileName;
                
                try {
                    uploadAttachment = await uploadFirebase(document.getElementById('customFile').files[0], destinationFile);
                    if(uploadAttachment.status === true) {
                        document.getElementById('url_attachment').value = uploadAttachment.data;
                        document.getElementById('filename_attachment').value = fileName;
                        await submitEntry(this);
                    }
                    else {
                        await deleteFromFirebase(destinationFile);
                        await warningMessage('Unggah lampiran dokumen gagal!');
                    }
                } catch (error) {
                    await warningMessage('Unggah lampiran dokumen gagal!');
                }   
            }
            else {
                await submitEntry(this);
            }
        }
    });
	    
    await waiting();

    await getMonthNow();
    await getDataPermission();

    Swal.close();

});

async function submitEntry(formThis) {
    await $.ajax({
        url: getUrl('PermissionRequest/submitEntry'),
        type: "post",
        data:new FormData(formThis),
        processData:false,
        contentType:false,
        cache:false,
        success: async function (response) {
            var json = $.parseJSON(response);
            if(json.status === false) {
                await warningMessage(json.message);
            }
            else {
                await successMessageCallBack('Yeayy', json.message, getUrl('PermissionRequest'));
            }
        },
        error: async function(jqXHR, textStatus, errorThrown) {
            await errorConnectionMessage();
        }
    });
}

async function getMonthNow() {
    try {
        // Create a new Date object representing the current date
        var currentDate = new Date();

        // Get the month (zero-based index) from the Date object
        var currentMonth = currentDate.getMonth() + 1; // Adding 1 to adjust zero-based index

        // Format the month to have two digits
        var formattedMonth = currentMonth < 10 ? '0' + currentMonth : currentMonth;

        // Display the formatted month
        // console.log("Formatted month: " + formattedMonth);
        document.getElementById('month_period').value = formattedMonth;
        $('.selectpicker').selectpicker('refresh');
        
    } catch (error) {
        errorConnectionMessage();
    }
}

async function getSelectDataPermission(month_period) {
    try {
        document.getElementById('month_period').value = month_period;
        $('.selectpicker').selectpicker('refresh');
        await waiting();
        await getDataPermission();
        Swal.close();
        
    } catch (error) {
        errorConnectionMessage();
    }
}

async function getDataPermission() {
    try {
        await $.ajax({
            url: getUrl('PermissionRequest/listDataPermission'),
            type: "post",
            data: {
                "month_period" : document.getElementById('month_period').value,
                "year_period" : document.getElementById('year_period').value,
            },
            success: function (response) {
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('div_permission').innerHTML = json.data;
                }
                else { 
                    document.getElementById('div_permission').innerHTML = '<div class="w-100 text-center"><label class="text-red" style="font-size: 13px; font-family: `Audiowide`, sans-serif;">' + json.message + '</label></div>'; 
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

async function searchListData() {
    var filter = document.getElementById("txt_search_list").value.toLowerCase();
    var items = document.querySelectorAll('#div_permission .listdata');
    
    items.forEach(function(item) {
        var text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

async function getListData(idValue, idName, lblList) {
    waiting();

    document.getElementById('lbl_list_label').innerHTML = lblList;
    document.getElementById("txt_search_list").value = "";
    document.getElementById('listDataSelect').innerHTML = "<img src='" + getUrl('assets/images/ICON/loading_blue.gif') + "' width='100%'>";
    if(lblList == 'Alasan Tidak Masuk Kerja') {
        $.ajax({
            url: getUrl('PermissionRequest/listPermissionType'),
            type: "post",
            success: function (response) {
                var json = $.parseJSON(response);
                if(json.status === true) {
                    var i;
                    var data = json.data;
                    var listData = '<button type="button" class="w-100 text-blue text-left border border-primary p-2 mb-1" onclick="getSelectData(``, ``, `' + idValue + '`, `' + idName + '`)"><i class="fas fa-hand-point-right"></i> -- Empty --</button>';
                    for (i = 0; i < data.length; ++i) {
                        if(data[i].vendor_idx == document.getElementById(idValue).value) {
                            listData = listData + '<button type="button" class="w-100 bg-blue text-white text-left border border-primary p-2 mb-1" data-dismiss="modal"><i class="fas fa-hand-point-right"></i> ' + data[i].status_name + '</button>';
                        }
                        else {
                            listData = listData + '<button type="button" class="w-100 text-blue text-left border border-primary p-2 mb-1" onclick="getSelectData(`' + data[i].status + '`, `' + data[i].status_name + '`, `' + idValue + '`, `' + idName + '`)"><i class="fas fa-hand-point-right"></i> ' + data[i].status_name + '</button>';
                        }
                    }
                    document.getElementById('listDataSelect').innerHTML = listData;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                errorConnectionMessage();
            }
        });
    }
    
    $('#modalListData').modal({backdrop: 'static', keyboard: false});
    $('#modalListData').modal('show');
    Swal.close();
}

async function searchListDataSelect() {
    var input, filter, button, a, i, txtValue;
    input = document.getElementById("txt_search_list_select");
    filter = input.value.toUpperCase();
    div = document.getElementById("listDataSelect");
    button = div.getElementsByTagName("button");
    for (i = 0; i < button.length; i++) {
        a = button[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            button[i].style.display = "";
        } else {
            button[i].style.display = "none";
        }
    }
}

async function getSelectData(value, label, idValue, idLabel) {
    document.getElementById(idValue).value = value;
    document.getElementById(idLabel).value = label;

    if(idValue == 'permission_type') {
        document.getElementById('remarks').value = label;
    }

    $('#modalListData').modal('hide');
}

async function getAddPermission() {
    await waiting();
    document.getElementById('entry_type').value = '1';
    await $('#modalEntryPermission').modal({backdrop: 'static', keyboard: true});
    await $('#modalEntryPermission').modal('show');
    await Swal.close();
}

async function getEditPermission(idx) {
    await waiting();
    document.getElementById('entry_type').value = '2';
    document.getElementById('idx').value = idx;
    try {
        await $.ajax({
            url: getUrl('PermissionRequest/getDataEmployeePermission'),
            type: "post",
            data: {
                "idx" : idx
            },
            success: async function (response) {
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('permission_no').value = json.data.permission_no;
                    document.getElementById('from_date').value = json.data.from_date;
                    document.getElementById('to_date').value = json.data.to_date;
                    document.getElementById('permission_type').value = json.data.permission_type;
                    document.getElementById('permission_type_name').value = json.data.permission_type_name;
                    document.getElementById('remarks').value = json.data.remarks;
                    document.getElementById('url_attachment').value = json.data.url_attachment;
                    document.getElementById('filename_attachment').value = json.data.filename_attachment;
                    document.getElementById('lbl_attachment').innerHTML = json.data.filename_attachment;
                }
                else { await warningMessage(json.message); }
            },
            error: async function(jqXHR, textStatus, errorThrown) {
                await errorConnectionMessage();
            }
        });
        
        
    } catch (error) {
        errorConnectionMessage();
    }
    await $('#modalEntryPermission').modal({backdrop: 'static', keyboard: true});
    await $('#modalEntryPermission').modal('show');
    await Swal.close();
}

async function getAttachment(urlAttachment) {
    await waiting();
    window.open(urlAttachment, '_blank');
    Swal.close();
}