var app = app || {};

// extending backbone model for comments model

app.Comment = Backbone.Model.extend({
    // default vars

    defaults: {
        comment_id: '',
        comment_post_id : '',
        comment_class: '',
        author: '',
        email: '',
        website: '',
        avatar: '',
        avatar_size: 96,
        comment_post_link: '',
        comment_iso_time: '',
        comment_date: '',
        comment: '',
        moderation_required: true
    },
    sync: function(method, model, options) {
        return $.ajax({
            type: 'POST',
            contentType: 'application/x-www-form-urlencoded',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-HTTP-Method-Override', 'POST');
            },
            //dataType: 'json',
            //url: '/index?id=' + this.get('id') + '&email=' + this.get('email')
            url: 'http://localhost/live-comments/wp-admin/admin-ajax.php?action=add_comment&author=' + this.get('author') + '&email=' + this.get('email') + '&url=' + this.get('website') + '&comment=' + this.get('comment' + '&comment_post_id=' + this.get('comment_post_id'))
        }).done(function(data) {
            alert(data);
            /*if (console && console.log) {
                console.log("Sample of data:", data.slice(0, 100));
            }*/
        });
    }


});