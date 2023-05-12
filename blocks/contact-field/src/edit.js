/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
import { useSelect } from '@wordpress/data';
import { SelectControl } from '@wordpress/components';

export default function Edit( attributes ) {
	const blockProps = useBlockProps();
	const postId = attributes.context.postId;
	const toString = JSON.stringify(attributes);
	const postData = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecord( 'postType', 'vz-open-contact', postId, 'vz_phone' );
	}, [postId]);

	const postMeta = postData.meta;

	const fieldOptions = [
			{ value: 'vz_title', label: 'Title' },
			{ value: 'vz_name', label: 'Name' },
			{ value: 'vz_last_name', label: 'Last Name' },
			{ value: 'vz_address', label: 'Address' },
			{ value: 'vz_phone', label: 'Phone' },
			{ value: 'vz_social_media', label: 'Social Media' },
			{ value: 'vz_email', label: 'Email' },
			{ value: 'vz_external_links', label: 'External Linkx' },
	];

	const { field } = attributes.attributes;

	const onChangeField = (newField) => {
		attributes.setAttributes({ field: newField });
	};

	return (
		<p { ...blockProps }>
			<div className="vz-open-contact-field-control">
				<SelectControl
					label="Select a field"
					value={field}
					options={fieldOptions}
					onChange={onChangeField}
					/>
			</div>
			{ postMeta[field] }
		</p>
	);
}
