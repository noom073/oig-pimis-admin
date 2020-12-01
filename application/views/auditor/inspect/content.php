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
                                <div>
                                    <!-- <ul>
                                        <li>1. xxxxx
                                            <ul>
                                                <li>1.1. subject
                                                    <ul>
                                                        <li class="d-flex">                                                            
                                                           <div>1.1.1 question</div>
                                                           <div class="ml-auto">
                                                               <input type="radio" name="" id="">
                                                               <input type="radio" name="" id="">
                                                               <input type="radio" name="" id="">
                                                               <div>
                                                                   <input type="file" name="" id="">
                                                               </div>
                                                           </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul> -->
                                </div>

                                <div id="xx"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>