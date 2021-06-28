<?php
/**
 * Newsletter Subscribe widget
 *
 * @copyright 2019-present Creative Themes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package   Blocksy
 */


$options = [
	'title' => [
		'type' => 'text',
		'label' => __( 'Title', 'blc' ),
		'field_attr' => [ 'id' => 'widget-title' ],
		'design' => 'inline',
		'value' => __( 'Newsletter', 'blc' ),
		'disableRevertButton' => true,
	],

	'newsletter_subscribe_text' => [
		'label' => __( 'Message', 'blc' ),
		'type' => 'textarea',
		'value' => __( 'Enter your email address below to subscribe to our newsletter', 'blc' ),
		'design' => 'inline',
		'disableRevertButton' => true,
	],

	'newsletter_subscribe_list_id_source' => [
		'type' => 'ct-radio',
		'label' => __( 'List Source', 'blc' ),
		'value' => 'default',
		'view' => 'radio',
		'inline' => true,
		'design' => 'inline',
		'disableRevertButton' => true,
		'choices' => [
			'default' => __('Default', 'blc'),
			'custom' => __('Custom', 'blc'),
		],
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'newsletter_subscribe_list_id_source' => 'custom' ],
		'options' => [

			'newsletter_subscribe_list_id' => [
				'label' => __( 'List ID', 'blc' ),
				'type' => 'blocksy-newsletter-subscribe',
				'value' => '',
				'design' => 'inline',
				'disableRevertButton' => true,
			],

		],
	],

	'has_newsletter_subscribe_name' => [
		'type'  => 'ct-switch',
		'label' => __( 'Name Field', 'blc' ),
		'value' => 'no',
		'disableRevertButton' => true,
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [ 'has_newsletter_subscribe_name' => 'yes' ],
		'options' => [

			'newsletter_subscribe_name_label' => [
				'type' => 'text',
				'label' => __( 'Name Label', 'blc' ),
				'design' => 'inline',
				'value' => __( 'Your name', 'blc' ),
				'disableRevertButton' => true,
			],

		],
	],

	'newsletter_subscribe_mail_label' => [
		'type' => 'text',
		'label' => __( 'Mail Label', 'blc' ),
		'design' => 'inline',
		'value' => __( 'Your email', 'blc' ),
		'disableRevertButton' => true,
	],

	'newsletter_subscribe_button_text' => [
		'type' => 'text',
		'label' => __( 'Button Label', 'blc' ),
		'design' => 'inline',
		'value' => __( 'Subscribe', 'blc' ),
		'disableRevertButton' => true,
	],

	'newsletter_subscribe_container' => [
		'label' => __( 'Container Type', 'blc' ),
		'type' => 'ct-select',
		'value' => 'default',
		'design' => 'inline',
		'disableRevertButton' => true,
		'choices' => [
			'default' => __( 'Default', 'blc' ),
			'boxed' => __( 'Boxed', 'blc' ),
		],
	],

	'newsletter_subscribe_alignment' => [
		'type' => 'ct-radio',
		'label' => __( 'Content Alignment', 'blc' ),
		'value' => 'left',
		'view' => 'text',
		'design' => 'inline',
		'attr' => [ 'data-type' => 'alignment' ],
		'disableRevertButton' => true,
		'choices' => [
			'left' => '',
			'center' => '',
			'right' => '',
		],
	],

];
