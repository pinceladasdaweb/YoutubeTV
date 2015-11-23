# YoutubeTV
> Experiment with the Youtube API using PHP and JavaScript

![](https://raw.github.com/pinceladasdaweb/YoutubeTV/master/screenshot.jpg)

## What is it?

YoutubeTV is a experiment with the Youtube API using PHP and JavaScript (without frameworks), giving you an option to assemble a portfolio using your Youtube channel.

## Demo
View demo [here](http://www.pinceladasdaweb.com.br/blog/uploads/youtubetv/).

## Getting Started

```bash
# Get the latest snapshot
$ git clone git@github.com:pinceladasdaweb/YoutubeTV.git
```
## How to use?

Open the config.php [`config.php`](config/config.php) file and fill with your informations.

```php
<?php

return [
    'youtube' => [
        'apiKey' => 'Your APY KEY here', // Your API KEY
        'user'   => 'Youtube user', // Youtube user to display videos
        'maxResults' => 13 // Number of videos to display
    ]
];
```

Here's how to get your API KEY: [https://developers.google.com/youtube/v3/getting-started](https://developers.google.com/youtube/v3/getting-started).

## Browser Support

![IE](https://raw.githubusercontent.com/alrra/browser-logos/master/internet-explorer/internet-explorer_48x48.png) | ![Chrome](https://raw.githubusercontent.com/alrra/browser-logos/master/chrome/chrome_48x48.png) | ![Firefox](https://raw.githubusercontent.com/alrra/browser-logos/master/firefox/firefox_48x48.png) | ![Opera](https://raw.githubusercontent.com/alrra/browser-logos/master/opera/opera_48x48.png) | ![Safari](https://raw.githubusercontent.com/alrra/browser-logos/master/safari/safari_48x48.png)
--- | --- | --- | --- | --- |
IE 10+ ✔ | Latest ✔ | Latest ✔ | Latest ✔ | Latest ✔ |

## Contributing

Check [CONTRIBUTING.md](CONTRIBUTING.md) for more information.

## History

Check [Releases](https://github.com/pinceladasdaweb/YoutubeTV/releases) for detailed changelog.

## License
[MIT](LICENSE)

## To do

- [x] Add support for older browsers.
- [ ] Scroll to top after click on video thumbnail.
- [ ] Button to display more videos.