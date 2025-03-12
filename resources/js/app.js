import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import jquery from 'jquery';
window.$ = window.jQuery = jquery;

import 'slick-carousel';

import moment from "moment";
window.moment = moment;

import.meta.glob([
    '../images/**',
    '../images/card-type/**',
    '../fonts/**',
    '../videos/**'
]);