var app = app || {};

// extending backbone model for comments model

app.Comment = Backbone.Model.extend({
    
    // default vars
    
    defaults: {
        comment : '',
        author  : '',
        email   : '',
        website : '',
        avatar  : ''
    }
    
});