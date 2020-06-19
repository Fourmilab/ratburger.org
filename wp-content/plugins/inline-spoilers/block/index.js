/**
 * @package Inline Spoilers
 */

( function( blocks, editor, i18n, element, components, _ ) {
  var __ = i18n.__;
  var element = element.createElement;

  const { RichText } = wp.editor;

  var visibleTitle = {};

  blocks.registerBlockType( 'inline-spoilers/block', {
    title: __( 'Inline Spoiler', 'inline-spoilers' ),
    icon: 'hidden',
    category: 'formatting',
    attributes: {
      title: {
        type: 'array',
        selector: '.spoiler-head',
        source: 'children'
      },
      content: {
        type: 'array',
        selector: '.spoiler-body',
        source: 'children'
      },
      initial_state: {
        type: 'string',
        default: 'collapsed'
      }
    },

    edit: function( props ) {
      var UID = props.clientId;
      var { title, content } = props.attributes;

      // Prevent spoiler 'visible content'/'value' mismatch,
      // since visible & state texts are different items.
      if(!visibleTitle[UID]) {
        visibleTitle[UID] = title;
      }

      return (
        element( 'div', { className: props.className },
          element( 'div', {
            class: 'spoiler-title',
            contenteditable: 'true',
            onInput: function( event ) {
              props.setAttributes( { title: event.target.innerText } )
            }
          }, visibleTitle[UID] ),
          element( 'div', { class: 'spoiler-content' },
            element( RichText, {
              placeholder: __( 'Spoiler content', 'inline-spoilers' ),
              value: content,
              onChange: function( value ) {
                props.setAttributes( { content: value } );
              }
            })
          ),
        )
      );
    },

    save: function( props ) {
      var { title, content } = props.attributes;

      return (
        element( 'div', null,
          element( 'div', { class: 'spoiler-wrap' },
            element( 'div', { class: 'spoiler-head collapsed', title: 'Expand' },
              title.length ? title : '&nbsp;'
            ),
            element( RichText.Content, { tagName: 'div', className: 'spoiler-body', style: { display: 'none' }, value: content } ),
            element( 'noscript', null,
              element( RichText.Content, { tagName: 'div', className: 'spoiler-body', value: content } )
            )
          )
        )
      );
    },
  });
})(
  window.wp.blocks,
  window.wp.editor,
  window.wp.i18n,
  window.wp.element,
  window.wp.components,
  window._,
);
