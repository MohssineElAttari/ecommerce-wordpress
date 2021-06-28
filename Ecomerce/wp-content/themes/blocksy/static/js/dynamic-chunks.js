import $script from 'scriptjs'

let loadedChunks = {}
let intersectionObserver = null

const loadChunkWithPayload = (chunk, payload = {}, el = null) => {
	const immediateMount = () => {
		if (el) {
			loadedChunks[chunk.id].mount(el, payload)
		} else {
			;[...document.querySelectorAll(chunk.selector)].map((el) => {
				loadedChunks[chunk.id].mount(el, payload)
			})
		}
	}

	if (loadedChunks[chunk.id]) {
		immediateMount()
	} else {
		if (chunk.deps) {
			const depsThatAreNotLoadedIds = chunk.deps.filter(
				(id) =>
					!document.querySelector(
						`script[src*="${chunk.deps_data[id]}"]`
					)
			)
			const depsThatAreNotLoaded = depsThatAreNotLoadedIds.map(
				(id) => chunk.deps_data[id]
			)

			if (depsThatAreNotLoadedIds.includes('underscore')) {
				$script(chunk.deps_data.underscore, () => {
					$script(
						[chunk.url, ...depsThatAreNotLoaded],
						immediateMount
					)
				})
			} else {
				$script([chunk.url, ...depsThatAreNotLoaded], immediateMount)
			}
		} else {
			$script(chunk.url, immediateMount)
		}
	}
}

const addChunkToIntersectionObserver = (chunk) => {
	if (!window.IntersectionObserver) {
		return
	}

	if (!intersectionObserver) {
		intersectionObserver = new IntersectionObserver((entries) => {
			entries.map(({ boundingClientRect, target, isIntersecting }) => {
				const chunk = target.__chunk__

				if (!isIntersecting && boundingClientRect.y > 0) {
					return
				}

				let state = `target-before-bottom`

				if (!isIntersecting && boundingClientRect.y < 0) {
					state = 'target-after-bottom'
				}

				if (
					state === 'target-before-bottom' &&
					!loadedChunks[chunk.id]
				) {
					return
				}

				loadChunkWithPayload(chunk, { state, target }, chunk.el)
			})
		})
	}

	;[...document.querySelectorAll(chunk.selector)].map((el) => {
		if (el.ioObserving) {
			return
		}

		el.ioObserving = true

		const target = document.querySelector(chunk.target)

		if (!target) {
			return
		}

		target.__chunk__ = { ...chunk, el }

		intersectionObserver.observe(target)
	})
}

export const mountDynamicChunks = () => {
	const requestIdleCallback =
		window.requestIdleCallback ||
		function (cb) {
			var start = Date.now()
			return setTimeout(function () {
				cb({
					didTimeout: false,
					timeRemaining: function () {
						return Math.max(0, 50 - (Date.now() - start))
					},
				})
			}, 1)
		}

	ct_localizations.dynamic_js_chunks.map((chunk) => {
		if (!chunk.id) {
			return
		}

		if (!document.querySelector(chunk.selector)) {
			return
		}

		if (chunk.trigger) {
			if (chunk.trigger === 'click') {
				;[...document.querySelectorAll(chunk.selector)].map((el) => {
					if (el.hasLazyLoadClickListener) {
						return
					}

					el.hasLazyLoadClickListener = true

					const cb = (event) => {
						if (
							chunk.ignore_click &&
							event.target.matches(chunk.ignore_click)
						) {
							return
						}

						event.preventDefault()
						loadChunkWithPayload(chunk, { event }, el)
					}

					el.dynamicJsChunkStop = () => {
						el.removeEventListener('click', cb)
					}

					el.addEventListener('click', cb)
				})
			}

			if (chunk.trigger === 'submit') {
				;[...document.querySelectorAll(chunk.selector)].map((el) => {
					if (el.hasLazyLoadSubmitListener) {
						return
					}

					el.hasLazyLoadSubmitListener = true

					el.addEventListener('submit', (event) => {
						event.preventDefault()
						loadChunkWithPayload(chunk, { event }, el)
					})
				})
			}

			if (chunk.trigger === 'intersection-observer') {
				addChunkToIntersectionObserver(chunk)
			}

			if (chunk.trigger === 'scroll') {
				setTimeout(() => {
					let prevScroll = scrollY

					let cb = (e) => {
						if (Math.abs(scrollY - prevScroll) > 30) {
							document.removeEventListener('scroll', cb)
							loadChunkWithPayload(chunk)
							return
						}
					}

					document.addEventListener('scroll', cb)
				}, 500)
			}
		} else {
			loadChunkWithPayload(chunk)
		}
	})
}

export const registerDynamicChunk = (id, implementation) => {
	if (loadedChunks[id]) {
		return
	}

	loadedChunks[id] = implementation
}
