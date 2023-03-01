wp.domReady( () => {

    function initChatSettingsPage() {
        const page = new URLSearchParams( window.location.search ).get( 'page' );

        if ( page === 'helsinki-chat-settings' ) {
            const chatSelection = document.getElementById( 'chat-selection' );
            chatSelection.onchange = function() {
                displayChatSettingsSection( this );
            };
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

    initChatSettingsPage();
});