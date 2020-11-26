<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Home</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('controller_user') ?>">Controller User</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">Welcome</div>                    
                </div>
                <div class="card-body">
                    <h5 class="card-title">Controller User</h5>
                    <p class="card-text">
                        <a href="<?= site_url('admin/list_user') ?>">- การจัดการคำถามประเมิน</a>
                        <br>
                        <a href="<?= site_url('admin/list_user') ?>">- ดูรายงานผลการตรวจฯ</a>
                        <br>
                        <a href="<?= site_url('admin/list_user') ?>">- ดูสถิติผลการตรวจฯ</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>