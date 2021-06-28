import { onDocumentLoaded } from '../helpers'

onDocumentLoaded(() => {
	if (!document.querySelector('.comment-form')) return

	let inputs = [
		...document.querySelectorAll(
			'.comment-form input[type="text"], .comment-form input[type="email"], .comment-form textarea'
		),
	]

	const renderEmptiness = () => {
		inputs.map((input) => {
			input.parentNode.classList.remove('ct-not-empty')

			if (input.value.trim().length > 0) {
				input.parentNode.classList.add('ct-not-empty')
			}
		})
	}

	renderEmptiness()

	inputs.map((input) => input.addEventListener('input', renderEmptiness))
})

/**
 * Shim WordPress's addComment.moveForm in order to properly add classes
 */
onDocumentLoaded(() => {
	if (!window.addComment) return
	if (!window.addComment.moveForm) return

	let originalMoveForm = addComment.moveForm

	addComment.moveForm = (...args) => {
		originalMoveForm.apply(addComment, args)

		document.getElementById(args[0]).classList.add('ct-has-reply-form')

		let cancel = document.getElementById('cancel-comment-reply-link')

		let originalCancel = cancel.onclick

		cancel.onclick = function () {
			originalCancel.call(this)
			document
				.getElementById(args[0])
				.classList.remove('ct-has-reply-form')

			return false
		}

		return false
	}
})
