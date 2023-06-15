/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

// import 'bootstrap';
require('bootstrap');
import 'bootstrap/dist/css/bootstrap.css';

// import slick-slider
import 'slick-slider/slick/slick.scss';
import 'slick-slider/slick/slick-theme.scss';
import 'slick-slider/slick/slick.min.js';

// import all js files
import './js/ajax.js';
import './js/slick.js';
import './js/preview.js';
import './js/header.js';
import './js/report.js';
