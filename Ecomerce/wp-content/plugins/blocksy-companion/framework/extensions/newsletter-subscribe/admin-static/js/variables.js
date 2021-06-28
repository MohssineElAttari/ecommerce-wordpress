import { handleVariablesFor } from 'customizer-sync-helpers'

handleVariablesFor({
	newsletter_subscribe_content: [
		{
			selector: '.ct-newsletter-subscribe-block',
			variable: 'color',
			type: 'color:default',
		},

		{
			selector: '.ct-newsletter-subscribe-block',
			variable: 'linkHoverColor',
			type: 'color:hover',
		},
	],

	newsletter_subscribe_button: [
		{
			selector: '.ct-newsletter-subscribe-block',
			variable: 'buttonInitialColor',
			type: 'color:default',
		},

		{
			selector: '.ct-newsletter-subscribe-block',
			variable: 'buttonHoverColor',
			type: 'color:hover',
		},
	],

	newsletter_subscribe_background: {
		selector: '.ct-newsletter-subscribe-block',
		variable: 'mailchimpBackground',
		type: 'color',
	},

	newsletter_subscribe_shadow: {
		selector: '.ct-newsletter-subscribe-block',
		type: 'box-shadow',
		variable: 'box-shadow',
		responsive: true,
	},

	newsletter_subscribe_spacing: {
		selector: '.ct-newsletter-subscribe-block',
		variable: 'padding',
		responsive: true,
		unit: 'px',
	},
})
