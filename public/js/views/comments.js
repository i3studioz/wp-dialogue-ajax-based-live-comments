var app = app || {};
var $ = jQuery;
app.CommentView = Backbone.View.extend({
    el: $('#commentapp'),
    template: _.template($('#comments-template').html()),
    events: {
        'click button#save': 'saveComment'
    },
    initialize: function() {
        _.bindAll(this, 'render', 'saveComment', 'appendItem');
        
        this.$comment = this.$('#comment');
        this.$author = this.$('#author');
        this.$email = this.$('#email');
        
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
        return 'http://www.gravatar.com/avatar/'+md5($string); // md5 it later
    },
    getAttributes: function() {

        return {
            comment: this.$comment.val().trim(),
            author: this.$author.val().trim(),
            email: this.$email.val().trim(),
            avatar: this.getAvatarUrl(this.$email.val().trim())
        };

    },
    saveComment: function() {
        this.counter++;
        //console.log(this.getAttributes());
        var comment = new app.Comment();
        comment.set(this.getAttributes());
        this.collection.add(comment);
    },
    appendItem: function(item) {
        $('ul', this.el).append(this.template(this.getAttributes()));
    }


});