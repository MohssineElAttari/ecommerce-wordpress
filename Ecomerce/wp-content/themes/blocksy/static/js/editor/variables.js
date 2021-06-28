import { handleBackgroundOptionFor } from '../customizer/sync/variables/background'
import { withKeys } from '../customizer/sync/helpers'
import { maybePromoteScalarValueIntoResponsive } from 'customizer-sync-helpers/dist/promote-into-responsive'

const isContentBlock = document.body.classList.contains(
	'post-type-ct_content_block'
)

export const gutenbergVariables = {
	...handleBackgroundOptionFor({
		id: 'background',
		selector: '.editor-styles-wrapper',
		responsive: true,
		addToDescriptors: {
			fullValue: true,
		},
		valueExtractor: ({ background }) => {
			if (
				!isContentBlock &&
				background.background_type === 'color' &&
				background.backgroundColor.default.color &&
				background.backgroundColor.default.color.indexOf(
					'CT_CSS_SKIP_RULE'
				) > -1
			) {
				return ct_editor_localizations.default_background
			}

			return background
		},
	}),

	...withKeys(
		[
			'content_style_source',
			'content_style',
			'content_background',
			'content_boxed_shadow',
			'boxed_content_spacing',
			'content_boxed_radius',

			...(isContentBlock ? ['has_content_block_structure'] : []),
		],
		[
			{
				selector: `.block-editor-writing-flow`,
				variable: 'has-boxed',
				responsive: true,
				extractValue: ({
					content_style_source = 'inherit',
					has_content_block_structure,
					content_style = 'wide',
				}) => {
					if (!isContentBlock && content_style_source === 'inherit') {
						content_style =
							ct_editor_localizations.default_content_style
					}

					content_style = maybePromoteScalarValueIntoResponsive(
						content_style
					)

					if (
						isContentBlock &&
						has_content_block_structure !== 'yes'
					) {
						content_style = {
							desktop: 'wide',
							tablet: 'wide',
							mobile: 'wide',
						}
					}

					return {
						desktop:
							content_style.desktop === 'boxed'
								? 'var(--true)'
								: 'var(--false)',

						tablet:
							content_style.tablet === 'boxed'
								? 'var(--true)'
								: 'var(--false)',

						mobile:
							content_style.mobile === 'boxed'
								? 'var(--true)'
								: 'var(--false)',
					}
				},
				fullValue: true,
				unit: '',
			},

			{
				selector: `.block-editor-writing-flow`,
				variable: 'has-wide',
				responsive: true,
				extractValue: ({
					has_content_block_structure,
					content_style_source = 'inherit',
					content_style = 'wide',
				}) => {
					if (!isContentBlock && content_style_source === 'inherit') {
						content_style =
							ct_editor_localizations.default_content_style
					}

					content_style = maybePromoteScalarValueIntoResponsive(
						content_style
					)

					if (
						isContentBlock &&
						has_content_block_structure !== 'yes'
					) {
						content_style = {
							desktop: 'wide',
							tablet: 'wide',
							mobile: 'wide',
						}
					}

					return {
						desktop:
							content_style.desktop === 'wide'
								? 'var(--true)'
								: 'var(--false)',

						tablet:
							content_style.tablet === 'wide'
								? 'var(--true)'
								: 'var(--false)',

						mobile:
							content_style.mobile === 'wide'
								? 'var(--true)'
								: 'var(--false)',
					}
				},
				fullValue: true,
				unit: '',
			},

			...handleBackgroundOptionFor({
				id: 'background',
				selector: '.block-editor-writing-flow',
				responsive: true,
				addToDescriptors: {
					fullValue: true,
				},
				valueExtractor: ({
					has_content_block_structure,
					content_style_source = 'inherit',
					content_background,
				}) => {
					if (!isContentBlock && content_style_source === 'inherit') {
						content_background =
							ct_editor_localizations.default_content_background
					}

					if (
						isContentBlock &&
						has_content_block_structure !== 'yes'
					) {
						content_background = JSON.parse(
							JSON.stringify(
								maybePromoteScalarValueIntoResponsive(
									content_background
								)
							)
						)

						content_background.desktop.background_type = 'color'
						content_background.desktop.backgroundColor.default.color =
							'CT_CSS_SKIP_RULE'

						content_background.tablet.background_type = 'color'
						content_background.tablet.backgroundColor.default.color =
							'CT_CSS_SKIP_RULE'

						content_background.mobile.background_type = 'color'
						content_background.mobile.backgroundColor.default.color =
							'CT_CSS_SKIP_RULE'
					}

					return content_background
				},
			}).background,

			{
				selector: '.block-editor-writing-flow',
				type: 'spacing',
				variable: 'boxed-content-spacing',
				responsive: true,
				unit: '',
				fullValue: true,
				extractValue: ({
					content_style_source = 'inherit',
					boxed_content_spacing,
					has_content_block_structure,
				}) => {
					if (!isContentBlock && content_style_source === 'inherit') {
						boxed_content_spacing =
							ct_editor_localizations.default_boxed_content_spacing
					}

					if (
						isContentBlock &&
						has_content_block_structure !== 'yes'
					) {
						return 'CT_CSS_SKIP_RULE'
					}

					return boxed_content_spacing
				},
			},

			{
				selector: '.block-editor-writing-flow',
				type: 'spacing',
				variable: 'border-radius',
				responsive: true,

				fullValue: true,
				extractValue: ({
					content_style_source = 'inherit',
					content_boxed_radius,
					has_content_block_structure,
				}) => {
					if (!isContentBlock && content_style_source === 'inherit') {
						content_boxed_radius =
							ct_editor_localizations.default_content_boxed_radius
					}

					if (
						isContentBlock &&
						has_content_block_structure !== 'yes'
					) {
						return 'CT_CSS_SKIP_RULE'
					}

					return content_boxed_radius
				},
			},

			{
				selector: '.block-editor-writing-flow',
				type: 'box-shadow',
				variable: 'box-shadow',
				responsive: true,
				fullValue: true,
				extractValue: ({
					content_style_source = 'inherit',
					content_boxed_shadow,
					has_content_block_structure,
				}) => {
					if (!isContentBlock && content_style_source === 'inherit') {
						content_boxed_shadow =
							ct_editor_localizations.default_content_boxed_shadow
					}

					if (
						isContentBlock &&
						has_content_block_structure !== 'yes'
					) {
						return 'CT_CSS_SKIP_RULE'
					}

					return content_boxed_shadow
				},
			},
		]
	),
}
