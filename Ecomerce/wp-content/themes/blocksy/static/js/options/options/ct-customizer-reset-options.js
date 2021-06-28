import { Fragment, createElement, useState } from '@wordpress/element'
import Overlay from '../../customizer/components/Overlay'
import { __ } from 'ct-i18n'

const CustomizerResetOptions = ({ value, option, onChange }) => {
	const [isShowing, setIsShowing] = useState(false)

	return (
		<Fragment>
			<button
				className="button"
				style={{width: '100%'}}
				onClick={(e) => {
					e.preventDefault()

					setIsShowing(true)
				}}>
				{__('Reset Options', 'blocksy')}
			</button>

			<Overlay
				items={isShowing}
				className="ct-admin-modal ct-reset-options"
				onDismiss={() => setIsShowing(false)}
				render={() => (
					<div className="ct-modal-content">
						<h2 className="ct-modal-title">Reset Settings</h2>
						<p>
							You are about to reset all settings to their default
							values, are you sure you want to continue?
						</p>

						<div
							className="ct-modal-actions has-divider"
							data-buttons="2">
							<button
								onClick={(e) => {
									e.preventDefault()
									e.stopPropagation()
									setIsShowing(false)
								}}
								className="button">
								Cancel
							</button>

							<button
								className="button button-primary"
								onClick={(e) => {
									e.preventDefault()

									jQuery.post(
										ajaxurl,
										{
											wp_customize: 'on',
											action: 'ct_customizer_reset',
											nonce:
												ct_customizer_localizations.customizer_reset_none,
										},
										() => {
											wp.customize
												.state('saved')
												.set(true)
											location.reload()
										}
									)
								}}>
								Confirm
							</button>
						</div>
					</div>
				)}
			/>
		</Fragment>
	)
}

export default CustomizerResetOptions
