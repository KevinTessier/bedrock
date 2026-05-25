import { createElement, Fragment } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
  useBlockProps,
  RichText,
  InspectorControls
} from '@wordpress/block-editor';
import { PanelBody, TextControl, BlockQuotation } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

registerBlockType(metadata.name, {
  edit: ({ attributes, setAttributes }) => {
    const { quote, firstName, lastName } = attributes;

    const blockProps = useBlockProps({ className: 'sage-citation' });

    return (
      <>
        <InspectorControls>
          <PanelBody title={__('Attribution', 'sage')} initialOpen>
            <TextControl
              label={__('Prénom', 'sage')}
              value={firstName}
              onChange={(value) => setAttributes({ firstName: value })}
            />
            <TextControl
              label={__('Nom', 'sage')}
              value={lastName}
              onChange={(value) => setAttributes({ lastName: value })}
            />
          </PanelBody>
        </InspectorControls>

        <BlockQuotation { ...blockProps }>
            <RichText
              tagName="p"
              value={quote}
              onChange={(value) => setAttributes({ quote: value })}
              placeholder={__('Énoncer la citation…', 'sage')}
              allowedFormats={['core/bold', 'core/italic']}
            />

            <figcaption className="sage-citation__attribution">
              {firstName || lastName ? (
                <>
                  {firstName} {lastName}
                </>
              ) : (
                <span className="sage-citation__placeholder">
                  {__('Prénom Nom (panneau latéral)', 'sage')}
                </span>
              )}
            </figcaption>
        </BlockQuotation>

      </>
    );
  },
  save: () => null,
});
