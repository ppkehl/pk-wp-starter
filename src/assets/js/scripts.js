// ################################################################################################################
// ##
// ##     JAVASCRIPT FUNCTIONS IMPORTS 
// ##
// ################################################################################################################

//===============================================================================================================
// UTIL FUNCTIONS
// From: https://github.com/WebDevSimplified/js-util-functions
//===============================================================================================================

import * as common from "./utils/common"
import * as formatters from "./utils/formatters"
import * as dom from "./utils/dom"
import * as misc from "./utils/misc"
import * as _ from 'lodash-es'

//===============================================================================================================
// PLUGINS IMPORTS
//===============================================================================================================

//------------------------------------------------------------------------------------------
// Lazysizes
//------------------------------------------------------------------------------------------

// Import JS
import { lazysizes } from "lazysizes";

//------------------------------------------------------------------------------------------
// Tiny Slider
//------------------------------------------------------------------------------------------

// Import JS
import { tns } from "tiny-slider"
// Import CSS
import "../../../node_modules/tiny-slider/dist/tiny-slider.css"

//------------------------------------------------------------------------------------------
// Tiny Slider
//------------------------------------------------------------------------------------------

var slider = tns({
    container: '.my-slider',
    items: 1,
    slideBy: 'page',
    autoplay: true,
    mouseDrag: true,
    loop: true
});

//------------------------------------------------------------------------------------------
// Examples
//------------------------------------------------------------------------------------------

var testLodashDebounce = _.debounce(function () {
    console.log('Lodash debounced after 1000ms!');
}, 1000);

var testLodashThrottle = _.throttle(function () {
    console.log('Lodash throttle after 1000ms!');
}, 1000);

document.addEventListener("mousemove", e => {
    testLodashDebounce()
    testLodashThrottle()
})

console.log(_.partition([1, 2, 3, 4], n => n % 2));