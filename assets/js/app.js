/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)


let $ = require('jquery');
// jankiness cuz jquery plugins don't know what code standards are
window.jQuery = $;
require('flexslider');

require('./doubletaptogo');
require('bootstrap');

require("./init");
// somehow they're diferent here and it's making things crash even tho they should just be references ?
$ = window.jQuery;

import '../css/app.css';

import '../css/default.css'
import '../css/layout.css'
import '../css/media-queries.css'
// import bootstrap after the rest
import '../css/global.scss';




console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
