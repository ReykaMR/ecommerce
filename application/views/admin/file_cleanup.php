<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">File Cleanup Utility</h2>
    <a href="<?php echo site_url('admin'); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0"><i class="fas fa-broom me-2"></i>Cleanup Orphaned Files</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            This utility will scan for and delete files that exist in the uploads folders but don't have corresponding
            records in the database (orphaned files).
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-circle fa-2x text-primary mb-3"></i>
                        <h5>Avatars</h5>
                        <p class="text-muted">uploads/avatars/</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-image fa-2x text-success mb-3"></i>
                        <h5>Product Images</h5>
                        <p class="text-muted">uploads/products/</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-file-invoice-dollar fa-2x text-info mb-3"></i>
                        <h5>Payment Proofs</h5>
                        <p class="text-muted">uploads/payments/</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button id="runCleanupBtn" class="btn btn-warning btn-lg">
                <i class="fas fa-broom me-2"></i>Run File Cleanup
            </button>
        </div>

        <div id="cleanupResults" class="mt-4" style="display: none;">
            <h5>Cleanup Results:</h5>
            <div id="resultsContent"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#runCleanupBtn').on('click', function () {
            var button = $(this);

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Cleaning...');

            $.ajax({
                url: '<?php echo site_url('admin/run_file_cleanup'); ?>',
                method: 'POST',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showAlert('success', response.message);

                        // Show results
                        var resultsHtml = '<div class="alert alert-success">';
                        resultsHtml += '<strong>' + response.total_cleaned + ' files cleaned</strong>';
                        resultsHtml += '</div>';

                        if (response.cleaned_files.avatars.length > 0) {
                            resultsHtml += '<h6>Avatars Deleted:</h6><ul>';
                            response.cleaned_files.avatars.forEach(function (file) {
                                resultsHtml += '<li>' + file + '</li>';
                            });
                            resultsHtml += '</ul>';
                        }

                        if (response.cleaned_files.product_images.length > 0) {
                            resultsHtml += '<h6>Product Images Deleted:</h6><ul>';
                            response.cleaned_files.product_images.forEach(function (file) {
                                resultsHtml += '<li>' + file + '</li>';
                            });
                            resultsHtml += '</ul>';
                        }

                        if (response.cleaned_files.payment_proofs.length > 0) {
                            resultsHtml += '<h6>Payment Proofs Deleted:</h6><ul>';
                            response.cleaned_files.payment_proofs.forEach(function (file) {
                                resultsHtml += '<li>' + file + '</li>';
                            });
                            resultsHtml += '</ul>';
                        }

                        if (response.total_cleaned === 0) {
                            resultsHtml = '<div class="alert alert-info">No orphaned files found. Everything is clean!</div>';
                        }

                        $('#resultsContent').html(resultsHtml);
                        $('#cleanupResults').show();
                    }
                },
                error: function () {
                    showAlert('danger', 'An error occurred during file cleanup.');
                },
                complete: function () {
                    button.prop('disabled', false).html('<i class="fas fa-broom me-2"></i>Run File Cleanup');
                }
            });
        });
    });
</script>