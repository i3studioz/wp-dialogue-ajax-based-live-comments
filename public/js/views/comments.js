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
        this.$email = this.$('#email');
        this.$website = this.$('#url');
        this.collection = new app.CommentList();
        //this.collection = new app.CommentList(app_vars.db_comments);
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
    getAvatarUrl: function($string) {
        return 'http://0.gravatar.com/avatar/' + md5($string) + '/?s=96'; // md5 it later
    },
    getAttributes: function() {

        return {
            comment_id: '',
            comment_depth: 1,
            author: this.$author.val().trim(),
            email: this.$email.val().trim(),
            website: this.$website.val().trim(),
            avatar: this.getAvatarUrl(this.$email.val().trim()),
            avatar_size: 96,
            comment_post_link: '',
            comment_iso_time: '',
            comment_date: '',
            comment: this.$comment.val().trim(),
            maderation_required: true
        };

    },
    saveComment: function(e) {

        e.preventDefault();

        this.counter++;
        //console.log(this.getAttributes());
        var comment = new app.Comment();
        comment.set(this.getAttributes());
        this.collection.add(comment);
        return false;
    },
    appendItem: function(item) {
        $('ol.comment-list', this.el).append(this.template(this.getAttributes()));
    }


});