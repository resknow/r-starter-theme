document.addEventListener('DOMContentLoaded', function () {
	var menu, menuToggle;

	// Get Menu Toggle buttons
	menuToggle = document.getElementById('menu-toggle');

	// Get Menu
	menu = document.getElementById('menu');

	/**
	 * Toggle Menu
	 */
	menuToggle.addEventListener('click', function (e) {
		e.preventDefault();

		// Toggle menu state
		menu.classList.toggle('is-active');
		menuToggle.classList.toggle('is-active');
	});
});
