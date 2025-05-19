$(document).ready(async function(){
	    
    await waiting();

    await getMonthNow();
    await getDataAttendance();

    Swal.close();

});

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
        document.getElementById('month_attendance').value = formattedMonth;
        $('.selectpicker').selectpicker('refresh');
        
    } catch (error) {
        errorConnectionMessage();
    }
}

async function getSelectDataAttendance(month_attendance) {
    try {
        document.getElementById('month_attendance').value = month_attendance;
        $('.selectpicker').selectpicker('refresh');
        await waiting();
        await getDataAttendance();
        Swal.close();
        
    } catch (error) {
        errorConnectionMessage();
    }
}

async function getDataAttendance() {
    try {
        await $.ajax({
            url: getUrl('HistoryAttendance/getDataAttendance'),
            type: "post",
            data: {
                "month_attendance" : document.getElementById('month_attendance').value,
                "year_attendance" : document.getElementById('year_attendance').value,
            },
            success: function (response) {
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('div_attendance').innerHTML = json.data;
                }
                else { 
                    document.getElementById('div_attendance').innerHTML = '<div class="w-100 text-center"><label class="text-red" style="font-size: 13px; font-family: `Audiowide`, sans-serif;">' + json.message + '</label></div>'; 
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
    var items = document.querySelectorAll('#div_attendance .listdata');
    
    items.forEach(function(item) {
        var text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}