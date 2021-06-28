const init_admin_exclusion = function () {

	const execute = () => {
		const template_add_exclude_url = document.querySelector("#tpl-exclusion-url");
		const template_add_exclude_block = document.querySelector("#tpl-exclusion-block");
		const parent_exclude_block_append = document.querySelector("#container-exclude_blocks");

		function removeLine(e) {
			e.preventDefault()
			this.parentNode.remove()
		}

		if (document.querySelector("#js-add-exclude-block")) {
			document
				.querySelector("#js-add-exclude-block")
				.addEventListener("click", (e) => {
					e.preventDefault()
					parent_exclude_block_append.insertAdjacentHTML("beforeend", template_add_exclude_block.innerHTML);
					document
						.querySelector(
							"#container-exclude_blocks .item-exclude:last-child .js-btn-remove-exclude"
						)
						.addEventListener("click", removeLine);
				});
		}

		const remove_urls = document
			.querySelectorAll(".js-btn-remove")

		remove_urls.forEach((el) => {
			el.addEventListener("click", removeLine);
		})


	}

	document.addEventListener('DOMContentLoaded', () => {
		execute();
	})
}

export default init_admin_exclusion;

