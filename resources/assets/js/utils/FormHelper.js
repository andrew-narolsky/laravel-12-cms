export default class FormHelper {
    static send(form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        const url = form.getAttribute('action');
        const data = new FormData(form);

        return axios.post(url, data)
            .then((response) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: response.data.message,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                if (response.data.redirect) {
                    setTimeout(() => {
                        window.location = response.data.redirect;
                    }, 3000);
                }

                return response;
            })
            .catch((error) => {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    const generalMessage = error.response.data.message;

                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        html: generalMessage,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                    });

                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');

                                let feedback = input.parentNode.querySelector('.invalid-feedback');
                                if (!feedback) {
                                    feedback = document.createElement('span');
                                    feedback.classList.add('invalid-feedback');
                                    feedback.setAttribute('role', 'alert');
                                    input.parentNode.appendChild(feedback);
                                }

                                feedback.innerHTML = `${errors[key][0]}`;

                                input.addEventListener('input', function handler() {
                                    input.classList.remove('is-invalid');
                                    feedback.remove();
                                    input.removeEventListener('input', handler);
                                });
                            }
                        }
                    }
                }

                throw error;
            });
    }

    static initSelects(form, settings = {}) {
        const selectElements = form.querySelectorAll('select');
        const instances = [];

        selectElements.forEach(select => {
            const slim = new SlimSelect({
                select,
                settings: {...settings},
            });
            instances.push(slim);
        });

        return instances;
    }

    static deleteElement(element, title = 'Are you sure?', text = 'This action cannot be undone!') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const formId = element.getAttribute('form');
                const form = document.getElementById(formId);

                if (form) {
                    form.submit();
                } else {
                    console.error(`Form with id "${formId}" not found.`);
                }
            }
        });
    }
}
