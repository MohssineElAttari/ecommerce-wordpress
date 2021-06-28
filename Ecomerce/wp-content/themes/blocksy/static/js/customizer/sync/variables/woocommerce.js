import { handleResponsiveSwitch, withKeys } from '../helpers'
import { typographyOption } from './typography'

export const getWooVariablesFor = () => ({
	// Woocommerce archive
	shopCardsGap: {
		selector: '[data-products]',
		variable: 'grid-columns-gap',
		responsive: true,
		unit: 'px',
	},

	...withKeys(['woocommerce_catalog_columns', 'blocksy_woo_columns'], {
		selector: '[data-products]',
		variable: 'shop-columns',
		responsive: true,
		unit: '',
		extractValue: () => {
			const value = wp.customize('blocksy_woo_columns')()

			return {
				desktop: `CT_CSS_SKIP_RULE`,
				tablet: `repeat(${value.tablet}, 1fr)`,
				mobile: `repeat(${value.mobile}, 1fr)`,
			}
		},
	}),

	...withKeys(['woo_product_related_cards_columns'], {
		selector: '.related [data-products], .upsells [data-products]',
		variable: 'shop-columns',
		responsive: true,
		unit: '',
		extractValue: () => {
			const value = wp.customize('woo_product_related_cards_columns')()

			return {
				desktop: `CT_CSS_SKIP_RULE`,
				tablet: `repeat(${value.tablet}, 1fr)`,
				mobile: `repeat(${value.mobile}, 1fr)`,
			}
		},
	}),

	cardProductTitleColor: [
		{
			selector:
				'[data-products] .woocommerce-loop-product__title, [data-products] .woocommerce-loop-category__title',
			variable: 'heading-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector:
				'[data-products] .woocommerce-loop-product__title, [data-products] .woocommerce-loop-category__title',
			variable: 'linkHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	cardProductPriceColor: {
		selector: '[data-products] .price',
		variable: 'color',
		type: 'color',
		responsive: true,
	},

	starRatingColor: [
		{
			selector: ':root',
			variable: 'star-rating-initial-color',
			type: 'color:default',
		},

		{
			selector: ':root',
			variable: 'star-rating-inactive-color',
			type: 'color:inactive',
		},
	],

	global_quantity_color: [
		{
			selector: '.quantity',
			variable: 'quantity-initial-color',
			type: 'color:default',
		},

		{
			selector: '.quantity',
			variable: 'quantity-hover-color',
			type: 'color:hover',
		},
	],

	global_quantity_arrows: [
		{
			selector: '.quantity[data-type="type-1"]',
			variable: 'quantity-arrows-initial-color',
			type: 'color:default',
		},

		{
			selector: '.quantity[data-type="type-2"]',
			variable: 'quantity-arrows-initial-color',
			type: 'color:default_type_2',
		},

		{
			selector: '.quantity',
			variable: 'quantity-arrows-hover-color',
			type: 'color:hover',
		},
	],

	saleBadgeColor: [
		{
			selector: ':root',
			variable: 'badge-text-color',
			type: 'color:text',
		},

		{
			selector: ':root',
			variable: 'badge-background-color',
			type: 'color:background',
		},
	],

	outOfStockBadgeColor: [
		{
			selector: '.out-of-stock-badge',
			variable: 'badge-text-color',
			type: 'color:text',
		},

		{
			selector: '.out-of-stock-badge',
			variable: 'badge-background-color',
			type: 'color:background',
		},
	],

	cardProductCategoriesColor: [
		{
			selector: '[data-products] .entry-meta a',
			variable: 'linkInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '[data-products] .entry-meta a',
			variable: 'linkHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	// quick view button
	quick_view_button_icon_color: [
		{
			selector: '.ct-woo-card-extra .ct-open-quick-view',
			variable: 'icon-color',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.ct-woo-card-extra .ct-open-quick-view',
			variable: 'icon-hover-color',
			type: 'color:hover',
			responsive: true,
		},
	],

	quick_view_button_background_color: [
		{
			selector: '.ct-woo-card-extra .ct-open-quick-view',
			variable: 'trigger-background',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.ct-woo-card-extra .ct-open-quick-view',
			variable: 'trigger-hover-background',
			type: 'color:hover',
			responsive: true,
		},
	],

	cardProductButton1Text: [
		{
			selector: '[data-products="type-1"]',
			variable: 'buttonTextInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '[data-products="type-1"]',
			variable: 'buttonTextHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	cardProductButton2Text: [
		{
			selector: '[data-products="type-2"]',
			variable: 'buttonTextInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '[data-products="type-2"]',
			variable: 'buttonTextHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	cardProductButtonBackground: [
		{
			selector: '[data-products]',
			variable: 'buttonInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '[data-products]',
			variable: 'buttonHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	cardProductBackground: {
		selector: '[data-products="type-2"]',
		variable: 'backgroundColor',
		type: 'color',
		responsive: true,
	},

	cardProductRadius: {
		selector: '[data-products] .product',
		type: 'spacing',
		variable: 'borderRadius',
		responsive: true,
	},

	cardProductShadow: {
		selector: '[data-products="type-2"]',
		type: 'box-shadow',
		variable: 'box-shadow',
		responsive: true,
	},

	// Woocommerce single
	product_thumbs_spacing: {
		selector: '.product-entry-wrapper',
		variable: 'thumbs-spacing',
		responsive: true,
		unit: '',
	},

	productGalleryWidth: {
		selector: '.product-entry-wrapper',
		variable: 'product-gallery-width',
		unit: '%',
	},

	singleProductTitleColor: {
		selector: '.entry-summary .product_title',
		variable: 'heading-color',
		type: 'color',
	},

	singleProductPriceColor: {
		selector: '.entry-summary .price',
		variable: 'color',
		type: 'color',
	},

	// Store notice
	wooNoticeContent: {
		selector: '.demo_store',
		variable: 'color',
		type: 'color',
	},

	wooNoticeBackground: {
		selector: '.demo_store',
		variable: 'backgroundColor',
		type: 'color',
	},

	// messages
	infoMessageColor: [
		{
			selector: '.woocommerce-info, .woocommerce-message',
			variable: 'color',
			type: 'color:text',
		},

		{
			selector: '.woocommerce-info, .woocommerce-message',
			variable: 'background-color',
			type: 'color:background',
		},
	],

	errorMessageColor: [
		{
			selector: '.woocommerce-error',
			variable: 'color',
			type: 'color:text',
		},

		{
			selector: '.woocommerce-error',
			variable: 'background-color',
			type: 'color:background',
		},
	],

	// add to cart actions
	add_to_cart_button_width: {
		selector: '.entry-summary form.cart',
		variable: 'button-width',
		responsive: true,
		unit: '',
	},

	quantity_color: [
		{
			selector: '.entry-summary .quantity',
			variable: 'quantity-initial-color',
			type: 'color:default',
		},

		{
			selector: '.entry-summary .quantity',
			variable: 'quantity-hover-color',
			type: 'color:hover',
		},
	],

	quantity_arrows: [
		{
			selector: '.entry-summary .quantity[data-type="type-1"]',
			variable: 'quantity-arrows-initial-color',
			type: 'color:default',
		},

		{
			selector: '.entry-summary .quantity[data-type="type-2"]',
			variable: 'quantity-arrows-initial-color',
			type: 'color:default_type_2',
		},

		{
			selector: '.entry-summary .quantity',
			variable: 'quantity-arrows-hover-color',
			type: 'color:hover',
		},
	],

	add_to_cart_text: [
		{
			selector: '.entry-summary .single_add_to_cart_button',
			variable: 'buttonTextInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.entry-summary .single_add_to_cart_button',
			variable: 'buttonTextHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	add_to_cart_background: [
		{
			selector: '.entry-summary .single_add_to_cart_button',
			variable: 'buttonInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.entry-summary .single_add_to_cart_button',
			variable: 'buttonHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	view_cart_button_text: [
		{
			selector: '.entry-summary .ct-cart-actions .added_to_cart',
			variable: 'buttonTextInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.entry-summary .ct-cart-actions .added_to_cart',
			variable: 'buttonTextHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	view_cart_button_background: [
		{
			selector: '.entry-summary .ct-cart-actions .added_to_cart',
			variable: 'buttonInitialColor',
			type: 'color:default',
			responsive: true,
		},

		{
			selector: '.entry-summary .ct-cart-actions .added_to_cart',
			variable: 'buttonHoverColor',
			type: 'color:hover',
			responsive: true,
		},
	],

	// product tabs
	...typographyOption({
		id: 'woo_tabs_font',
		selector: '.woocommerce-tabs .tabs',
	}),

	woo_tabs_font_color: [
		{
			selector: '.woocommerce-tabs .tabs',
			variable: 'linkInitialColor',
			type: 'color:default',
		},

		{
			selector: '.woocommerce-tabs .tabs',
			variable: 'linkHoverColor',
			type: 'color:hover',
		},

		{
			selector: '.woocommerce-tabs .tabs',
			variable: 'linkActiveColor',
			type: 'color:active',
		},
	],

	woo_tabs_border_color: {
		selector: '.woocommerce-tabs[data-type] .tabs',
		variable: 'tab-border-color',
		type: 'color',
	},

	woo_actibe_tab_border: {
		selector: '.woocommerce-tabs[data-type] .tabs',
		variable: 'tab-background',
		type: 'color',
	},

	woo_actibe_tab_background: [
		{
			selector: '.woocommerce-tabs[data-type*="type-2"] .tabs',
			variable: 'tab-background',
			type: 'color:default',
		},

		{
			selector: '.woocommerce-tabs[data-type*="type-2"] .tabs li.active',
			variable: 'tab-border-color',
			type: 'color:border',
		},
	],

	// account page
	account_nav_text_color: [
		{
			selector: '.ct-acount-nav',
			variable: 'account-nav-text-initial-color',
			type: 'color:default',
		},

		{
			selector: '.ct-acount-nav',
			variable: 'account-nav-text-active-color',
			type: 'color:active',
		},
	],

	account_nav_background_color: [
		{
			selector: '.ct-acount-nav',
			variable: 'account-nav-background-initial-color',
			type: 'color:default',
		},

		{
			selector: '.ct-acount-nav',
			variable: 'account-nav-background-active-color',
			type: 'color:active',
		},
	],

	account_nav_divider_color: [
		{
			selector: '.ct-acount-nav',
			variable: 'account-nav-divider-color',
			type: 'color:default',
		},
	],

	account_nav_shadow: {
		selector: '.ct-acount-nav',
		type: 'box-shadow',
		variable: 'box-shadow',
		responsive: true
	},

})





