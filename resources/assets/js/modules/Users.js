export default class Users {

    form = document.getElementById('users-form');
    updateButton = document.querySelector('#update');

    constructor() {
        FormHelper.initSelects(this.form, { showSearch: false });
        this.initEvents();
    }

    initEvents() {
        this.updateButton.addEventListener('click', this.onUpdateButtonClick.bind(this));
    }

    onUpdateButtonClick(e) {
        e.preventDefault();
        FormHelper.send(this.form);
    }
}
