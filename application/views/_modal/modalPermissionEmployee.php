<!-- show status ijin kerja -->
<div class="modal" id="modalPermissionStatus" style="overflow-y: scroll;" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-2">
            <form id="frmEntry" method="POST" enctype="multipart/form-data">
                <!-- Modal Header -->
                <div class="modal-header border border-left-0 border-top-0 border-right-0">
                    <h4 class="modal-title">FORM IZIN TIDAK MASUK KERJA</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body border-bottom">
                    <input type="hidden" id="idx" name="idx">
                    <table class="table table-sm mb-0" style="font-size: 13px;">
                        <tbody>
                            <tr>
                                <th>Nama</th>
                                <td>
                                    <input type="text" id="permission_no" class="form-control form-control-sm box_pointed" placeholder="AUTO" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Departemen</th>
                                <td>
                                    <input type="text" id="from_date" name="from_date" class="form-control form-control-sm datepicker box_pointed bg-white" placeholder="Pilih periode tanggal awal" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>
                                    <input type="text" id="to_date" name="to_date" class="form-control form-control-sm datepicker box_pointed bg-white" placeholder="Pilih periode tanggal akhir" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>
                                    <input type="text" id="remarks" name="remarks" class="form-control form-control-sm datepicker box_pointed bg-white" placeholder="Pilih periode tanggal akhir" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th style="color: red;">*Jika Ditolak</th>
                                <td>
                                    <textarea id="remarks_rejected" name="remarks_rejected" class="form-control form-control-sm box_pointed" rows="2" placeholder="Diisi jika ijin kerja ditolak"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="p-3 d-flex justify-content-between">
                    <button id="btnAccPermission" type="button" class="btn btn-success btn-sm btn-rounded mr-2" style="flex: 1 1 auto; max-width: 150px;">
                        <i class="fas fa-check"></i> Setuju
                    </button>
                    <br>
                    <button id="btnRejectPermission" type="button" class="btn btn-danger btn-sm btn-rounded" data-dismiss="modal" style="flex: 1 1 auto; max-width: 150px;">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal status kerja -->