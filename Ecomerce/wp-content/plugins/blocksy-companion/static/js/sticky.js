import ctEvents from 'ct-events'
import { registerDynamicChunk } from 'blocksy-frontend'
import { mountStickyHeader } from './frontend/sticky'

mountStickyHeader()

registerDynamicChunk('blocksy_sticky_header', {
	mount: (el) => {},
})
