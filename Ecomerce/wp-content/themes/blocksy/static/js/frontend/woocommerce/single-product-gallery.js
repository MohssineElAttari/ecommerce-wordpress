import $ from 'jquery'
import ctEvents from 'ct-events'

function isTouchDevice() {
	try {
		document.createEvent('TouchEvent')
		return true
	} catch (e) {
		return false
	}
}

export const mount = (el, { event: mountEvent }) => {
	const openPhotoswipeFor = (el, index = null) => {
		var pswpElement = $('.pswp')[0],
			eventTarget = $(el),
			clicked = eventTarget

		const items = [
			...el
				.closest('.woocommerce-product-gallery')
				.querySelectorAll(
					'.flexy-items .ct-image-container img:not(.zoomImg), .woocommerce-product-gallery > .ct-image-container img:not(.zoomImg)'
				),
		].map((img) => ({
			img,
			src: img.closest('a') ? img.closest('a').href : img.src,
			w:
				(img.closest('a')
					? img.closest('a').dataset.width
					: img.width) || img.width,
			h:
				(img.closest('a')
					? img.closest('a').dataset.height
					: img.width) || img.width,
			title: img.getAttribute('title'),
		}))

		if (
			items.length === 1 &&
			items[0].img.closest('a') &&
			!items[0].img.closest('a').getAttribute('href')
		) {
			return
		}

		var options = $.extend(
			{
				index: index === 0 ? 0 : index || $(clicked).index(),
				addCaptionHTMLFn: function (item, captionEl) {
					if (!item.title) {
						captionEl.children[0].textContent = ''
						return false
					}
					captionEl.children[0].textContent = item.title
					return true
				},
			},
			{
				shareEl: false,
				closeOnScroll: false,
				history: false,
				hideAnimationDuration: 0,
				showAnimationDuration: 0,
			}
		)

		// Initializes and opens PhotoSwipe.
		var photoswipe = new PhotoSwipe(
			pswpElement,
			PhotoSwipeUI_Default,
			items,
			options
		)

		photoswipe.init()

		document.body.classList.add('ct-photoswipe-open')

		photoswipe.listen('close', () => {
			setTimeout(() => {
				document.body.classList.remove('ct-photoswipe-open')
			}, 300)
		})
	}

	const renderPhotoswipe = ({ onlyZoom = false } = {}) => {
		let maybeTrigger = [
			...document.querySelectorAll(
				'.woocommerce-product-gallery .woocommerce-product-gallery__trigger'
			),
		]

		;[
			...document.querySelectorAll(
				'.single-product .flexy-items .ct-image-container, .single-product .woocommerce-product-gallery > .ct-image-container'
			),
		].map((el) => {
			if (
				((window.wp &&
					wp.customize &&
					wp.customize('has_product_single_lightbox') &&
					wp.customize('has_product_single_lightbox')() === 'yes') ||
					!window.wp ||
					!wp.customize) &&
				!onlyZoom
			) {
				if (!el.hasPhotoswipeListener) {
					el.hasPhotoswipeListener = true
					el.addEventListener('click', (e) => {
						e.preventDefault()

						if (maybeTrigger.length > 0) {
							return
						}

						let activeIndex = 0

						if (el.closest('.flexy-items')) {
							activeIndex = [
								...el.closest('.flexy-items').children,
							].indexOf(el.parentNode)
						}

						window.PhotoSwipe && openPhotoswipeFor(el, activeIndex)
					})
				}
			}

			if ($.fn.zoom) {
				if (
					(window.wp &&
						wp.customize &&
						wp.customize('has_product_single_zoom') &&
						wp.customize('has_product_single_zoom')() === 'yes') ||
					!window.wp ||
					!wp.customize
				) {
					const rect = el.getBoundingClientRect()

					$(el).zoom({
						url: el.href,
						touch: false,
						duration: 50,

						...(rect.width > parseFloat(el.dataset.width) ||
						rect.height > parseFloat(el.dataset.height)
							? {
									magnify: 2,
							  }
							: {}),

						...(isTouchDevice()
							? {
									on: 'toggle',
							  }
							: {}),
					})
				}
			}
		})

		if ($.fn.zoom) {
			if (
				(wp.customize &&
					wp.customize('has_product_single_zoom') &&
					wp.customize('has_product_single_zoom')() === 'yes') ||
				!wp.customize
			) {
				setTimeout(() => {
					if (!mountEvent) {
						return
					}

					if (
						mountEvent.target.closest('.flexy-items') ||
						(mountEvent.target.closest('.ct-image-container') &&
							mountEvent.target
								.closest('.ct-image-container')
								.parentNode.classList.contains(
									'woocommerce-product-gallery'
								))
					) {
						$(
							mountEvent.target.closest('.ct-image-container')
						).trigger(
							isTouchDevice() ? 'click.zoom' : 'mouseenter.zoom'
						)
					}
				}, 150)
			}
		}

		maybeTrigger.map((maybeTrigger) => {
			if (maybeTrigger.hasPhotoswipeListener) {
				return
			}

			maybeTrigger.hasPhotoswipeListener = true

			maybeTrigger.addEventListener('click', (e) => {
				e.preventDefault()
				e.stopPropagation()

				if (
					maybeTrigger.closest('.ct-image-container') &&
					!maybeTrigger.closest('.flexy-items')
				) {
					window.PhotoSwipe &&
						openPhotoswipeFor(
							maybeTrigger.closest('.ct-image-container')
						)

					return
				}

				if (
					maybeTrigger.closest('.ct-image-container') &&
					maybeTrigger.closest('.flexy-items') &&
					maybeTrigger.closest('.ct-columns-top-gallery')
				) {
					window.PhotoSwipe &&
						openPhotoswipeFor(
							maybeTrigger.closest('.ct-image-container'),

							[
								...maybeTrigger.closest('.ct-image-container')
									.parentNode.parentNode.children,
							].indexOf(
								maybeTrigger.closest('.ct-image-container')
									.parentNode
							)
						)

					return
				}

				if (
					document.querySelector(
						'.single-product .woocommerce-product-gallery > .ct-image-container'
					)
				) {
					window.PhotoSwipe &&
						openPhotoswipeFor(
							document.querySelector(
								'.single-product .woocommerce-product-gallery > .ct-image-container'
							)
						)
				}

				if (
					document.querySelector(
						'.single-product .flexy-items .ct-image-container'
					)
				) {
					let pills = document.querySelector(
						'.single-product .flexy-pills'
					)

					let activeIndex = Array.from(
						pills.querySelector('.active').parentNode.children
					).indexOf(
						pills.querySelector('.active') ||
							pills.firstElementChild
					)

					window.PhotoSwipe &&
						openPhotoswipeFor(
							document.querySelector(
								'.single-product .flexy-items'
							).children[activeIndex].firstElementChild,

							activeIndex
						)
				}
			})
		})
	}

	renderPhotoswipe()
}
