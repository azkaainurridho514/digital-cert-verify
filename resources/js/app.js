import './bootstrap';
import { Html5Qrcode } from 'html5-qrcode';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// Expose ke window
window.Html5Qrcode = Html5Qrcode;
window.Swal = Swal;

console.log('App.js loaded');
console.log('Html5Qrcode:', typeof Html5Qrcode);
console.log('Swal:', typeof Swal);