/**
 * BLOCK: konfidoo
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';
import {InspectorControls} from '@wordpress/block-editor';
import {SelectControl, TextControl, PanelBody, PanelRow} from '@wordpress/components';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'cgb/block-konfidoo', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'konfidoo - Form Integration' ), // Block title.
	icon: 'forms', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'embed', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'konfidoo — Form Integration' ),
	],
	attributes: {
		type: {  enum: [ 'form' ], default: 'form' },
		projectId: { type: 'string', default: '' },
		configurationId: { type: 'string', default: '' },
		// configTitle: { type: 'string', default: '' },
		// configSubtitle: { type: 'string', default: '' },
		// configBtnText: { type: 'string', default: '' },
		// configBtnCls: { type: 'string', default: '' },
	},
	edit: ( {attributes, setAttributes} ) => {

		// Get global Project ID from wp_localize_script
		const globalProjectId = cgbGlobal && cgbGlobal.globalProjectId ? cgbGlobal.globalProjectId : '';
		
		// Use block-specific projectId or fall back to global
		const effectiveProjectId = attributes.projectId || globalProjectId;

		// const isSidebarOpened = wp.data.select( 'core/edit-post' ).isEditorSidebarOpened();
		//
		// if ( !isSidebarOpened ) {
		// 	wp.data.dispatch( 'core/edit-post' ).openGeneralSidebar();
		// }
		function openSettingsSidebar() {
			console.log('openSettingsSidebar')
			wp.data.dispatch("core/edit-post").openGeneralSidebar( 'edit-post/block' );
		}

		return (
			<div>
				<InspectorControls key="setting" initialOpen={true}>
					<PanelBody
						title="Konfiguration"
						initialOpen={true}
					>
						<PanelRow>
							<TextControl
								label="Project ID"
								value={ attributes.projectId }
								onChange={ ( value ) => setAttributes( { projectId: value } ) }
								placeholder={ globalProjectId ? `Global: ${globalProjectId}` : 'Enter Project ID or configure global setting' }
								help={ globalProjectId && !attributes.projectId ? `Using global Project ID: ${globalProjectId}` : '' }
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label="Configuration ID"
								value={ attributes.configurationId }
								onChange={ ( value ) => setAttributes( { configurationId: value } ) }
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody
						title="Layout"
						initialOpen={false}
					>
						<PanelRow>
							<SelectControl
								label="Art der Einbindung"
								value={ attributes.type }
								options={ [
									{ label: 'Normal', value: 'form' },
									// { label: 'Intro', value: 'intro' },
									// { label: 'Popup', value: 'modal' },
								] }
								onChange={ ( value ) => setAttributes( { type: value } ) }
								__nextHasNoMarginBottom
							/>
						</PanelRow>
							{ (attributes.type === "intro" || attributes.type === "modal") &&
								<div>
									<PanelRow>
									<TextControl
										label="Title"
										value={ attributes.configTitle }
										onChange={ ( value ) => setAttributes( { configTitle: value } ) }
									/>
									</PanelRow>
									<PanelRow>
									<TextControl
										label="Untertitel"
										value={ attributes.configSubtitle }
										onChange={ ( value ) => setAttributes( { configSubtitle: value } ) }
									/>
									</PanelRow>
									<PanelRow>
									<TextControl
										label="Button label"
										value={ attributes.configBtnText }
										onChange={ ( value ) => setAttributes( { configBtnText: value } ) }
									/>
									</PanelRow>
									<PanelRow>
									<TextControl
										label="Button css class"
										value={ attributes.configBtnCls }
										onChange={ ( value ) => setAttributes( { configBtnCls: value } ) }
									/>
									</PanelRow>
								</div>
							}
					</PanelBody>
				</InspectorControls>
				<div className="wp-block-kfd-placeholder">
					<div className="title">konfidoo</div>
					{ effectiveProjectId && attributes.configurationId && (
						<div>
							<strong>Project: </strong>
							{effectiveProjectId}
							<br/>
							<strong>Konfiguration: </strong>
							{attributes.configurationId}
							<br/>
							<a href={'https://dev.konfidoo.de/cms/mandant/' + effectiveProjectId} target="_blank">
								Projekt öffnen</a>
							{/*<button onClick={openSettingsSidebar}>*/}
							{/*	Activate Lasers*/}
							{/*</button>*/}
						</div>
						) }
					{ !effectiveProjectId && (
						<div>Bitte eine Projekt ID angeben { globalProjectId ? 'oder in den Plugin-Einstellungen konfigurieren' : '(keine globale ID konfiguriert)' }</div>
					) }
					{ !attributes.configurationId && (
						<div>Bitte eine Konfigurations ID angeben</div>
					) }
					{( !effectiveProjectId && ! !attributes.configurationId) && (
						<p>Open the settings sidebar to configure the integration</p>
					) }
				</div>
			</div>
		);
	},
	save: ( props ) => {
		// Get global Project ID from wp_localize_script
		const globalProjectId = cgbGlobal && cgbGlobal.globalProjectId ? cgbGlobal.globalProjectId : '';
		
		// Use block-specific projectId or fall back to global
		const effectiveProjectId = props.attributes.projectId || globalProjectId;
		
		if (!effectiveProjectId || !props.attributes.configurationId) {
			return;
		}

		if(props.attributes.type === "intro") {
			return (
				<div>
					<kfd-intro
						project={ effectiveProjectId }
						configuration={ props.attributes.configurationId }
						title={ props.attributes.configTitle }
						subtitle={ props.attributes.configSubtitle }
						btntext={ props.attributes.configBtnText }
						btncls={ props.attributes.configBtnCls }
						seamless="true"
					></kfd-intro>
				</div>
			);
		}

		if(props.attributes.type === "modal") {
			return (
				<div>
					<kfd-modal
						project={ effectiveProjectId }
						configuration={ props.attributes.configurationId }
						title={ props.attributes.configTitle }
						subtitle={ props.attributes.configSubtitle }
						btntext={ props.attributes.configBtnText }
						btncls={ props.attributes.configBtnCls }
						seamless="true"
					></kfd-modal>
				</div>
			);
		}

		return (
			<div>
				<kfd-inline
					project={ effectiveProjectId }
					configuration={ props.attributes.configurationId }
					seamless="true"
				></kfd-inline>
			</div>
		);
	},
} );
