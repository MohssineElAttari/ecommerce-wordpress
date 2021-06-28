import { mount as mountMiniCart } from './mini-cart'

export const wooEntryPoints = [
	{
		els: 'body.single-product .woocommerce-product-gallery',
		condition: () =>
			!!document.querySelector(
				'.woocommerce-product-gallery .ct-image-container'
			),
		load: () => import('./single-product-gallery'),
		trigger: ['hover-with-click'],
	},

	{
		els: 'form.variations_form',
		condition: () =>
			!!document.querySelector(
				'.woocommerce-product-gallery .ct-image-container'
			),
		load: () => import('./variable-products'),
		trigger: ['hover'],
	},

	{
		els: '.quantity',
		load: () => import('./quantity-input'),
		forcedEvents: ['ct:add-to-cart:quantity'],
		trigger: ['hover'],
	},

	{
		els: () => [
			...document.querySelectorAll('.ct-ajax-add-to-cart .cart'),
			...document.querySelectorAll('.ct-floating-bar .cart'),
		],
		load: () => import('./add-to-cart-single'),
		trigger: ['submit'],
	},

	{
		els: '.ct-header-cart',
		load: () => new Promise((r) => r({ mount: mountMiniCart })),
		events: ['ct:header:update'],
	},
]
