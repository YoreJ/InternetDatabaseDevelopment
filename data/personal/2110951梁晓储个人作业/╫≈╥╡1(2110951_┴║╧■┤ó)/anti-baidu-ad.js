// ==UserScript==
// @name         百度去广告
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  帮你去除烦人的广告，还你一个清爽的搜索体验
// @author       2110951梁晓储
// @match        https://www.baidu.com/*
// @icon         https://www.google.com/s2/favicons?sz=64&domain=baidu.com
// @license      AGPL -3.0
// @grant        none
// ==/UserScript==

(function () {
    'use strict';

    // 要去除的元素的XPath列表
    var xpaths = [
        '//*[@id="s-hotsearch-wrapper"]',
        '//*[@id="u1"]/div[1]',
        '//*[@id="s_side_wrapper"]',
        '//*[@id="content_right"]',
        '//*[@id="top-ad"]',
        '//*[@id="con-ar"]',
        '//*[@id="s_wrap"]',

    ];

    function manipulateElements() {
        // 隐藏指定XPath的元素
        function hideElements() {
            for (var i = 0; i < xpaths.length; i++) {
                var elements = document.evaluate(xpaths[i], document, null, XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE, null);
                for (var j = 0; j < elements.snapshotLength; j++) {
                    var element = elements.snapshotItem(j);
                    element.style.display = 'none';
                }
            }
        }

        // 移除指定XPath的元素
        function removeElements() {
            for (var i = 0; i < xpaths.length; i++) {
                var elements = document.evaluate(xpaths[i], document, null, XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE, null);
                for (var j = 0; j < elements.snapshotLength; j++) {
                    var element = elements.snapshotItem(j);
                    element.parentNode.removeChild(element);
                }
            }
        }

        // 在页面加载完成前执行隐藏元素操作
        hideElements();

        // 在页面加载完成后执行移除元素操作
        window.addEventListener('load', function () {
            removeElements();
        });

        //为保证去除彻底，运用了两种方法同时去除
    }

    manipulateElements();
})();