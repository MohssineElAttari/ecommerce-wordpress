import ctEvents from 'ct-events'
import { registerDynamicChunk } from 'blocksy-frontend'
import { handleAccountModal, activateScreen } from './frontend/account'

if (document.querySelector('#account-modal')) {
	handleAccountModal(document.querySelector('#account-modal'))
}

registerDynamicChunk('blocksy_account', {
	mount: (el, { event }) => {
		event.preventDefault()

		if (el.closest('.must-log-in')) {
			let maybeAccount = document.querySelector(
				'.ct-header-account[href]'
			)

			if (maybeAccount) {
				maybeAccount.click()
			} else {
				event.target.dynamicJsChunkStop()
				event.target.click()
			}

			return
		}

		try {
			document.querySelector(el.hash)
		} catch (e) {
			return
		}

		activateScreen(document.querySelector(el.hash), {
			screen: 'login',
		})

		ctEvents.trigger('ct:overlay:handle-click', {
			e: event,
			href: el.hash,
			options: {
				isModal: true,
			},
		})
	},
})
