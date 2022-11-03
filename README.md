# Helsinki Chat
A plugin designed for integrating various chat services into the [Helsinki Theme](https://github.com/City-of-Helsinki/wordpress-helfi-helsinkiteema),

## Dependencies

### Required
- None

## Integrated Chat Services
- Genesys v9


## Configuration
After installation a chat service can be configured to be used from the `Helsinki Chat` admin submenu.

Available settings for a chat may differ between chat services. Currently the only chat service available is Genesys v9.

This plugin is not intended to provide the endpoints required to setup a chat service. You will have to contact the chat service provider for the required information to setup and use the chat.

## Development

### Assets
(S)CSS and JS source files are stored in `/src`. Asset complitation is done with [Gulp](https://gulpjs.com/) and the processed files can be found in `/assets`.

Install dependencies with `npm install`. Build assets with `gulp scripts` and `gulp styles` or watch changes with `gulp watch`.

## Collaboration
Raise [issues](https://github.com/City-of-Helsinki/wordpress-helfi-privatewebsite/issues) for found bugs or development ideas. Feel free to send [pull requests](https://github.com/City-of-Helsinki/wordpress-helfi-privatewebsite/pulls) for bugfixes and new or improved features.