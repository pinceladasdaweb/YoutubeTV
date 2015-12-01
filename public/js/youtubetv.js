/*jslint browser: true, debug: true*/
/*global define, module, exports, fetch, console*/
(function (root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.YoutubeTv = factory();
    }
}(this, function () {
    "use strict";
    var YoutubeTv = function () {
        if (!this || !(this instanceof YoutubeTv)) {
            return new YoutubeTv();
        }

        this.content         = document.querySelector('#content');
        this.endpointBranded = './inc/branded.php';
        this.endpointShelf   = './inc/shelf.php';

        this.run();
    };

    YoutubeTv.prototype = {
        template: function (s, d) {
            var p;

            for (p in d) {
                if (d.hasOwnProperty(p)) {
                    s = s.replace(new RegExp('{' + p + '}', 'g'), d[p]);
                }
            }
            return s;
        },
        inject: function (target, html) {
            target.insertAdjacentHTML('beforeend', html);
        },
        empty: function (el) {
            while(el.hasChildNodes()) {
                el.removeChild(el.lastChild);
            }
        },
        timeAgo: function (time) {
            if (!time) {
                return;
            }

            var templates = {
                prefix: "",
                suffix: " ago",
                seconds: "less than a minute",
                minute: "about a minute",
                minutes: "%d minutes",
                hour: "about an hour",
                hours: "about %d hours",
                day: "a day",
                days: "%d days",
                month: "about a month",
                months: "%d months",
                year: "about a year",
                years: "%d years"
            },
                template = function (t, n) {
                    return templates[t] && templates[t].replace(/%d/i, Math.abs(Math.round(n)));
                }, now, seconds, minutes, hours, days, years;

            time = time.replace(/\.\d+/, "");
            time = time.replace(/-/, "/").replace(/-/, "/");
            time = time.replace(/T/, " ").replace(/Z/, " UTC");
            time = time.replace(/([\+\-]\d\d)\:?(\d\d)/, " $1$2");
            time = new Date(time * 1000 || time);

            now     = new Date();
            seconds = ((now.getTime() - time) * .001) >> 0;
            minutes = seconds / 60;
            hours   = minutes / 60;
            days    = hours / 24;
            years   = days / 365;

            return templates.prefix + (
                seconds < 45 && template('seconds', seconds) || seconds < 90 && template('minute', 1) || minutes < 45 && template('minutes', minutes) || minutes < 90 && template('hour', 1) || hours < 24 && template('hours', hours) || hours < 42 && template('day', 1) || days < 30 && template('days', days) || days < 45 && template('month', 1) || days < 365 && template('months', days / 30) || years < 1.5 && template('year', 1) || template('years', years)
            ) + templates.suffix;
        },
        formatNumber: function (num) {
            return ("" + num).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, function ($1) {
                return $1 + "."
            });
        },
        setHeight: function() {
            var cHeight = document.documentElement.clientHeight - 130;

            this.content.style.minHeight = cHeight + 'px';
        },
        watch: function (video) {
            var featured = document.querySelector('#featured iframe');

            featured.setAttribute('src', '//www.youtube.com/embed/' + video + '?vq=hd720&autoplay=1');
        },
        branded: function () {
            fetch(this.endpointBranded)
                .then(function (response) {
                    return response.text();
                })
                .then(function (responseText) {
                    this.content.innerHTML = responseText;
                }.bind(this))
                .then(function () {
                    this.shelf();
                }.bind(this))
                .catch(function (error) {
                    console.error(error.message);
                });
        },
        shelf: function () {
            var shelves = document.querySelectorAll('.shelf-item');

            Array.prototype.forEach.call(shelves, function (shelf) {
                this.mount(shelf, shelf.dataset.video);

                shelf.addEventListener('click', function (e) {
                    if (e.target && e.target.nodeName === 'DIV' || e.target.nodeName === 'H1'  || e.target.nodeName === 'P') {
                        scroll2Top(280, function () {
                            this.watch(shelf.dataset.video);
                        }.bind(this), 1400);
                    }
                }.bind(this), false);
            }.bind(this));
        },
        mount: function (el, id) {
            var request = this.endpointShelf + '?id=' + id, res;

            fetch(request)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    res = this.template(
                        '<div class="shelf-item-wrapper">' +
                            '<img class="shelf-item-image" src="{image}">' +
                            '<div class="shelf-item-info">' +
                                '<h1 class="shelf-item-title">{title}</h1>' +
                                '<p class="shelf-item-time">{duration}</p>' +
                                '<p class="shelf-item-statistics">{views} - {publishedAt}</p>' +
                            '</div>' +
                        '</div>', {
                            title: data.items[0].snippet.title,
                            image: data.items[0].snippet.thumbnails.standard.url,
                            duration: data.items[0].contentDetails.duration.replace('PT', ''),
                            publishedAt: this.timeAgo(data.items[0].snippet.publishedAt),
                            views: this.formatNumber(data.items[0].statistics.viewCount)
                        });

                    this.empty(el);
                    this.inject(el, res);
                }.bind(this))
                .catch(function (error) {
                    console.error(error.message);
                });
        },
        run: function () {
            this.setHeight();
            this.branded();
        }
    };

    return YoutubeTv;
}));