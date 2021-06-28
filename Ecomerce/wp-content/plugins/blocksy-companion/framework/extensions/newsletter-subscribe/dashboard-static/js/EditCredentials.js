import {
	createElement,
	Component,
	useEffect,
	useState,
	Fragment,
} from '@wordpress/element'

import classnames from 'classnames'
import { __, sprintf } from 'ct-i18n'
import ListPicker from './ListPicker'
import Overlay from '../../../../../static/js/helpers/Overlay'
import { Select } from 'blocksy-options'

const EditCredentials = ({
	extension,
	isEditingCredentials,
	setIsEditingCredentials,
	onCredentialsValidated,
}) => {
	const [provider, setProvider] = useState(extension.data.provider)
	const [apiKey, setApiKey] = useState(extension.data.api_key)
	const [listId, setListId] = useState(extension.data.list_id)
	const [isLoading, setIsLoading] = useState(false)
	const [isApiKeyInvalid, makeKeyInvalid] = useState(false)

	const attemptToSaveCredentials = async () => {
		const body = new FormData()

		body.append('provider', provider)
		body.append('api_key', apiKey)
		body.append('list_id', listId)

		body.append(
			'action',
			'blocksy_ext_newsletter_subscribe_maybe_save_credentials'
		)

		setIsLoading(true)

		try {
			const response = await fetch(ctDashboardLocalizations.ajax_url, {
				method: 'POST',
				body,
			})

			if (response.status === 200) {
				const body = await response.json()

				if (body.success) {
					if (body.data.result !== 'api_key_invalid') {
						onCredentialsValidated()
						makeKeyInvalid(false)
					}
				}
			}

			makeKeyInvalid(true)
		} catch (e) {
			makeKeyInvalid(true)
		}

		await new Promise((r) => setTimeout(() => r(), 1000))

		setIsLoading(false)
	}

	return (
		<Overlay
			items={isEditingCredentials}
			onDismiss={() => setIsEditingCredentials(false)}
			className={'ct-mailchimp-modal'}
			render={() => (
				<div
					className={classnames('ct-modal-content', {
						'ct-key-invalid': isApiKeyInvalid,
					})}>
					<h2>{__('API Credentials', 'blc')}</h2>

					<p
						dangerouslySetInnerHTML={{
							__html: __(
								'Enter your Newsletter provider API credentials in the form below.',
								'blc'
							),
						}}
					/>

					<div className="ct-newsletter-select-provider">
						<Fragment>
							<h4>{__('Select Provider', 'blc')}</h4>

							<Select
								onChange={(copy) => {
									setProvider(copy)
								}}
								option={{
									placeholder: __(
										'Pick Mailing Service',
										'blc'
									),
									choices: [
										{
											key: 'mailchimp',
											value: 'Mailchimp',
										},

										{
											key: 'mailerlite',
											value: 'Mailerlite',
										},
									],
								}}
								value={provider}
							/>
						</Fragment>

						{!ctDashboardLocalizations.plugin_data.is_pro &&
							provider !== 'mailchimp' && (
								<p
									dangerouslySetInnerHTML={{
										__html: sprintf(
											__(
												'This option is available only in Blocksy premium %sversion%s.',
												'blc'
											),

											'<a target="_blank" href="https://creativethemes.com/blocksy/pricing/">',
											'</a>'
										),
									}}
								/>
							)}

						{provider === 'mailchimp' && (
							<p
								dangerouslySetInnerHTML={{
									__html: sprintf(
										__(
											'More info on how to generate an API key for Mailchimp can be found %shere%s.',
											'blc'
										),

										'<a target="_blank" href="https://mailchimp.com/help/about-api-keys/">',
										'</a>'
									),
								}}
							/>
						)}

						{ctDashboardLocalizations.plugin_data.is_pro &&
							provider === 'mailerlite' && (
								<p
									dangerouslySetInnerHTML={{
										__html: sprintf(
											__(
												'More info on how to generate an API key for Mailerlite can be found %shere%s.',
												'blc'
											),

											'<a target="_blank" href="https://help.mailerlite.com/article/show/35040-where-to-find-the-mailerlite-api-key-and-api-documentation">',
											'</a>'
										),
									}}
								/>
							)}
					</div>

					{(provider === 'mailchimp' ||
						ctDashboardLocalizations.plugin_data.is_pro) && (
						<div className="mailchimp-credentials">
							<section>
								<label>{__('API Key', 'blc')}</label>

								<div className="ct-option-input">
									<input
										type="text"
										onChange={({ target: { value } }) =>
											setApiKey(value)
										}
										value={apiKey || ''}
									/>
								</div>
							</section>

							<section>
								<label>{__('List ID', 'blc')}</label>

								<ListPicker
									listId={listId}
									onChange={(id) => setListId(id)}
									provider={provider}
									apiKey={apiKey}
								/>
							</section>

							<section>
								<label>&nbsp;</label>
								<button
									className="ct-button"
									data-button="blue"
									disabled={!apiKey || !listId || isLoading}
									onClick={() => attemptToSaveCredentials()}>
									{isLoading
										? __('Loading...', 'blc')
										: !extension.__object
										? __('Activate', 'blc')
										: __('Save Settings', 'blc')}
								</button>
							</section>
						</div>
					)}
				</div>
			)}
		/>
	)
}

export default EditCredentials
