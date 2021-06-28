import { handleBackgroundOptionFor } from '../../../../static/js/customizer/sync/variables/background'
import ctEvents from 'ct-events'
import { updateAndSaveEl } from '../../../../static/js/frontend/header/render-loop'
import {
	getRootSelectorFor,
	assembleSelector,
	mutateSelector,
} from '../../../../static/js/customizer/sync/helpers'

ctEvents.on(
	'ct:header:sync:collect-variable-descriptors',
	(variableDescriptors) => {
		const handleSectionBackground = ({ itemId }) =>
			handleBackgroundOptionFor({
				id: 'section',
				selector: assembleSelector(
					mutateSelector({
						selector: getRootSelectorFor({ itemId }),
						operation: 'suffix',
						to_add: '> section',
					})
				),

				responsive: true,
				addToDescriptors: {
					fullValue: true,
				},

				valueExtractor: ({ offcanvasBackground }) =>
					offcanvasBackground,
			}).section

		const handleRootBackground = ({ itemId }) =>
			handleBackgroundOptionFor({
				id: 'section',
				selector: assembleSelector(getRootSelectorFor({ itemId })),
				responsive: true,
				addToDescriptors: {
					fullValue: true,
				},

				valueExtractor: ({
					offcanvas_behavior,
					offcanvasBackdrop,
					offcanvasBackground,
				}) =>
					offcanvas_behavior === 'modal'
						? offcanvasBackground
						: offcanvasBackdrop,
			}).section

		variableDescriptors['offcanvas'] = ({ itemId }) => ({
			offcanvas_behavior: [
				...handleSectionBackground({ itemId }),
				...handleRootBackground({ itemId }),
			],
			offcanvasBackground: [
				...handleSectionBackground({ itemId }),
				...handleRootBackground({ itemId }),
			],
			offcanvasBackdrop: [...handleRootBackground({ itemId })],

			headerPanelShadow: {
				selector: assembleSelector(
					`${
						getRootSelectorFor({ itemId })[0]
					} [data-behaviour*="side"]`
				),
				type: 'box-shadow',
				variable: 'box-shadow',
				responsive: true,
			},

			side_panel_width: {
				selector: assembleSelector(getRootSelectorFor({ itemId })),
				variable: 'side-panel-width',
				responsive: true,
				unit: '',
			},

			offcanvas_content_vertical_alignment: {
				selector: assembleSelector(getRootSelectorFor({ itemId })),
				variable: 'vertical-alignment',
				responsive: true,
				unit: '',
			},

			offcanvasContentAlignment: [
				{
					selector: assembleSelector(getRootSelectorFor({ itemId })),
					variable: 'horizontal-alignment',
					responsive: true,
					unit: '',
				},

				{
					selector: assembleSelector(getRootSelectorFor({ itemId })),
					variable: 'has-indentation',
					unit: '',
					responsive: true,

					extractValue: (value) => {
						if (value.desktop) {
							if (
								value.desktop === 'center' ||
								value.tablet === 'center' ||
								value.mobile === 'center'
							) {
								return {
									desktop:
										value.desktop === 'center' ? '0' : '1',
									tablet:
										value.tablet === 'center' ? '0' : '1',
									mobile:
										value.mobile === 'center' ? '0' : '1',
								}
							}
						}

						return 'CT_CSS_SKIP_RULE'
					},
				},
			],

			menu_close_button_color: [
				{
					selector: assembleSelector(
						mutateSelector({
							selector: getRootSelectorFor({ itemId }),
							operation: 'suffix',
							to_add: '.ct-close-button',
						})
					),
					variable: 'icon-color',
					type: 'color:default',
				},

				{
					selector: assembleSelector(
						mutateSelector({
							selector: getRootSelectorFor({ itemId }),
							operation: 'suffix',
							to_add: '.ct-close-button',
						})
					),
					variable: 'icon-hover-color',
					type: 'color:hover',
				},
			],

			menu_close_button_shape_color: [
				{
					selector: assembleSelector(
						mutateSelector({
							selector: getRootSelectorFor({ itemId }),
							operation: 'suffix',
							to_add: '.ct-close-button',
						})
					),
					variable: 'closeButtonBackground',
					type: 'color:default',
				},

				{
					selector: assembleSelector(
						mutateSelector({
							selector: getRootSelectorFor({ itemId }),
							operation: 'suffix',
							to_add: '.ct-close-button',
						})
					),
					variable: 'closeButtonHoverBackground',
					type: 'color:hover',
				},
			],
		})
	}
)

ctEvents.on(
	'ct:header:sync:item:offcanvas',
	({ optionId, optionValue, values }) => {
		const selector = '#offcanvas'

		if (
			optionId === 'offcanvas_behavior' ||
			optionId === 'side_panel_position'
		) {
			const el = document.querySelector('#offcanvas')

			if (document.body.dataset.panel) {
				// document.querySelector('.ct-header-trigger').click
			}

			setTimeout(() => {
				el.removeAttribute('data-behaviour')
				el.classList.add('ct-disable-transitions')

				requestAnimationFrame(() => {
					el.dataset.behaviour =
						values.offcanvas_behavior === 'modal'
							? 'modal'
							: `${values.side_panel_position}-side`

					setTimeout(() => {
						el.classList.remove('ct-disable-transitions')
					})
				})
			}, 300)
		}
	}
)
