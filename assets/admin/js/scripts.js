"use strict";

function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
wp.domReady(function () {
  var _wp$components = wp.components,
    FormTokenField = _wp$components.FormTokenField,
    CustomSelectControl = _wp$components.CustomSelectControl;
  var useState = wp.element.useState;
  var __ = wp.i18n.__;
  function initChatSettingsPage() {
    var page = new URLSearchParams(window.location.search).get('page');
    if (page === 'helsinki-chat-settings') {
      var chatSelection = document.getElementById('chat-selection');
      chatSelection.onchange = function () {
        displayChatSettingsSection(this);
      };
      addPageSelectFormTokenFieldControl('chat-pages', getChatPagesValueArray());
      initChatVisibilitySelector();
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
  function addPageSelectFormTokenFieldControl(id, selectedOptions) {
    var options = [];
    for (var i = 0; i < window.helsinkiChatSettings.pages.length; i++) {
      options.push({
        label: window.helsinkiChatSettings.pages[i].title,
        value: window.helsinkiChatSettings.pages[i].id
      });
    }
    var optionsNames = [];
    var optionsValues = [];
    for (var _i = 0; _i < window.helsinkiChatSettings.pages.length; _i++) {
      optionsNames.push(window.helsinkiChatSettings.pages[_i].title);
      optionsValues.push(window.helsinkiChatSettings.pages[_i].id);
    }
    //create an array with selectedOptions titles that match the optionsNames
    var selectedOptionsNames = [];
    for (var _i2 = 0; _i2 < selectedOptions.length; _i2++) {
      var index = optionsValues.indexOf(parseInt(selectedOptions[_i2]));
      if (index !== -1) {
        selectedOptionsNames.push(optionsNames[index]);
      }
    }
    var root = wp.element.createRoot(document.getElementById(id + '-controller'));
    root.render(wp.element.createElement(PageSelectFormTokenField, {
      id: id,
      selectedOptionsNames: selectedOptionsNames,
      optionsValues: optionsValues,
      optionsNames: optionsNames
    }));
  }
  var PageSelectFormTokenField = function PageSelectFormTokenField(_ref) {
    var id = _ref.id,
      selectedOptionsNames = _ref.selectedOptionsNames,
      optionsValues = _ref.optionsValues,
      optionsNames = _ref.optionsNames;
    var _useState = useState(selectedOptionsNames),
      _useState2 = _slicedToArray(_useState, 2),
      value = _useState2[0],
      setValue = _useState2[1];
    return wp.element.createElement(FormTokenField, {
      label: __('Add a new page', 'helsinki-chat'),
      id: id + '-control',
      suggestions: optionsNames,
      maxSuggestions: 10,
      value: value,
      __experimentalShowHowTo: false,
      __nextHasNoMarginBottom: true,
      onChange: function onChange(values) {
        var updatedSelectedOptions = [];
        var updatedSelectedOptionsNames = [];
        values.map(function (name) {
          //find the id of the selected option
          var index = optionsNames.indexOf(name);
          if (index !== -1) {
            updatedSelectedOptions.push(optionsValues[index]);
            updatedSelectedOptionsNames.push(name);
          }
        });
        //update component value
        updateChatPagesValue(updatedSelectedOptions);
        setValue(updatedSelectedOptionsNames);
      }
    });
  };
  function getChatPagesValueArray() {
    var chatPages = document.getElementById('chat-pages');
    return chatPages.value.split(',');
  }
  function updateChatPagesValue(value) {
    var chatPages = document.getElementById('chat-pages');
    chatPages.value = value;
  }
  function initChatVisibilitySelector() {
    // add an event listener to the select element #chat-visibility
    // when the value changes, update the visibility of the .helsinki-chat-settings-chat-pages class
    var chatVisibility = document.getElementById('chat-visibility');
    chatVisibility.addEventListener('change', function () {
      var chatPages = document.getElementsByClassName('helsinki-chat-settings-chat-pages');
      for (var i = 0; i < chatPages.length; i++) {
        if (chatVisibility.value === 'selected') {
          chatPages[i].style.display = 'table-row';
        } else {
          chatPages[i].style.display = 'none';
        }
      }
    });

    // trigger the change event to set the initial visibility
    chatVisibility.dispatchEvent(new Event('change'));
  }

  /*function onChatPageChange( value ) {
      const chatPage = document.getElementById( 'chat-page' );
      chatPage.value = value;
  }*/

  initChatSettingsPage();
});