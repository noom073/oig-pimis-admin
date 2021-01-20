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
                        <li class="breadcrumb-item active">กำหนดแผนการตรวจ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="h3">กำหนดแผนการตรวจ</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="card-text">
                        <div id="load-calendar">Loading...</div>
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- CREATE PALN Modal -->
        <div class="modal fade" id="create-plan-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>หน่วย:</label>
                                <input type="text" class="form-control" id="search-units" placeholder="ค้นหาหน่วย">
                                <select class="form-control" id="nprt-units" required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>วันเริ่มต้น</label>
                                <input type="date" class="form-control" id="date-start" required>
                            </div>
                            <div class="form-group">
                                <label>วันสิ้นสุด</label>
                                <input type="date" class="form-control" id="date-end" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CREATE PALN Modal -->
    </div>
</div>