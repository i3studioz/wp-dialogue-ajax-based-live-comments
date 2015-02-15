var app = app || {};

app.CommentList = Backbone.Collection.extend({
    //which model

    model: app.Comment,
    actionURL: {
        'read': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=fetch_comment&post_id='+lc_vars.post_id+'&old_start='+lc_vars.old_start+'&new_start='+lc_vars.new_start+'&type=newer',
        'create': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=add_comment',
        'update': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=add_comment',
        'delete': 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=remove_comment'
    },
    sync: function(method, collection, options) {
        options = options || {};
        options.url = collection.actionURL[method.toLowerCase()];
        console.log(method.toLowerCase());
        return Backbone.sync.apply(this, arguments);
    },
});