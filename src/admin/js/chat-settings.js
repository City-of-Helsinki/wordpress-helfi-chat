wp.domReady( () => {
    const { FormTokenField, CustomSelectControl } = wp.components;
    const { createRoot, createElement, useState } = wp.element;
    const { __ } = wp.i18n;

    function initChatSettingsPage({pages}) {
      const page = new URLSearchParams( window.location.search ).get( 'page' );

      if ( page === 'helsinki-chat-settings' ) {
        const chatSelection = document.getElementById( 'chat-selection' );
        chatSelection.onchange = function() {
            displayChatSettingsSection( this );
        };
        addPageSelectFormTokenFieldControl({
          id: 'chat-pages',
          pages: pages || [],
          selected: getChatPagesValueArray(),
        });

        initChatVisibilitySelector();
        displayChatSettingsSection(chatSelection);
      }
    }

    function displayChatSettingsSection(select) {
      hideAllChatSettingsSections();

      const selected = select.value;

      const chatSettingsSection = document.getElementsByClassName( 'helsinki-chat-section-chat-' + selected)[0];

      if ( chatSettingsSection !== undefined ) {
        chatSettingsSection.style.display = 'block';
      }
    }

    function hideAllChatSettingsSections() {
      const chatSettingsSections = document.getElementsByClassName( 'helsinki-chat-settings-section ' );

      for ( let i = 0; i < chatSettingsSections.length; i++ ) {
        chatSettingsSections[i].style.display = 'none';
      }
    }

    function addPageSelectFormTokenFieldControl({id, pages, selected}) {
      const options = [];
      const optionsNames = [];
      const optionsValues = [];
      const selectedOptionsNames = [];

      pages.forEach(({id, title}) => {
        options.push({label: title, value: id});
        optionsNames.push(title);
        optionsValues.push(id);

        if (selected.includes(id)) {
          selectedOptionsNames.push(title);
        }
      });

      var root = createRoot( document.getElementById( id + '-controller') );

      root.render( createElement(PageSelectFormTokenField, {
        id,
        selectedOptionsNames,
        optionsValues,
        optionsNames
      }) );
    }

    const PageSelectFormTokenField = ( { id, selectedOptionsNames, optionsValues, optionsNames } ) => {
      const [ value, setValue ] = useState( selectedOptionsNames );

      return createElement(FormTokenField, {
        label: __('Add a new page' , 'helsinki-chat'),
        id: id + '-control',
        suggestions: optionsNames,
        maxSuggestions: 10,
        value,
        __experimentalShowHowTo: false,
        __nextHasNoMarginBottom: true,
        onChange: ( values ) => {
            var updatedSelectedOptions = [];
            var updatedSelectedOptionsNames = [];
            values.map( ( name ) => {
                //find the id of the selected option
                const index = optionsNames.indexOf( name );
                if ( index !== -1 ) {
                    updatedSelectedOptions.push( optionsValues[index] );
                    updatedSelectedOptionsNames.push( name );
                }
            });
            //update component value
            updateChatPagesValue( updatedSelectedOptions );
            setValue( updatedSelectedOptionsNames );
        },
      });
    };

    function getChatPagesValueArray() {
      const chatPages = document.getElementById( 'chat-pages' );
      return chatPages.value.split(',')
        .filter(value => '' !== value)
        .map(id => parseInt(id, 10));
    }

    function updateChatPagesValue( value ) {
      const chatPages = document.getElementById( 'chat-pages' );
      chatPages.value = value;
    }

    function initChatVisibilitySelector() {
      // add an event listener to the select element #chat-visibility
      // when the value changes, update the visibility of the .helsinki-chat-settings-chat-pages class
      const chatVisibility = document.getElementById( 'chat-visibility' );
      chatVisibility.addEventListener( 'change', function() {
          const chatPages = document.getElementsByClassName( 'helsinki-chat-settings-chat-pages' );
          for ( let i = 0; i < chatPages.length; i++ ) {
              if ( chatVisibility.value === 'selected' ) {
                  chatPages[i].style.display = 'table-row';
              } else {
                  chatPages[i].style.display = 'none';
              }
          }
      });

      // trigger the change event to set the initial visibility
      chatVisibility.dispatchEvent( new Event( 'change' ) );
    }

    initChatSettingsPage(helsinkiChatSettings);
});
