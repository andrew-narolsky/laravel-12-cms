import axios from 'axios';
window.axios = axios;

import * as bootstrap from 'bootstrap';

import 'sweetalert2/dist/sweetalert2.min.css';
import Swal from 'sweetalert2';
window.Swal = Swal;

import SlimSelect from 'slim-select'
import 'slim-select/styles';
window.SlimSelect = SlimSelect;

import FormHelper from './utils/FormHelper';
window.FormHelper = FormHelper;

import FileUploader from "./utils/FileUploader";

const modules = import.meta.glob('./modules/*.js', { eager: true });
Object.entries(modules).forEach(([path, module]) => {
    const name = path.split('/').pop().replace('.js', '');
    window[name] = module.default;
});

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
