"use strict";

wp.domReady(function () {
  function initChatSettingsPage() {
    var page = new URLSearchParams(window.location.search).get('page');

    if (page === 'helsinki-chat-settings') {
      var chatSelection = document.getElementById('chat-selection');

      chatSelection.onchange = function () {
        displayChatSettingsSection(this);
      };

      displayChatSettingsSection(chatSelection);
    }
  }

  function displayChatSettingsSection(select) {
    hideAllChatSettingsSections();
    var selected = select.value;
    var chatSettingsSection = document.getElementsByClassName('helsinki-chat-section-chat-' + selected)[0];

    if (chatSettingsSection !== undefined) {
      chatSettingsSection.style.display = 'block';
    }
  }

  function hideAllChatSettingsSections() {
    var chatSettingsSections = document.getElementsByClassName('helsinki-chat-settings-section ');

    for (var i = 0; i < chatSettingsSections.length; i++) {
      chatSettingsSections[i].style.display = 'none';
    }
  }

  initChatSettingsPage();
});