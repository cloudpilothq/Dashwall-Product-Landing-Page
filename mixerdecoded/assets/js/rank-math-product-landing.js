
/**
 * MixerDecoded product landing pages
 * Rank Math content analysis integration.
 */
(function (window, document) {
    'use strict';

    if (
        !window.wp ||
        !window.wp.hooks ||
        !document.querySelector('.mdpl-admin-wrap')
    ) {
        return;
    }

    var refreshTimer = null;

    function htmlToText(html) {
        var element = document.createElement('div');
        element.innerHTML = html || '';

        return (element.textContent || element.innerText || '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function getValue(selector) {
        var field = document.querySelector(selector);
        return field ? htmlToText(field.value || '') : '';
    }

    function getValues(selector) {
        return Array.prototype.map.call(
            document.querySelectorAll(selector),
            function (field) {
                return htmlToText(field.value || '');
            }
        ).filter(Boolean);
    }

    function getProductDescription() {
        if (window.tinymce) {
            var editor = window.tinymce.get(
                'mdpl_product_description'
            );

            if (editor && !editor.isHidden()) {
                return htmlToText(editor.getContent());
            }
        }

        return getValue('#mdpl_product_description');
    }

    function getLandingPageContent() {
        var parts = [];

        // Hero content.
        parts.push(getValue('#mdpl_eyebrow'));
        parts.push(getValue('#mdpl_headline'));
        parts.push(getValue('#mdpl_subhead'));

        // Product benefits.
        parts = parts.concat(
            getValues('input[name^="mdpl_benefits"][name$="[title]"]'),
            getValues('textarea[name^="mdpl_benefits"][name$="[text]"]')
        );

        // Product overview.
        parts.push(getValue('#mdpl_description_heading'));
        parts.push(getProductDescription());

        // Pros and cons.
        parts.push(getValue('#mdpl_pros'));
        parts.push(getValue('#mdpl_cons'));

        // Product specifications.
        parts = parts.concat(
            getValues('#mdpl-specifications input[name$="[label]"]'),
            getValues('#mdpl-specifications input[name$="[value]"]')
        );

        return parts.filter(Boolean).join(' ').replace(/\s+/g, ' ').trim();
    }

    function addLandingPageContent(content) {
        var customContent = getLandingPageContent();
        var existingContent = typeof content === 'string'
            ? content
            : '';

        if (!customContent) {
            return existingContent;
        }

        return existingContent + ' ' + customContent;
    }

    window.wp.hooks.addFilter(
        'rank_math_content',
        'mixerdecoded/product-landing-content',
        addLandingPageContent,
        20
    );

    function refreshAnalysis() {
        if (refreshTimer) {
            window.clearTimeout(refreshTimer);
        }

        refreshTimer = window.setTimeout(function () {
            if (
                window.rankMathEditor &&
                typeof window.rankMathEditor.refresh === 'function'
            ) {
                window.rankMathEditor.refresh('content');
            }
        }, 350);
    }

    // Watch custom fields.
    document.addEventListener('input', function (event) {
        if (
            event.target &&
            event.target.closest('.mdpl-admin-wrap')
        ) {
            refreshAnalysis();
        }
    });

    document.addEventListener('change', function (event) {
        if (
            event.target &&
            event.target.closest('.mdpl-admin-wrap')
        ) {
            refreshAnalysis();
        }
    });

    // Watch the rich-text editor.
    if (window.tinymce) {
        window.tinymce.on('AddEditor', function (event) {
            if (
                event.editor &&
                event.editor.id === 'mdpl_product_description'
            ) {
                event.editor.on(
                    'keyup change input undo redo SetContent',
                    refreshAnalysis
                );
            }
        });

        var editor = window.tinymce.get(
            'mdpl_product_description'
        );

        if (editor) {
            editor.on(
                'keyup change input undo redo SetContent',
                refreshAnalysis
            );
        }
    }

    // Initial analysis.
    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            refreshAnalysis,
            { once: true }
        );
    } else {
        refreshAnalysis();
    }

})(window, document);
