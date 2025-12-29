import SweetAlert from "../utils/SweetAlert.js";
import JobWatcher from "../utils/JobWatcher.js";

export default class Backups {
    makeBackupButton = document.querySelector("#make-backup");
    openModalButton = document.querySelector("[data-bs-target=\"#restore-modal\"]");

    removeBackupButtons = document.querySelectorAll("[data-remove-backup]");
    restoreBackupButtons = document.querySelectorAll("[data-restore-backup]");

    constructor() {
        this.initEvents();
    }

    initEvents() {
        this.makeBackupButton.addEventListener('click', this.onMakeBackupOnClick.bind(this));

        this.removeBackupButtons.forEach((button) => {
            button.addEventListener('click', this.onRemoveClick.bind(this));
        });

        this.restoreBackupButtons.forEach((button) => {
            button.addEventListener('click', this.onRestoreClick.bind(this));
        });
    }

    async onMakeBackupOnClick() {
        this.changeDisability(true);

        try {
            const { data } = await axios.post('/admin/backups/make');

            SweetAlert.toast({ icon: 'success', title: data.message });
            setTimeout(() => window.location.reload(), 3000);
        } catch (error) {
            const message = error.response?.statusText || 'Unknown error';
            SweetAlert.toast({ icon: 'error', title: message });
            setTimeout(() => this.changeDisability(false), 3000);
        }
    }

    async onRemoveClick(event) {
        const row = event.target.closest('tr');
        const id = row.getAttribute('data-id');

        if (await SweetAlert.confirm({
            title: 'Delete backup?',
            text: 'This backup will be permanently removed and cannot be restored.',
            confirmButtonText: 'Yes, delete it',
            icon: 'warning',
        })) {
            this.changeDisability(true);

            try {
                const { data } = await axios.post('/admin/backups/remove', {
                    id: id,
                    _method: 'delete'
                });

                row.remove();
                SweetAlert.toast({ icon: 'success', title: data.message });
            } catch (error) {
                const message = error.response?.statusText || 'Unknown error';
                SweetAlert.toast({ icon: 'error', title: message });
            } finally {
                setTimeout(() => this.changeDisability(false), 3000);
            }
        }
    }

    async onRestoreClick(event) {
        const row = event.target.closest('tr');
        const id = row.getAttribute('data-id');
        const progressBar = row.querySelector('.progress-bar');

        if (await SweetAlert.confirm({
            title: 'Restore backup?',
            text: 'Current data will be overwritten!',
            confirmButtonText: 'Yes, restore',
            icon: 'warning',
        })) {
            this.changeDisability(true);

            try {
                const { data } = await axios.post('/admin/backups/restore', {
                    id: id,
                });

                JobWatcher.watch(
                    data.job,
                    progressBar,
                    '/admin/backups/get-job-status.json',
                    () => {
                        SweetAlert.toast({ icon: 'success', title: 'Backup restored successfully.' });
                        this.changeDisability(false);
                    },
                    () => {
                        SweetAlert.toast({ icon: 'error', title: 'Job failed.' });
                        this.changeDisability(false);
                    }
                );
            } catch (error) {
                const message = error.response?.statusText || 'Unknown error';
                SweetAlert.toast({ icon: 'error', title: message });
            }
        }
    }

    changeDisability(disabled) {
        this.makeBackupButton.disabled = disabled;
        this.openModalButton.disabled = disabled;

        this.removeBackupButtons?.forEach(btn => btn.disabled = disabled);
        this.restoreBackupButtons?.forEach(btn => btn.disabled = disabled);
    }
}
