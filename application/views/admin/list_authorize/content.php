<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Administrator</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Admin</a></li>
                        <li class="breadcrumb-item active">สิทธิผู้ใช้</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">สิทธิผู้ใช้</div>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <div class="mb-3">
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        </div>
                        <div>
                            <table id="table-user" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th>ชื่อผู้ใช้</th>
                                        <th>RTARF-MAIL</th>
                                        <th>สิทธิ</th>
                                        <th class="text-center">การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div>
        <!-- EDIT PRIVILEGE Modal -->
        <div class="modal fade" id="edit-privilege-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">แก้ไขสิทธิผู้ใช้งาน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="create-user-form">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <input class="form-control" type="text" placeholder="คำนำหน้าชื่อ" name="title" required>
                                </div>

                                <div class="form-group col-md-5">
                                    <input class="form-control" type="text" placeholder="ชื่อ" name="fname" required>
                                </div>

                                <div class="form-group col-md-5">
                                    <input class="form-control" type="text" placeholder="นามสกุล" name="lname" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="RTARF Mail" name="email" style="border-right: 1px solid #f1f1f1;">
                                        <div class="input-group-append">
                                            <span class="input-group-text">@rtarf.mi.th</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-danger">** ใส่ Email โดยไม่ต้องระบุ "@rtarf.mi.th"</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>เปิด/ปิด การใช้งาน</label>
                                    <select class="form-control" name="activation" id="act-create-user-form">
                                        <option value="y">เปิด</option>
                                        <option value="n">ปิด</option>
                                    </select>
                                </div>
                            </div>

                            <div id="result-create-user-form"></div>

                            <div>
                                <button class="btn btn-primary" type="submit">บันทึก</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">ปิด</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- END CREATE USER Modal -->
    </div>
</div>