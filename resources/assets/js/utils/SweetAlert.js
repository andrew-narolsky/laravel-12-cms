import 'sweetalert2/dist/sweetalert2.min.css';
import Swal from 'sweetalert2';

export default class SweetAlert {
    static toast(options = {}) {
        const defaults = {
            toast: true,
            icon: 'success',
            title: '',
            html: '',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        };

        Swal.fire({ ...defaults, ...options });
    }

    static confirm(options = {}) {
        const defaults = {
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
        };

        return Swal.fire({ ...defaults, ...options })
            .then(result => result.isConfirmed);
    }
}
