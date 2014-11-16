var app = app || {};

app.CommentList = Backbone.Collection.extend({
    
    //which model
    
    model : app.Comment,
    
    // using local storage for temporary demo
    // @todo connect with database for saving the comments
    
    localStorage: new Backbone.LocalStorage('comments-backbone'),
    
});