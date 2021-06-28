!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=1)}([function(e,t){e.exports=window.blocksyCustomizerSync},function(e,t,n){"use strict";n.r(t);var o,r=function(e){if([e.top,e.right,e.bottom,e.left].reduce((function(e,t){return!!e&&!("auto"!==t&&t&&t.toString().match(/\d/g))}),!0))return"CT_CSS_SKIP_RULE";var t=["auto"!==e.top&&e.top.toString().match(/\d/g)?e.top:0,"auto"!==e.right&&e.right.toString().match(/\d/g)?e.right:0,"auto"!==e.bottom&&e.bottom.toString().match(/\d/g)?e.bottom:0,"auto"!==e.left&&e.left.toString().match(/\d/g)?e.left:0];return t[0]===t[1]&&t[0]===t[2]&&t[0]===t[3]?t[0]:t[0]===t[2]&&t[1]===t[3]?"".concat(t[0]," ").concat(t[3]):t.join(" ")},c=function(e,t){var n=t.forcedOutput,o=void 0!==n&&n;if("CT_CSS_SKIP_RULE"===e)return"CT_CSS_SKIP_RULE";if("none"===e)return"none";if(!e.enable)return o?"none":"CT_CSS_SKIP_RULE";if(0===parseFloat(e.blur)&&0===parseFloat(e.spread)&&0===parseFloat(e.v_offset)&&0===parseFloat(e.h_offset))return o?"none":"CT_CSS_SKIP_RULE";var r=[];return e.inset&&r.push("inset"),r.push("".concat(e.h_offset,"px")),r.push("".concat(e.v_offset,"px")),0!==parseFloat(e.blur)&&(r.push("".concat(e.blur,"px")),0!==parseFloat(e.spread)&&r.push("".concat(e.spread,"px"))),0===parseFloat(e.blur)&&0!==parseFloat(e.spread)&&(r.push("".concat(e.blur,"px")),r.push("".concat(e.spread,"px"))),r.push(e.color.color),r.join(" ")},i=function(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"desktop",o={desktop:"ct-main-styles-inline-css",tablet:"ct-main-styles-tablet-inline-css",mobile:"ct-main-styles-mobile-inline-css"},r=document.querySelector("style#".concat(o[n])),c=r.innerText,i="".concat(e["".concat(n,"_selector_prefix")]?"".concat(e["".concat(n,"_selector_prefix")]," "):"").concat(e.selector||":root"),a=null,l=c.match(a);0===c.trim().indexOf(i)?(a=new RegExp("".concat(i.replace(/[.*+?^${}()|[\]\\]/g,"\\$&"),"\\s?{[\\s\\S]*?}"),"gm"),l=c.match(a)):(a=new RegExp("\\}\\s*?".concat(i.replace(/[.*+?^${}()|[\]\\]/g,"\\$&"),"\\s?{[\\s\\S]*?}"),"gm"),l=c.match(a)),l||(0===(c="".concat(c," ").concat(i," {   }")).trim().indexOf(i)?(a=new RegExp("".concat(i.replace(/[.*+?^${}()|[\]\\]/g,"\\$&"),"\\s?{[\\s\\S]*?}"),"gm"),l=c.match(a)):(a=new RegExp("\\}\\s*?".concat(i.replace(/[.*+?^${}()|[\]\\]/g,"\\$&"),"\\s?{[\\s\\S]*?}"),"gm"),l=c.match(a))),r.innerText=c.replace(a,l[0].indexOf("--".concat(e.variable,":"))>-1?l[0].replace(new RegExp("--".concat(e.variable,":[\\s\\S]*?;"),"gm"),t.indexOf("CT_CSS_SKIP_RULE")>-1||t.indexOf(e.variable)>-1?"":"--".concat(e.variable,": ").concat(t,";")):l[0].replace(new RegExp("".concat(i.replace(/[.*+?^${}()|[\]\\]/g,"\\$&"),"\\s?{"),"gm"),"".concat(i," {").concat(t.indexOf("CT_CSS_SKIP_RULE")>-1||t.indexOf(e.variable)>-1?"":"--".concat(e.variable,": ").concat(t,";"))))},a=function(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"desktop",o=(e.type||"").indexOf("color")>-1?t["color"===e.type?"default":e.type.split(":")[1]].color:t;"border"===(e.type||"")&&(o=t&&"none"!==t.style?"".concat(t.width,"px ").concat(t.style," ").concat(t.color.color):"none"),"spacing"===(e.type||"")&&(o=r(t)),"box-shadow"===(e.type||"")&&(o=c(t,e)),i(e,"".concat(o).concat(e.unit||"").concat(e.important?" !important":""),n)},l=function(e,t){var n=t;t=e.extractValue?e.extractValue(t):t,e.whenDone&&e.whenDone(t,n),t=function(e){var t=!(arguments.length>1&&void 0!==arguments[1])||arguments[1];return e&&Object.keys(e).indexOf("desktop")>-1?t?e:e.desktop:t?{desktop:e,tablet:e,mobile:e}:e}(t,!!e.responsive),e.responsive?(e.enabled&&"no"===!wp.customize(e.enabled)()&&(t.mobile="0"+(e.unit?"":"px"),t.tablet="0"+(e.unit?"":"px"),t.desktop="0"+(e.unit?"":"px")),a(e,t.desktop,"desktop"),a(e,t.tablet,"tablet"),a(e,t.mobile,"mobile")):a(e,t)},s=n(0);function p(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function u(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?p(Object(n),!0).forEach((function(t){d(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):p(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function d(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}o=u(u({trendingBlockContainerSpacing:{selector:".ct-trending-block",variable:"padding",responsive:!0,unit:""}},Object(s.typographyOption)({id:"trendingBlockPostsFont",selector:".ct-trending-block .ct-item-title"})),{},{trendingBlockFontColor:[{selector:".ct-trending-block",variable:"color",type:"color:default",responsive:!0},{selector:".ct-trending-block",variable:"linkHoverColor",type:"color:hover",responsive:!0}]},Object(s.handleBackgroundOptionFor)({id:"trending_block_background",selector:".ct-trending-block",responsive:!0})),wp.customize.bind("change",(function(e){return o[e.id]&&(Array.isArray(o[e.id])?o[e.id]:[o[e.id]]).map((function(t){return l(t,e())}))})),wp.customize("trending_block_visibility",(function(e){return e.bind((function(e){return Object(s.responsiveClassesFor)("trending_block_visibility",document.querySelector(".ct-trending-block"))}))})),wp.customize("trending_block_label",(function(e){return e.bind((function(e){var t=document.querySelector(".ct-trending-block .ct-block-title");if(t){var n=t.innerHTML.split("<svg");n[0]=e,t.innerHTML=n.join("<svg")}}))}))}]);