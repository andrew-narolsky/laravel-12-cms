import axios from 'axios';
window.axios = axios;

import * as bootstrap from 'bootstrap';
import SlimSelect from 'slim-select'
import 'slim-select/styles';
import FormHelper from './utils/FormHelper.js';
import FileUploader from './utils/FileUploader.js';

window.FormHelper = FormHelper;
window.SlimSelect = SlimSelect;
window.FileUploader = FileUploader;

const modules = import.meta.glob('./modules/*.js', { eager: true });
Object.entries(modules).forEach(([path, module]) => {
    const name = path.split('/').pop().replace('.js', '');
    window[name] = module.default;
});

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
