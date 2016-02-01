
var currentChannel = 0;

var gravatars = {
    'default': null,
    '404': '404', // (return a 404)
    'mm': 'mm', // (mysteryman)
    'identicon': 'identicon', // (unique, generated image)
    'monsterid': 'monsterid', // (unique, generated image)
    'wavatar': 'wavatar'    // (unique, generated image)
};

var defimg = gravatars['identicon'];


function sendJsonp(url, data, successCallback, async) {

    if (typeof async === 'undefined') {
        async = true;
    }

    successCallback = successCallback || function (data) {};

    return $.ajax({
        dataType: 'jsonp',
        url: url,
        cache: false,
        async: async,
        data: {
        },
        success: function (resp) {
            if (resp.success) {
                successCallback(resp);
            } else {
                if (resp.type == 'error') {
                    var msg = resp.msg;
                    if (resp.data.messages !== undefined) {
                        msg = resp.data.messages.join("\n")
                    }
                    alert('ERROR:\n' + msg);
                } else {
                    alert('WARNING:\n' + resp.msg);
                }
            }
        },
        error: function (resp) {
            if (resp.statusText == 'Forbiden') {
                spinner();
                location.href = Routing.generate('fos_user_security_logout');
                return;
            }

            alert('ERROR:\n' + resp.statusText);
        }
    });
}

function getUrl(name, data) {
    data = data || {};
    var url = null;

    switch (name) {
        case 'users':
        case 'channels':
            url = urlsTmpl[name];
            break;
        case 'createChannel':
            url = urlsTmpl[name].replace('%5B1%5D', JSON.stringify([parseInt(data)]));
            break;
        case 'messages':
        case 'sendMessage':
        case 'assignees':
            url = urlsTmpl[name].replace(/\/1\//, '/' + currentChannel + '/');
            break;
    }

    return url;
}

function synchronize() {

    var urls = {
        users: getUrl('users'),
        channels: getUrl('channels'),
    };

    sendJsonp(urls.users, {}, function (resp) {

        var selector = $('#users');
        selector.html(getUsers(resp.data));
    });

    sendJsonp(urls.channels, {}, function (resp) {

        var selector = $('#channels');
        selector.find('*').remove();
        selector.append(getChannels(resp.data));

        if (currentChannel > 0) {
            var tabsel = Mustache.render('.nav.nav-tabs li a[href="#{{id}}"]', {id: currentChannel});
        } else {
            var tabsel = '.nav.nav-tabs li:first-child a';
        }

        var tab = selector.find(tabsel);

        if (tab.length > 0) {
            tab.trigger('click');
            currentChannel = tab.attr('href').replace(/\D+/g, '');
            currentChannel = parseInt(currentChannel);
        }
    });

    updateChannel();
}

function getMessages(data) {

    var tmpl = '<dl class="dl-horizontal">\n\
                                {{#items}}\n\
                                    <dt class="text-primary">{{ createdBy }}</dt>\n\
                                    <dd>\n\
                                        {{ content }}<br/>\n\
                                        <div class="text-muted right">\n\
                                            <em>{{ createdAt }}</em>\n\
                                        </div>\n\
                                    </dd>\n\
                                {{/items}}\n\
                            </dl>';

    return Mustache.render(tmpl, data);
}

function getAssignees(data) {
    var tmpl = '<ul class="list-unstyled">\n\
                                {{#items}}\n\
                                <li class="text-muted row">\n\
                                    <div class="col-md-9">{{ usernameCanonical }}</div>\n\
                                    <div class="col-md-3">\n\
                                    <i class="fa-user fa fa-fw"></i>\n\
                                </div>\n\
                                {{/items}}\n\
                            </li>'
            ;

    return Mustache.render(tmpl, data);
}

function getChannels(data) {

    var tmpl = '<ul class="nav nav-tabs">\n\
                                {{#items}}\n\
                                <li role="presentation">\n\
                                    <a role="tab" data-toggle="tab" href="#{{ id }}">{{ name }}</a>\n\
                                </li>\n\
                                {{/items}}\n\
                             </ul>';

    return Mustache.render(tmpl, data);
}

function getUsers(data) {

    data.url = function () {
        return function (val, render) {
            return getUrl('createChannel', render(val));
        };
    };

    data.defimg = function () {
        return function (val, render) {
            return defimg;
        };
    };

    var tmpl = '<ul class="list-unstyled">\n\
                        {{#items}}\n\
                                {{ #online }}\n\
                                <a href="{{#url}}{{id}}{{/url}}" class="text-success">\n\
                                <li class="row">\n\
                                    <div class="col-md-3">\n\
                                        <img src="{{ image }}&d={{ #defimg }}{{ /defimg }}" alt="{{ username }}" alt="{{ username }}" class="img-circle"/>\n\
                                    </div>\n\
                                    <div class="col-md-6"><strong>{{ username }}</strong></div>\n\
                                    <div class="col-md-3"><i class="fa-circle fa fa-fw" aria-hidden="true"></i></div>\n\
                                </li>\n\
                                </a>\n\
                            {{ /online }}\n\
                            \n\
                            {{ ^online }}\n\
                                <a href="{{#url}}{{id}}{{/url}}" class="text-muted">\n\
                                <li class="row">\n\
                                    <div class="col-md-3">\n\
                                        <img src="{{ image }}&d={{ #defimg }}{{ /defimg }}" alt="{{ username }}" title="{{ username }}" class="img-circle"/>\n\
                                    </div>\n\
                                    <div class="col-md-6">{{ username }}</div>\n\
                                    <div class="col-md-3"><i class="fa-circle-o fa fa-fw" aria-hidden="true"></i></div>\n\
                                </li>\n\
                                </a>\n\
                            {{ /online }}\n\
                        {{/items}}\n\
                        </ul>'
            ;

    return Mustache.render(tmpl, data);
}

function updateChannel() {

    if (currentChannel == 0) {
        return;
    }

    var urls = {
        messages: getUrl('messages'),
        assignees: getUrl('assignees'),
    };

    sendJsonp(urls.messages, {}, function (resp) {
        var selector = $('#messages');
        selector.find('dl').remove();
        selector.append(getMessages(resp.data));

        selector.closest('.chat-body').removeClass('hidden');
    });

    sendJsonp(urls.assignees, {}, function (resp) {
        var selector = $('#assignees');
        selector.find('*').remove();
        selector.append(getAssignees(resp.data));
    });
}

$(document).ready(function () {

    $('#messages form')
            .on('submit', function (e) {
                var control = '#messages form *[name=message]';
                var tempControl = '#messages form .temp-input';
                var value = $(tempControl).val().trim();
                $(control).val(value);
                $(tempControl).attr('disabled', true);
            })
            .ajaxForm(function (resp) {
                var channel = resp.data.channel;

                updateChannel();

                var tempControl = '#messages form .temp-input';
                $(tempControl).val('').removeAttr('disabled');
            })
            ;

    $('#users')
            .on('click', 'a', function (e) {
                e.preventDefault();
                sendJsonp($(this).attr('href'), {}, synchronize);
            })
            ;

    $('#channels')
            .on('click', 'li a', function (e) {

                e.preventDefault();
                var self = $(this);

                self.blur();

                currentChannel = self.attr('href').replace(/\D+/g, '');

                sendMessageUrl = getUrl('sendMessage');

                var form = $('#messages form');
                form.attr('action', sendMessageUrl);
                form.find('button[type=submit]').removeAttr('disabled');


                updateChannel();


            })
            ;

    synchronize();
    setInterval(synchronize, refreshAfter * 1000);
});
