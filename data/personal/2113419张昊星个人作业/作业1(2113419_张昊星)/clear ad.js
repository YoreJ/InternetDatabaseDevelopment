// ==UserScript==
// @name         去除百度广告
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  去除百度网页上的广告元素
// @author       张昊星2113419
// @match        https://www.baidu.com/*
// @grant        none
// ==/UserScript==

(function() {
    'use strict';

    const selectors = [
        '#s-hotsearch-wrapper',
        '#u1 > div:first-child',
        '#s_side_wrapper',
        '#content_right',
        '#top-ad',
        '#con-ar',
        '#s_wrap'
    ];

    function removeAds() {
        selectors.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.remove();
            }
        });
    }

    removeAds();
})();
