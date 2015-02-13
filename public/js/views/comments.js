var app = app || {};
var $ = jQuery;
app.CommentView = Backbone.View.extend({
    el: $('#comments'),
    template: _.template($('#comments-template').html()),
    events: {
        'submit form#commentform': 'saveComment'
    },
    initialize: function(app_vars) {
        _.bindAll(this, 'render', 'saveComment', 'appendItem');

        this.$comment = this.$('#comment');
        this.$author = this.$('#author');
        this.$parent = this.$('#comment_parent');
        this.$email = this.$('#email');
        this.$website = this.$('#url');
        this.$comment_post_ID = this.$('#comment_post_ID');
        var comments_json = app_vars.db_comments; //$.parseJSON(app_vars.db_comments);
        this.collection = new app.CommentList(comments_json);
        this.collection.bind('add', this.appendItem);

        this.counter = 0;
        this.render();
    },
    render: function() {

        var self = this;
        _(this.collection.models).each(function(comment) {
            self.appendItem(comment);
        }, this);

    },
    getAttributes: function() {

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
            comment: this.$comment.val().trim(),
            moderation_required: true,
            reply_link: ''
        };

    },
    saveComment: function(e) {

        e.preventDefault();

        this.counter++;

        var self = this;
        var new_comment = new app.Comment();
        new_comment.save(this.getAttributes(),
                {
                    wait: true,
                    success: function(model, response) {
                        console.log(response);
                        if (response.error.length > 0) {
                            $('<div/>').addClass("alert alert-danger")
                                    .html(response.error)
                                    .prependTo($("#respond"))
                                    .hide()
                                    .fadeIn(1000)
                                    .delay(3000)
                                    .fadeOut(function() {
                                        $(this).remove()
                                    });
                        } else {
                            self.collection.add(new_comment);
                        }
                    }
                }
        );

    },
    appendItem: function(item) {
        $('ol.comment-list', this.el).append(this.template(item.toJSON()));
    }
});