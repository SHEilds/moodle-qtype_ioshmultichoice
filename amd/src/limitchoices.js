define(["jquery"], function ($) {
	var selector = ".answer input[type=checkbox]",
		limit

	var checkboxStateHandler = function () {
		var unselected = [],
			selected = []

		// Categorise the checkbox states.
		$(selector).each(function (_) {
			this.checked ? selected.push(this) : unselected.push(this)
		})

		// Remove any checkboxes which go over the limit.
		while (selected.length > limit) {
			selected.pop().checked = false
		}

		// Disable checkboxes that are unchecked at limit reached.
		$(unselected).each(function (_) {
			this.disabled = selected.length == limit
		})
	}

	var init = function (_limit) {
		limit = _limit

		checkboxStateHandler()

		$(selector).each(function (_) {
			$(this).on("change", checkboxStateHandler)
		})
	}

	return { init: init }
})
