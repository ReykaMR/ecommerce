</div>
</div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Global Alert System untuk Admin
    class AlertSystem {
        constructor() {
            this.alertContainer = $('#alertContainer');
            this.alertQueue = [];
            this.isShowingAlert = false;
            this.processFlashMessages();
        }

        processFlashMessages() {
            <?php if ($this->session->flashdata('success')): ?>
                this.showAlert('success', '<?php echo addslashes($this->session->flashdata('success')); ?>');
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                this.showAlert('danger', '<?php echo addslashes($this->session->flashdata('error')); ?>');
            <?php endif; ?>
        }

        showAlert(type, message, duration = 5000) {
            this.alertQueue.push({ type, message, duration });
            this.processQueue();
        }

        processQueue() {
            if (this.isShowingAlert || this.alertQueue.length === 0) return;
            this.isShowingAlert = true;
            const alert = this.alertQueue.shift();
            this.displayAlert(alert.type, alert.message, alert.duration);
        }

        displayAlert(type, message, duration) {
            const alertClass = type === 'success' ? 'alert-success' :
                type === 'warning' ? 'alert-warning' :
                    type === 'info' ? 'alert-info' : 'alert-danger';

            const icon = type === 'success' ? 'fa-check-circle' :
                type === 'warning' ? 'fa-exclamation-triangle' :
                    type === 'info' ? 'fa-info-circle' : 'fa-exclamation-circle';

            const alertId = 'alert-' + Date.now();
            const alertHtml = `
                    <div id="${alertId}" class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fas ${icon} me-2"></i>${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

            this.alertContainer.append(alertHtml);

            setTimeout(() => {
                $(`#${alertId}`).alert('close');
            }, duration);

            $(`#${alertId}`).on('closed.bs.alert', () => {
                setTimeout(() => {
                    this.isShowingAlert = false;
                    this.processQueue();
                }, 300);
            });
        }

        clearAll() {
            this.alertQueue = [];
            this.alertContainer.empty();
            this.isShowingAlert = false;
        }
    }

    let alertSystem;

    $(document).ready(function () {
        alertSystem = new AlertSystem();

        window.showAlert = function (type, message, duration = 5000) {
            alertSystem.showAlert(type, message, duration);
        };

        window.clearAlerts = function () {
            alertSystem.clearAll();
        };
    });
</script>
</body>

</html>