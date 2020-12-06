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
                    <div class="h3">การตรวจราชการ</div>
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
                    <h5 class="card-title">บันทึกผลการตรวจของชุดตรวจ สจร.ทหาร</h5>
                    <div class="card-text">
                        <div>
                            <form>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">เรื่อง</label>
                                    <select class="form-control" name="" id="">
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>