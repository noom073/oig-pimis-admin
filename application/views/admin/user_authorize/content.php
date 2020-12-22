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
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/list_authorize') ?>">สิทธิผู้ใช้</a></li>
                        <li class="breadcrumb-item active">แก้ไขสิทธิผู้ใช้</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">แก้ไขสิทธิผู้ใช้</div>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <div class="mb-3">
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        </div>
                        <div>
                            <form id="privileg-form" data-user-id="<?= $userDetail['USER_ID'] ?>">
                                <div class="form-group">
                                    <label>ชื่อ นามสกุล</label>
                                    <div class="form-control"><?= "{$userDetail['TITLE']} {$userDetail['FIRSTNAME']}  {$userDetail['LASTNAME']}" ?></div>
                                </div>
                                <div class="form-group">
                                    <label>RTARF-Mail</label>
                                    <div class="form-control"><?= "{$userDetail['EMAIL']}" ?></div>
                                </div>
                                <div class="form-group">
                                    <label>สิทธิ</label>
                                    <select name="privileges[]" id="user-privileges" class="form-control select2" multiple="multiple" required></select>
                                </div>
                                <div class="form-group" id="privilege-form-result"></div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                    <button type="reset" class="btn btn-light">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>