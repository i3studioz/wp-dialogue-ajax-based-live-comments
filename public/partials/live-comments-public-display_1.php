            <div class="row">
                <div id="commentapp" class="col-md-12">
                    <h1>Comments</h1>
                    <div class="col-md-12">
                        <ul id="comment-list" style="padding: 0">&nbsp;</ul>
                    </div>
                    <div class="col-md-12">
                        <div class="comment-form">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="author">Name</label>
                                <input type="text" class="form-control" id="author" placeholder="Emter Name">
                            </div>
                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                            </div>
                            <button id="save" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="info">
                <p>Written by <a href="https://github.com/arunsparx">Arun Singh</a></p>
            </div>       
        </div>
        <script type="text/template" id="comments-template">
            <li style="list-style:none" class="view">
            <div class="col-md-12">
                <img src="<%= avatar %>" class="col-md-2" />
                <p class="comment-text col-md-10"><%= comment %> - <a href="mailto:<%= email %>"><i class="comment-author"><%= author %></i></a></p>
            </div>
            <p>
            <button id="reply" class="btn btn-primary pull-right">Reply</button></p>
            </li>
        </script>
