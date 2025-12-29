<div class="modal fade" id="restore-modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content card">
            <div class="modal-header">
                <h5 class="modal-title">Upload backup</h5>
                <button type="button" class="btn btn-danger btn-icon" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    @include('admin.partials.uploader', [
                        'module' => 'backups',
                        'moduleId' => null,
                        'id' => null,
                        'file' => null,
                        'allowed' => 'archives',
                        'maxSize' => 100,
                        'uploadUrl' => route('backup.upload'),
                        'deleteUrl' => route('backup.remove', ['id' => '__ID__']),
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
