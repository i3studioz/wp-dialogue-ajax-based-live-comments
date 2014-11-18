var app = app || {};
var $ = jQuery;
app.CommentView = Backbone.View.extend({
    el: $('#commentapp'),
    template: _.template($('#comments-template').html()),
    events: {
        'submit form#commentform' : 'saveComment'
    },
    initialize: function() {
        _.bindAll(this, 'render', 'saveComment', 'appendItem');
        
        this.$comment = this.$('#comment');
        this.$author = this.$('#author');
        this.$email = this.$('#email');
        this.$website = this.$('#url');
        
        this.collection = new app.CommentList();
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
    getAvatarUrl: function($string){
        return 'http://0.gravatar.com/avatar/'+md5($string)+'/?s=96'; // md5 it later
    },
    getAttributes: function() {

        return {
            comment: this.$comment.val().trim(),
            author: this.$author.val().trim(),
            email: this.$email.val().trim(),
            website: this.$website.val().trim(),
            avatar: this.getAvatarUrl(this.$email.val().trim())
        };

    },
    saveComment: function(e) {
        
         e.stopPropagation();
        
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