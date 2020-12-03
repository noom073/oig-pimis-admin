<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Auditor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Auditor</a></li>
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
                </div>
                <div class="card-body">
                    <h5 class="card-title">สายการตรวจ</h5>
                    <div class="card-text">
                        <div class="row mb-2">
                            <div class="col-6">
                                <div class="list-group">
                                    <?php foreach ($inspections['odd'] as $inspection) { ?>
                                        <button type="button" class="list-group-item list-group-item-action inspect" data-inspection-id="<?= $inspection['INSPE_ID'] ?>"><?= $inspection['INSPE_NAME'] ?></button>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="list-group">
                                    <?php foreach ($inspections['even'] as $inspection) { ?>
                                        <button type="button" class="list-group-item list-group-item-action inspect" data-inspection-id="<?= $inspection['INSPE_ID'] ?>"><?= $inspection['INSPE_NAME'] ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5>ฟอร์ม</h5>
                            <div>
                                <div id="form-questionaire"></div>
                                <div id="result-auditor-score" class="invisible">
                                    รวม <span id="total-auditor-score"></span> คะแนน
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>