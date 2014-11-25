var app = app || {};

// extending backbone model for comments model

app.Comment = Backbone.Model.extend({
    
    // default vars
    
    defaults: {
        comment_id : '',
        comment_class : '',
        author  : '',
        email   : '',
        website : '',
        avatar  : '',
        avatar_size : 96,
        comment_post_link : '',
        comment_iso_time : '',
        comment_date : '',
        comment : '',
        moderation_required : true
    }
    
});