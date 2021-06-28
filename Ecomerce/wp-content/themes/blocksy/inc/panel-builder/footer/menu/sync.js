import { typographyOption } from '../../../../static/js/customizer/sync/variables/typography'
import ctEvents from 'ct-events'
import {
	responsiveClassesFor,
	getRootSelectorFor,
	assembleSelector,
	mutateSelector,
} from '../../../../static/js/customizer/sync/helpers'

export const handleMenuVariables = ({ itemId }) => ({
	footerMenuItemsSpacing: {
		selector: assembleSelector(
			getRootSelectorFor({ itemId, panelType: 'footer' })
		),
		variable: 'menu-items-spacing',
		responsive: true,
		unit: 'px',
	},

	footerMenuAlignment: {
		selector: assembleSelector(
			mutateSelector({
				selector: getRootSelectorFor({ itemId, panelType: 'footer' }),
				operation: 'replace-last',
				to_add: `[data-column="${itemId}"]`,
			})
		),
		variable: 'horizontal-alignment',
		responsive: true,
		unit: '',
	},

	footerMenuVerticalAlignment: {
		selector: assembleSelector(
			mutateSelector({
				selector: getRootSelectorFor({ itemId, panelType: 'footer' }),
				operation: 'replace-last',
				to_add: `[data-column="${itemId}"]`,
			})
		),
		variable: 'vertical-alignment',
		responsive: true,
		unit: '',
	},

	...typographyOption({
		id: 'footerMenuFont',

		selector: assembleSelector(
			mutateSelector({
				selector: getRootSelectorFor({ itemId, panelType: 'footer' }),
				operation: 'suffix',
				to_add: 'ul',
			})
		),
	}),

	footerMenuFontColor: [
		{
			selector: assembleSelector(
				mutateSelector({
					selector: getRootSelectorFor({
						itemId,
						panelType: 'footer',
					}),
					operation: 'suffix',
					to_add: '> ul > li > a',
				})
			),
			variable: 'linkInitialColor',
			type: 'color:default',
		},

		{
			selector: assembleSelector(
				mutateSelector({
					selector: getRootSelectorFor({
						itemId,
						panelType: 'footer',
					}),
					operation: 'suffix',
					to_add: '> ul > li > a',
				})
			),
			variable: 'linkHoverColor',
			type: 'color:hover',
		},
	],

	footerMenuMargin: {
		selector: assembleSelector(
			getRootSelectorFor({ itemId, panelType: 'footer' })
		),
		type: 'spacing',
		variable: 'margin',
		responsive: true,
		important: true,
	},
})

export const handleMenuOptions = ({
	selector,
	changeDescriptor: { optionId, optionValue, values },
}) => {
	const el = document.querySelector(selector)

	if (optionId === 'stretch_menu' || optionId === 'menu_items_direction') {
		el.removeAttribute('data-stretch')

		el.classList.add('ct-disable-transitions')

		el.dataset.menuDirection = values.menu_items_direction

		if (
			values.stretch_menu === 'yes' &&
			values.menu_items_direction === 'horizontal'
		) {
			el.dataset.stretch = ''
		}

		setTimeout(() => {
			el.classList.remove('ct-disable-transitions')
		}, 500)
	}

	if (optionId === 'footer_menu_visibility') {
		responsiveClassesFor(optionValue, el)
	}
}

ctEvents.on('ct:footer:sync:item:menu', (changeDescriptor) => {
	const selector = '.footer-menu'
	handleMenuOptions({ selector, changeDescriptor })
})

ctEvents.on('ct:footer:sync:item:menu-secondary', (changeDescriptor) => {
	const selector = '.footer-menu-2'
	handleMenuOptions({ selector, changeDescriptor })
})

ctEvents.on(
	'ct:footer:sync:collect-variable-descriptors',
	(variableDescriptors) => {
		variableDescriptors['menu'] = handleMenuVariables
	}
)

ctEvents.on(
	'ct:footer:sync:collect-variable-descriptors',
	(variableDescriptors) => {
		variableDescriptors['menu-secondary'] = handleMenuVariables
	}
)
