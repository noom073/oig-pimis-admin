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
                    <h5 class="card-title">รายการตรวจราชการ</h5>
                    <div class="card-text">
                        <div class="pt-3">
                            <a href="<?= site_url("auditor/inspect?plan={$plan['ID']}") ?>" class="btn btn-info">
                                <i class="far fa-file-alt"></i>
                                การตรวจตามสายงาน
                            </a>
                            <a href="<?= site_url("auditor/inspection_result?plan={$plan['ID']}") ?>" class="btn btn-info">
                                <i class="far fa-file-alt"></i>
                                บันทึกผลการตรวจ
                            </a>
                            <a href="<?= site_url("auditor/inspection_summary?plan={$plan['ID']}") ?>" class="btn btn-info">
                                <i class="far fa-file-alt"></i>
                                สรุปผลการตรวจ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>