var emoticons = [
    ['&lt;3',   '<i class="fa-heart fa fa-lg text-danger"></i>'],
    [';\\|', '<i class="fa-meh-o fa fa-lg"></i>'],
    [';\\(', '<i class="fa-frown-o fa fa-lg"></i>'],
    [';\\)', '<i class="fa-smile-o fa fa-lg"></i>'],
    [':\\|', '<i class="fa-meh-o fa fa-lg"></i>'],
    [':\\(', '<i class="fa-frown-o fa fa-lg"></i>'],
    [':\\)', '<i class="fa-smile-o fa fa-lg"></i>']
];

var gravatars = {
    'default': null,
    '404': '404', // (return a 404)
    'mm': 'mm', // (mysteryman)
    'identicon': 'identicon', // (unique, generated image)
    'monsterid': 'monsterid', // (unique, generated image)
    'wavatar': 'wavatar'    // (unique, generated image)
};

var defimg = gravatars['identicon'];

var currentChannel = 0;

function sendJsonRequest(url, data, successCallback, async) {

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
            if (resp.statusText.trim() === 'Forbiden') {
                location.reload();
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
        case 'leaveChannel':
        case 'joinChannel':
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

    sendJsonRequest(urls.users, {}, function (resp) {

        var selector = $('#users');
        selector.html(getUsers(resp.data));
    });

    sendJsonRequest(urls.channels, {}, function (resp) {

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
    data.formatDate = function () {
        return function (val, render) {
            return render(val).replace(/^(\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}).*$/, '$1');
        };
    };

    data.enhance = function () {
        return function (val, render) {
            var content = render(val);

            $(emoticons).each(function (i, e) {
                var pattern = new RegExp(e[0], 'g');
                var replacement = e[1];
                content = content.replace(pattern, replacement);
            });

            return content;
        };
    }

    var tmpl = '\
        {{#items.0}}<dl class="dl-horizontal">{{/items.0}}\n\
            {{#items}}\n\
                <dt class="text-primary">{{ createdBy }}</dt>\n\
                <dd>\n\
                    <div col="row">\n\
                        <div class="col-lg-12">{{#enhance}}{{ content }}{{/enhance}}</div>\n\
                    </div>\n\
                    <div col="row">\n\
                        <div class="col-lg-12 text-muted right">\n\
                            <em>{{#formatDate}}{{ createdAt.date }}{{/formatDate}}</em>\n\
                        </div>\n\
                    </div>\n\
                </dd>\n\
            {{/items}}\n\
        {{#items.0}}</dl>{{/items.0}}\n\
        {{^items}}\n\
            <div class="alert alert-info">\n\
                List is empty.\n\
            </div>\n\
        {{/items}}\n\
    ';

    return Mustache.render(tmpl, data);
}

function getAssignees(data) {

    var tmpl = '\
        {{#items.0}}<ul class="list-unstyled">{{/items.0}}\n\
            {{#items}}\n\
            <li class="text-muted row">\n\
                <div class="col-md-9">{{ usernameCanonical }}</div>\n\
                <div class="col-md-3">\n\
                <i class="fa-user fa fa-fw"></i>\n\
            </div>\n\
            {{/items}}\n\
        {{#items.0}}</li>{{/items.0}}\n\
        {{^items}}\n\
            <div class="alert alert-warning">\n\
                List is empty.\n\
            </div>\n\
        {{/items}}\n\
    ';

    return Mustache.render(tmpl, data);
}

function getChannels(data) {

    var tmpl = '\
        {{#items.0}}<ul class="nav nav-tabs">{{/items.0}}\n\
            {{#items}}\n\
                <li role="presentation">\n\
                    <a role="tab" data-toggle="tab" href="#{{ id }}">\n\
                        {{ name }}&nbsp;&nbsp;\n\
                        <span class="leave-channel" title="Leave the channel">\n\
                            <i class="fa-times-circle fa fa-lg"></i>\n\
                        </span>\n\
                    </a>\n\
                </li>\n\
            {{/items}}\n\
        {{#items.0}}</ul>{{/items.0}}\n\
        {{^items}}\n\
            <div class="alert alert-warning">\n\
                List is empty.\n\
            </div>\n\
        {{/items}}\n\
    ';

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

    var tmpl = '\
        {{#items.0}}<ul class="list-unstyled">{{/items.0}}\n\
            {{#items}}\n\
                <a href="{{#url}}{{id}}{{/url}}" class="{{#online}}text-success{{/online}}{{^online}}text-muted{{/online}}">\n\
                    <li class="row">\n\
                        <div class="col-md-3">\n\
                            <img src="{{ image }}&d={{ #defimg }}{{ /defimg }}"\n\
                                 alt="{{ username }}"\n\
                                 title="{{ username }}"\n\
                                 class="img-circle"/>\n\
                        </div>\n\
                        <div class="col-md-6"><strong>{{ username }}</strong></div>\n\
                        <div class="col-md-3">\n\
                            <i class="fa-circle{{^online}}-o{{/online}} fa fa-fw" aria-hidden="true"></i>\n\
                        </div>\n\
                    </li>\n\
                </a>\n\
            {{/items}}\n\
        {{#items.0}}</ul>{{/items.0}}\n\
        {{^items}}\n\
            <div class="alert alert-warning">\n\
                List is empty.\n\
            </div>\n\
        {{/items}}\n\
    ';

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

    sendJsonRequest(urls.messages, {}, function (resp) {
        var selector = $('#messages');
        selector.find('dl').remove();
        selector.append(getMessages(resp.data));

        selector.closest('.chat-body').removeClass('hidden');
    });

    sendJsonRequest(urls.assignees, {}, function (resp) {
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
                sendJsonRequest($(this).attr('href'), {}, synchronize);
            })
            ;

    $('#channels')
            .on('click', '.leave-channel', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var id = $(this).closest('a').attr('href').replace(/\D/g, '');
                currentChannel = parseInt(id);
                
                var leaveChannelUrl = getUrl('leaveChannel');
                
                location.href = leaveChannelUrl;
            })
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
