$(document).ready(async function () {
	const baseUrl = "<?= base_url() ?>";
	$(".datepicker")
		.datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
		})
		.datepicker("setDate", "now");

	// Add the following code if you want the name of the file appear on select
	$(".custom-file-input").on("change", function () {
		var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});

	$("#frmEntry").submit(async function (event) {
		event.preventDefault();
		waiting();
		if (document.getElementById("from_date").value === "") {
			warningMessage("Mohon isi tanggal mulai izin dulu");
		} else if (document.getElementById("to_date").value === "") {
			warningMessage("Mohon isi tanggal akhir izin dulu");
		} else if (document.getElementById("permission_type").value === "") {
			warningMessage("Mohon pilih alasan izin dulu");
		} else {
			var destinationFile;
			var fileName;
			var uploadAttachment;
			const d = new Date();
			let time = d.getTime();

			if (document.getElementById("customFile").value) {
				if (
					document.getElementById("filename_attachment").value === "" ||
					document.getElementById("filename_attachment").value === null
				) {
					// Assuming you're interested in the first file selected
					const file = document.getElementById("customFile").files[0];

					// Get the file name
					const fileNameAttachment = file.name;

					// Get the file extension
					const fileExtension = fileNameAttachment.slice(
						((fileNameAttachment.lastIndexOf(".") - 1) >>> 0) + 2
					);
					console.log(fileExtension);
					fileName = "PERMISSION_EMPLOYEE_" + time + "." + fileExtension;
				} else {
					fileName = document.getElementById("filename_attachment").value;
				}

				destinationFile =
					getFolderCompany() + "/attachment_permission/" + fileName;

				try {
					uploadAttachment = await uploadFirebase(
						document.getElementById("customFile").files[0],
						destinationFile
					);
					if (uploadAttachment.status === true) {
						document.getElementById("url_attachment").value =
							uploadAttachment.data;
						document.getElementById("filename_attachment").value = fileName;
						await submitEntry(this);
					} else {
						await deleteFromFirebase(destinationFile);
						await warningMessage("Unggah lampiran dokumen gagal!");
					}
				} catch (error) {
					await warningMessage("Unggah lampiran dokumen gagal!");
				}
			} else {
				await submitEntry(this);
			}
		}
	});

	await waiting();

	await getMonthNow();
	await getPermissionEmployee();

	Swal.close();
});

async function getMonthNow() {
	try {
		// Create a new Date object representing the current date
		var currentDate = new Date();

		// Get the month (zero-based index) from the Date object
		var currentMonth = currentDate.getMonth() + 1; // Adding 1 to adjust zero-based index

		// Format the month to have two digits
		var formattedMonth = currentMonth < 10 ? "0" + currentMonth : currentMonth;

		// Display the formatted month
		// console.log("Formatted month: " + formattedMonth);
		document.getElementById("month_period").value = formattedMonth;
		$(".selectpicker").selectpicker("refresh");
	} catch (error) {
		errorConnectionMessage();
	}
}

// load data ijin kerja staff / karyawan
async function getPermissionEmployee() {
	try {
		await $.ajax({
			url: getUrl("PermissionEmployee/getPermissionEmployee"),
			type: "post",
			data: {
				month_period: document.getElementById("month_period").value,
				year_period: document.getElementById("year_period").value,
			},
			success: function (response) {
				// console.log(response);
				var json = $.parseJSON(response);
				if (json.status === true) {
					document.getElementById("div_permission").innerHTML = json.data;
				} else {
					document.getElementById("div_permission").innerHTML =
						'<div class="w-100 text-center"><label class="text-red" style="font-size: 13px; font-family: `Audiowide`, sans-serif;">' +
						json.message +
						"</label></div>";
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				errorConnectionMessage();
			},
		});
	} catch (error) {
		errorConnectionMessage();
	}
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

// get data ijin kerja berdasarkan id ijin kerja
async function getPermissionEmployeeById(idx) {
    console.log(idx);
	await waiting();
    document.getElementById('idx').value = idx;

	try {
		await $.ajax({
			url: getUrl("PermissionEmployee/getPermissionEmployeeById"),
			type: "post",
			data: {
				"idx": idx,
			},
			success: async function (response) {
				var json = $.parseJSON(response);
				console.log(json);
				// if (json.status === false) {
				// 	await warningMessage(json.message);
				// } else {
				// 	await successMessageCallBack(
				// 		"Yeayy",
				// 		json.message,
				// 		getUrl("PermissionEmployee")
				// 	);
				// }
			},
			error: async function (jqXHR, textStatus, errorThrown) {
				await errorConnectionMessage();
			},
		});
	} catch (error) {
        errorConnectionMessage();
    }
    await $('#modalPermissionStatus').modal({backdrop: 'static', keyboard: true});
    await $('#modalPermissionStatus').modal('show');
    await Swal.close();

}

async function getAttachment(urlAttachment) {
	await waiting();
	window.open(urlAttachment, "_blank");
	Swal.close();
}
