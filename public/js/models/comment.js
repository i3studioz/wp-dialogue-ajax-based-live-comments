"use strict";
var app = app || {};
// extending backbone model for comments model

app.Comment = Backbone.Model.extend({
    // default vars

    defaults: {
        comment_id: '',
        comment_parent: 0,
        comment_post_id: '',
        comment_class: '',
        author: '',
        email: '',
        website: '',
        avatar: '',
        avatar_size: 96,
        comment_post_link: '',
        comment_iso_time: '',
        comment_date: '',
        comment_date_readable: '',
        comment: '',
        moderation_required: true,
        reply_link : '',
        position: ''
    },
    idAttribute: 'comment_id',
    actionURL: {
        'read': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=fetch_comment',
        'create': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=add_comment',
        'update': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=add_comment',
        'delete': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=remove_comment'
    },
    sync: function(method, model, options) {
        options = options || {};
        options.url = model.actionURL[method.toLowerCase()];
        //console.log(method.toLowerCase());
        return Backbone.sync.apply(this, arguments);
    }
});