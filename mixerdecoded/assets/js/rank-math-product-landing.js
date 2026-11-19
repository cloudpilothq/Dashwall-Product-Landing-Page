
(function ($, window, document) {
    'use strict';

    if (
        !window.wp ||
        !window.wp.hooks ||
        !document.querySelector('.mdpl-admin-wrap')
    ) {
        return;
    }

    var refreshTimer;

    function escapeHTML(value) {
        var element = document.createElement('div');
        element.textContent = value || '';
        return element.innerHTML;
    }

    function getValue(selector) {
        var field = document.querySelector(selector);

        return field ? field.value.trim() : '';
    }

    function makeElement(tag, value) {
        if (!value) {
            return '';
        }

        return '<' + tag + '>' + escapeHTML(value) +
            '</' + tag + '>';
    }

    function getDescription() {
        if (window.tinymce) {
            var editor = window.tinymce.get(
                'mdpl_product_description'
            );

            if (editor && !editor.isHidden()) {
                return editor.getContent();
            }
        }

        var textarea = document.getElementById(
            'mdpl_product_description'
        );

        return textarea ? textarea.value : '';
    }

    function getBenefits() {
        var output = '';

        document.querySelectorAll(
            '.mdpl-benefit-admin'
        ).forEach(function (item) {
            var title = item.querySelector(
                'input[name$="[title]"]'
            );

            var description = item.querySelector(
                'textarea[name$="[text]"]'
            );

            output += makeElement(
                'h3',
                title ? title.value.trim() : ''
            );

            output += makeElement(
                'p',
                description ? description.value.trim() : ''
            );
        });

        return output;
    }

    function getSpecifications() {
        var output = '';

        document.querySelectorAll(
            '#mdpl-specifications .mdpl-spec-row'
        ).forEach(function (row) {
            var label = row.querySelector(
                'input[name$="[label]"]'
            );

            var value = row.querySelector(
                'input[name$="[value]"]'
            );

            var labelText = label ? label.value.trim() : '';
            var valueText = value ? value.value.trim() : '';

            if (labelText || valueText) {
                output += '<p>' +
                    escapeHTML(labelText) + ': ' +
                    escapeHTML(valueText) +
                    '</p>';
            }
        });

        return output;
    }

    function getList(selector) {
        var value = getValue(selector);

        if (!value) {
            return '';
        }

        var items = value.split(/\r?\n/).filter(Boolean);

        return '<ul>' + items.map(function (item) {
            return '<li>' + escapeHTML(item) + '</li>';
        }).join('') + '</ul>';
    }

    function getLandingPageContent(content) {
        var output = content || '';

        output += makeElement('p', getValue('#mdpl_eyebrow'));
        output += makeElement('h1', getValue('#mdpl_headline'));
        output += makeElement('p', getValue('#mdpl_subhead'));

        output += getBenefits();

        output += makeElement(
            'h2',
            getValue('#mdpl_description_heading')
        );

        // Preserve the actual product-description HTML.
        output += getDescription();

        output += makeElement('h2', 'Pros');
        output += getList('#mdpl_pros');

        output += makeElement('h2', 'Cons');
        output += getList('#mdpl_cons');

        output += getSpecifications();

        return output;
    }

    window.wp.hooks.addFilter(
        'rank_math_content',
        'mixerdecoded/product-landing-content',
        getLandingPageContent,
        11
    );

    function refreshAnalysis() {
        window.clearTimeout(refreshTimer);

        refreshTimer = window.setTimeout(function () {
            if (
                window.rankMathEditor &&
                typeof window.rankMathEditor.refresh === 'function'
            ) {
                window.rankMathEditor.refresh('content');
            }
        }, 500);
    }

    // Monitor ordinary custom fields.
    $(document).on(
        'input change',
        '.mdpl-admin-wrap input, .mdpl-admin-wrap textarea',
        refreshAnalysis
    );

    // Monitor the WordPress rich-text editor.
    $(document).on(
        'tinymce-editor-init',
        function (event, editor) {
            if (
                editor &&
                editor.id === 'mdpl_product_description'
            ) {
                editor.on(
                    'keyup change input undo redo SetContent',
                    refreshAnalysis
                );
            }
        }
    );

    // Initial analysis.
    refreshAnalysis();

})(jQuery, window, document);
