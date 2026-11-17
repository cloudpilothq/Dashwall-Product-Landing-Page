
document.addEventListener('DOMContentLoaded', function () {
	'use strict';

	var header = document.querySelector('.mdpl-site-header');
	var menuToggle = document.querySelector('.mdpl-menu-toggle');
	var navigation = document.querySelector('.mdpl-primary-nav');

	/**
	 * Mobile navigation.
	 */
	function closeMenu() {
		if (!header || !menuToggle) {
			return;
		}

		header.classList.remove('is-menu-open');
		menuToggle.setAttribute('aria-expanded', 'false');
		menuToggle.setAttribute('aria-label', 'Open navigation menu');
	}

	if (header && menuToggle && navigation) {
		menuToggle.addEventListener('click', function () {
			var isOpen = header.classList.toggle('is-menu-open');

			menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			menuToggle.setAttribute(
				'aria-label',
				isOpen ? 'Close navigation menu' : 'Open navigation menu'
			);
		});

		navigation.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				closeMenu();
			});
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				closeMenu();
			}
		});

		document.addEventListener('click', function (event) {
			if (
				header.classList.contains('is-menu-open') &&
				!header.contains(event.target)
			) {
				closeMenu();
			}
		});

		window.addEventListener('resize', function () {
			if (window.innerWidth > 760) {
				closeMenu();
			}
		});
	}

	/**
	 * Smooth scrolling for links to sections on this page.
	 */
	document.querySelectorAll('.mdpl-product-page a[href^="#"], .mdpl-primary-nav a[href^="#"]').forEach(function (link) {
		link.addEventListener('click', function (event) {
			var selector = link.getAttribute('href');

			if (!selector || selector === '#') {
				return;
			}

			var target;

			try {
				target = document.querySelector(selector);
			} catch (error) {
				return;
			}

			if (!target) {
				return;
			}

			event.preventDefault();

			target.scrollIntoView({
				behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
					? 'auto'
					: 'smooth',
				block: 'start'
			});
		});
	});
});
