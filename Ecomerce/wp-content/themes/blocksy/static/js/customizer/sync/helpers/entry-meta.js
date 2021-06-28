const removeAll = els =>
	(els.length || els.length === 0 ? [...els] : [els]).map(el => el.remove())

const removeAllTextNodes = (els, { removeFirst = true } = {}) =>
	(els.length || els.length === 0 ? [...els] : [els]).map(el => {
		;[...el.childNodes]
			.filter(
				elm => elm.nodeType != 1 && elm.textContent.trim().length !== 0
			)
			.map(
				(elm, index) =>
					(index !== 0 || (index === 0 && removeFirst)) &&
					elm.parentNode.removeChild(elm)
			)
	})

const renderLabel = (el, label, has_meta_label) => {
	if (!has_meta_label) {
		el.querySelector('span') && el.querySelector('span').remove()
		return
	}

	if (el.querySelector('span')) {
		el.querySelector('span').innerHTML = label
	}
}

export const renderSingleEntryMeta = ({
	el,
	meta_type,
	meta_divider,
	meta_elements
}) => {
	if (!el || !el.dataset) {
		return
	}
	el.dataset.type = `${meta_type || 'simple'}:${meta_divider || 'slash'}`

	if (!meta_elements) {
		return
	}

	meta_elements.map(layer => {
		let { id, enabled, label } = layer

		if (id === 'author') {
			let { has_author_avatar, avatar_size } = layer

			if (el.querySelector('.meta-author')) {
				const img = el.querySelector('.meta-author img')

				if (img) {
					img.height = avatar_size || '25'
					img.width = avatar_size || '25'
					img.style.height = `${avatar_size || 25}px`
				}
			}
		}

		if (id === 'tags' && el.querySelector('.meta-tags')) {
			let { style } = layer
			el.querySelector('.meta-tags').dataset.type = style || 'simple'
		}

		if (id === 'categories' && el.querySelector('.meta-categories')) {
			let { style } = layer

			el.querySelector('.meta-categories').dataset.type =
				style || 'simple'
		}
	})
}