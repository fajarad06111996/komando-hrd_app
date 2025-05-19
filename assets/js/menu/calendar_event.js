$(document).ready(async function(){
	    
    await waiting();

    await getYearNow();
    await getDataCalendar();

    Swal.close();

});

async function getYearNow() {
    try {
        // Create a new Date object representing the current date
        var currentDate = new Date();

        // Get the current year
        var currentYear = currentDate.getFullYear();

        // Display the formatted month
        // console.log("Formatted month: " + formattedMonth);
        document.getElementById('year_calendar').value = currentYear;
        
    } catch (error) {
        errorConnectionMessage();
    }
}

async function getSearchDataCalendar() {
    try {
        await waiting();
        await getDataCalendar();
        Swal.close();
        
    } catch (error) {
        errorConnectionMessage();
    }
}

async function getDataCalendar() {
    try {
        await $.ajax({
            url: getUrl('CalendarEvent/getDataCalendar'),
            type: "post",
            data: {
                "year_calendar" : document.getElementById('year_calendar').value,
            },
            success: function (response) {
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('div_calendar').innerHTML = json.data;
                }
                else { 
                    document.getElementById('div_calendar').innerHTML = '<div class="w-100 text-center"><label class="text-red" style="font-size: 13px; font-family: `Audiowide`, sans-serif;">' + json.message + '</label></div>'; 
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
    var items = document.querySelectorAll('#div_calendar .listdata');
    
    items.forEach(function(item) {
        var text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}