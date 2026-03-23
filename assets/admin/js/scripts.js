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
  var _wp$element = wp.element,
    createRoot = _wp$element.createRoot,
    createElement = _wp$element.createElement,
    useState = _wp$element.useState;
  var __ = wp.i18n.__;
  function initChatSettingsPage(_ref) {
    var pages = _ref.pages;
    var page = new URLSearchParams(window.location.search).get('page');
    if (page === 'helsinki-chat-settings') {
      var chatSelection = document.getElementById('chat-selection');
      chatSelection.onchange = function () {
        displayChatSettingsSection(this);
      };
      addPageSelectFormTokenFieldControl({
        id: 'chat-pages',
        pages: pages || [],
        selected: getChatPagesValueArray()
      });
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
  function addPageSelectFormTokenFieldControl(_ref2) {
    var id = _ref2.id,
      pages = _ref2.pages,
      selected = _ref2.selected;
    var options = [];
    var optionsNames = [];
    var optionsValues = [];
    var selectedOptionsNames = [];
    pages.forEach(function (_ref3) {
      var id = _ref3.id,
        title = _ref3.title;
      options.push({
        label: title,
        value: id
      });
      optionsNames.push(title);
      optionsValues.push(id);
      if (selected.includes(id)) {
        selectedOptionsNames.push(title);
      }
    });
    var root = createRoot(document.getElementById(id + '-controller'));
    root.render(createElement(PageSelectFormTokenField, {
      id: id,
      selectedOptionsNames: selectedOptionsNames,
      optionsValues: optionsValues,
      optionsNames: optionsNames
    }));
  }
  var PageSelectFormTokenField = function PageSelectFormTokenField(_ref4) {
    var id = _ref4.id,
      selectedOptionsNames = _ref4.selectedOptionsNames,
      optionsValues = _ref4.optionsValues,
      optionsNames = _ref4.optionsNames;
    var _useState = useState(selectedOptionsNames),
      _useState2 = _slicedToArray(_useState, 2),
      value = _useState2[0],
      setValue = _useState2[1];
    return createElement(FormTokenField, {
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
    return chatPages.value.split(',').filter(function (value) {
      return '' !== value;
    }).map(function (id) {
      return parseInt(id, 10);
    });
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
  initChatSettingsPage(helsinkiChatSettings);
});