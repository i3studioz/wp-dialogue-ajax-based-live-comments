var app = app || {};
var $ = jQuery;
app.CommentView = Backbone.View.extend({
    el: $('#comments'),
    template: _.template($('#comments-template').html()),
    new_template: _.template($('#new-comments').html()),
    events: {
        'submit form#commentform': 'saveComment',
        'click #load-new-comments': 'addNewComments',
        'click #load-old-comments': 'addPagedComments'
    },
    initialize: function (app_vars) {
        _.bindAll(this, 'render', 'saveComment', 'appendItem', 'getLiveComments');

        this.$comment = this.$('#comment');
        this.$author = this.$('#author');
        this.$parent = this.$('#comment_parent');
        this.$email = this.$('#email');
        this.$website = this.$('#url');
        this.$comment_post_ID = this.$('#comment_post_ID');
        var comments_json = app_vars.db_comments; //$.parseJSON(app_vars.db_comments);
        this.collection = new app.CommentList(comments_json);
        this.collection.bind('add', this.appendItem);
        this.initializeLiveVars();
        this.initializePageVars();
        this.collection.meta('read_post', lc_vars.post_id);
        this.collection.meta('read_type', 'newer');

        this.counter = 0;
        var self = this;
        this.liveLoader = setInterval(function () {
            self.getLiveComments();
        }, 3000);

        this.render();
    },
    render: function () {

        var self = this;
        _(this.collection.models).each(function (comment) {
            self.appendItem(comment);
        }, this);

    },
    getLiveComments: function () {
        //console.log('here');
        var self = this;
        this.collection.fetch({
            remove: false,
            silent: true,
            success: function (collection, response) {
                $('#load-new-comments').html(self.new_template({count: response.length}))
                //console.log(response.length);
            },
            error: function (collection, response) {
                console.log(response);
            }
        });
    },
    addPagedComments: function () {

        clearInterval(this.liveLoader);

        this.initializePageVars();

        //console.log(oldest_comment.get('comment_date'));
        //console.log('here');
        var self = this;
        this.collection.fetch({
            remove: false,
            success: function (collection, response) {
                // console.log(response.length);
            },
            error: function (collection, response) {
                console.log(response);
            }
        });
    },
    getFirstModel: function () {
        if (lc_vars.comment_order == 'asc') {
            var first = this.collection.at(0);
        } else {
            var first = this.collection.at(this.collection.length - 1);
        }
        return first;

    },
    getLastModel: function () {
        if (lc_vars.comment_order == 'asc') {
            var last = this.collection.at(this.collection.length - 1);
        } else {
            var last = this.collection.at(0);
        }
        
        return last;

    },
    getAttributes: function () {

        return {
            comment_post_id: this.$comment_post_ID.val().trim(),
            comment_parent: this.$parent.val().trim(),
            comment_class: '',
            author: this.$author.val().trim(),
            email: this.$email.val().trim(),
            website: this.$website.val().trim(),
            avatar: '',
            avatar_size: 96,
            comment_post_link: '',
            comment_iso_time: '',
            comment_date: '',
            comment_date_readable: '',
            comment: this.$comment.val().trim(),
            moderation_required: true,
            reply_link: '',
            position: ''
        };

    },
    saveComment: function (e) {

        e.preventDefault();

        this.counter++;

        var self = this;
        var new_comment = new app.Comment();
        new_comment.save(this.getAttributes(),
                {
                    wait: true,
                    success: function (model, response) {
                        console.log(response);
                        if (response.error && response.error.length > 0) {
                            $('<div/>').addClass("alert alert-danger")
                                    .html(response.error)
                                    .prependTo($("#respond"))
                                    .hide()
                                    .fadeIn(1000)
                                    .delay(3000)
                                    .fadeOut(function () {
                                        $(this).remove()
                                    });
                        } else {
                            var comment_json = new_comment.toJSON();
                            self.collection.add(comment_json);
                            window.location.hash = 'comment-' + comment_json.comment_id;
                        }
                    }
                }
        );

    },
    appendItem: function (item) {

        var item_json = item.toJSON();
        //console.log(item_json);
        var type = item_json.position;
        if (item_json.comment_parent != 0 && $('#comment-' + item_json.comment_parent).length > 0 && lc_vars.thread_comments == 1) {
            $('#comment-' + item_json.comment_parent + ' > ol.children', this.el).append(this.template(item_json));
        } else if (type == 'old') {
            //lc_vars.comment_default_position == 'append' && 
            $('ol.comment-list', this.el).append(this.template(item_json));
        } else if (type == 'new') {
            //lc_vars.comment_default_position == 'append' && 
            $('ol.comment-list', this.el).prepend(this.template(item_json));

            var $old_border = $('#comment-' + item_json.comment_id).css('border');
            $('#comment-' + item_json.comment_id).css('border', '1px solid ' + lc_vars.new_item_color);

            setTimeout(function () {
                $('#comment-' + item_json.comment_id).css('border', $old_border);
            }, 5000);
        } else {
            $('ol.comment-list', this.el).append(this.template(item_json));
        }
    },
    addNewComments: function () {
        var self = this;
        _(this.collection.models).each(function (comment) {
            var comment_json = comment.toJSON();
            if (comment_json.comment_id > this.collection.meta('new_start')) {
                self.appendItem(comment);
            }
        }, this);
        this.initializeLiveVars();
    },
    initializeLiveVars: function () {
        var first = this.getFirstModel();
        this.collection.meta('read_type', 'newer');
        this.collection.meta('read_start', first.get('comment_date'));
        this.collection.meta('new_start', first.get('comment_id'));

        $('#new-comment-stat').html('');
    },
    initializePageVars: function () {
        var last = this.getLastModel();
        this.collection.meta('read_type', 'older');
        this.collection.meta('read_start', last.get('comment_date'));
        this.collection.meta('old_start', last.get('comment_id'));
    }
});