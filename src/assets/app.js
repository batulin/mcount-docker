/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */


// require jQuery normally
const $ = require('jquery');


// create global $ and jQuery variables
global.$ = global.jQuery = $;

import './lib/bootstrap5/css/bootstrap.css';
import './lib/bootstrap5/js/bootstrap.js';

import './lib/fontawesome/css/all.css';
// any CSS you import will output into a single css file (app.css in this case)
import './css/styles.css';
import './css/app.css';

import './js/scripts.js';

// start the Stimulus application
//import './bootstrap';

import logoPath from './img/logo.svg';
