/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)


let $ = require('jquery');
window.jQuery = $;
require("bootstrap-tagsinput");
require('flexslider');

require('./doubletaptogo');
require('bootstrap');

require("./init");
$ = window.jQuery;

import '../css/app.css';

import '../css/default.css'
import '../css/layout.css'
import '../css/media-queries.css'

import '../css/global.scss';



// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

$('.tag-input').tagsinput();