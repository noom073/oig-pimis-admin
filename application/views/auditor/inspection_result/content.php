<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Auditor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('auditor/index') ?>">Auditor</a></li>
                        <li class="breadcrumb-item active">การตรวจราชการ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <div class="h3">รายการที่บันทึกการตรวจราชการ</div>
                    <div class="h4">
                        <u><?= $plan['STANDFOR'] ?></u>
                    </div>
                    <div>
                        <small class="text-danger">ห้วงวันที่:
                            <?= date("d/m/Y", strtotime($plan['INS_DATE'])) ?>
                            -
                            <?= date("d/m/Y", strtotime($plan['FINISH_DATE'])) ?>
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div>
                            <button class="btn btn-sm btn-primary disabled" id="add-note-btn" disabled>เพิ่มบันทึก</button>
                            <button class="btn btn-sm btn-light" onclick="return window.history.back();">ย้อนกลับ</button>
                        </div>
                        <div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>สายการตรวจ</th>
                                        <th>link</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="create-note-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มบันทึก</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="create-note-form">
                        <div class="form-group">
                            <label for="exampleInputEmail1">เรื่อง</label>
                            <select class="form-control" name="" id="create-note-form-inspections">
                                <option value="">--</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>หน่วยรับตรวจ</label>
                                <input class="form-control" type="text" name="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>ผบ.หน่วย</label>
                                <input class="form-control" type="text" name="">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ตรวจเมื่อวันที่</label>
                                <input class="form-control" type="text" name="">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ผู้รับตรวจ</label>
                                <input class="form-control" type="text" name="">
                            </div>
                            <div class="form-group col-md-6">
                                <label>ตำแหน่ง</label>
                                <input class="form-control" type="text" name="">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>ผู้ตรวจ</label>
                                <input class="form-control" type="text" name="">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>ข้อควรแก้ไขและข้อบกพร่องที่ตรวจพบ</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label>1. ข้อควรแก้ไข</label>
                            <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>2. ข้อบกพร่อง</label>
                            <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>3. ข้อบกพร่องสำคัญมาก</label>
                            <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label>4. ข้อสังเกตจากการตรวจและข้อแนะนำ</label>
                            <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>ผลคะแนนตามสายงาน</label>
                                <input class="form-control" type="text" name="">
                            </div>
                            <div class="form-group col-md-12">
                                <label>คะแนนประสิทธิภาพในการปฏิบัติงาน</label>
                                <input class="form-control" type="text" name="">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">บันทึก</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>