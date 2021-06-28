import { mount as mountResponsiveHeader } from '../header/responsive-desktop-menu'
import { getCurrentScreen } from '../helpers/current-screen'
import { mountMenuLevel, handleUpdate } from '../header/menu'

export const menuEntryPoints = [
	{
		els: ['header [data-device="desktop"] [data-id*="menu"] > .menu'],
		condition: () => getCurrentScreen() === 'desktop',
		load: () => new Promise((r) => r()),
		onLoad: false,
		mount: ({ el }) => mountMenuLevel(el, { startPosition: 'left' }),
		events: ['ct:general:device-change', 'ct:header:init-popper'],
	},

	{
		els: [
			'header [data-device="desktop"] [data-id*="menu"] > .menu .menu-item-has-children',
			'header [data-device="desktop"] [data-id*="menu"] > .menu .page_item_has_children',
		],
		load: () => new Promise((r) => r({ handleUpdate })),
		mount: ({ handleUpdate, el }) => handleUpdate(el),
		onLoad: false,
		events: ['ct:general:device-change', 'ct:header:init-popper'],
		condition: ({ allEls }) => getCurrentScreen() === 'desktop',
	},

	{
		els:
			'header [data-device="desktop"] [data-id^="menu"][data-responsive]',
		load: () => new Promise((r) => r({ mount: mountResponsiveHeader })),
		events: ['ct:general:device-change', 'ct:header:render-frame'],
		condition: () => getCurrentScreen() === 'desktop',
	},

	{
		els: '#offcanvas .child-indicator',
		load: () => import('../mobile-menu'),
		events: ['ct:modal:opened'],
		condition: ({ allEls }) =>
			allEls.some((el) =>
				el.closest('.ct-panel').classList.contains('active')
			),
	},
]
